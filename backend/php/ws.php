<?php

function postSolicitud() {
 
    $string_xml = file_get_contents("php://input");

    if (empty($string_xml)) {
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(['error' => 'No se recibió XML en la solicitud.']);
        return;
    }

    $params = array(
        'Userconect' => 'SiteAlmango',
        'Key' => 'd3d3LmF6bWl0YS5jb20=',
        'Proveedorid' => 1001,
        'Usuarioid' => 0,
        'Xmlsolicitud' => $string_xml
    );

    $wsdl_nuevo = 'http://desa.almango.com.uy//awsasolicitudser.aspx?wsdl';

    $options = array(
        'trace' => 1,
        'exceptions' => true,
    );

    try {
        $client_nuevo = new SoapClient($wsdl_nuevo, $options);
        $result_nuevo = $client_nuevo->Execute($params);
        $res_nuevo = $result_nuevo->Rescode;

        if ($res_nuevo == 0) {
            $respuesta_servicio = $result_nuevo;
        } else {
            $mensajeError_nuevo = $result_nuevo->Resmessage;
            $respuesta_servicio = array('error' => $mensajeError_nuevo);
        }

        $jsonData_nuevo = arrayToJson($respuesta_servicio);

        header('Content-Type: application/json');
        http_response_code(200);
        echo $jsonData_nuevo;
        
    } catch (SoapFault $e) {
        $errorResponse_nuevo = array(
            'error' => 'Error en la aplicación: ' . $e->getMessage()
        );

        $jsonError_nuevo = json_encode($errorResponse_nuevo);

        if ($jsonError_nuevo === false) {
            echo 'Error en la aplicación: ' . $e->getMessage();
        } else {
            header('Content-Type: application/json');
            http_response_code(500); 
            echo $jsonError_nuevo;
        }
    }
}


function consultarProveedor() {
    $wsdl = 'https://sis.almango.com.uy/awsoproveedores.aspx?wsdl';
    $params = array(
        'Userconect' => 'SiteAlmango',
        'Key' => 'd3d3LmF6bWl0YS5jb20='
    );
    $options = array(
        'trace' => 1,
        'exceptions' => true,
    );

    try {
        $client = new SoapClient($wsdl, $options);
        $result = $client->Execute($params);
        $res = $result->Rescode;

        if ($res == 0) {
            $proveedores = $result->Sdtproveedores->Item;
            return $proveedores;
        } else {
            $mensajeError = $result->Resmessage;
            return array('error' => $mensajeError);
        }
    } catch (SoapFault $e) {
        return array('error' => $e->getMessage());
    }
}

function getAll() {
    $wsdl = 'https://app.almango.com.uy/awsoserviciosdetall.aspx?wsdl';
    $params = array(
        'Userconect' => 'SiteAlmango',
        'Key' => 'd3d3LmF6bWl0YS5jb20=',
        'Proveedorid' => '1001'
    );
    $options = array(
        'trace' => 1,
        'exceptions' => true,
    );

    try {
        $client = new SoapClient($wsdl, $options);
        $result = $client->Execute($params);
        $res = $result->Rescode;

        if ($res == 0) {
            $Inventario = $result->Inventario;
            return $Inventario;
        } else {
            $mensajeError = $result->Resmessage;
            return array('error' => $mensajeError);
        }
    } catch (SoapFault $e) {
        return array('error' => $e->getMessage());
    }
}

function getDepartamentos() {
    $wsdl = 'https://app.almango.com.uy//awsozonas.aspx?wsdl';

    $params = array(
        'Userconect' => 'SiteAlmango',
        'Key' => 'd3d3LmF6bWl0YS5jb20=',
        'Paisiso' => 'uy'
    );
    $options = array(
        'trace' => 1,
        'exceptions' => true,
    );

    try {
        $client = new SoapClient($wsdl, $options);
        $result = $client->Execute($params);
        $res = $result -> Rescode;

        if ($res == 0) {
            return $result->Sdtzonas;
        } else {
            $mensajeError = $result->Resmessage;
            return array('error' => $mensajeError);
        }

    } catch (SoapFault $e) {
        return array('error' => $e->getMessage());
    }
}


function arrayToJson($response) {
    array_walk_recursive($response, function (&$value) {
        if (is_string($value)) {
            $value = trim(utf8_encode($value));
        }
    });

    $jsonData = json_encode($response);

    if ($jsonData === false) {
        return false;
    }

    return $jsonData;
}

function GET() {
    try {
        $proveedores = consultarProveedor();
        $getAll = getAll();
        $departamentos = getDepartamentos();
    
        $response = array(
            'proveedores' => $proveedores,
            'getAll' => json_decode($getAll),
            'departamentos' => $departamentos
        );
    
        //echo var_dump($response);
    
        $jsonData = arrayToJson($response);
    
        header('Content-Type: application/json');
        http_response_code(200);
        echo $jsonData;
        
    } catch (Exception $e) {
        $errorResponse = array(
            'error' => 'Error en la aplicación: ' . $e->getMessage()
        );
    
        $jsonError = json_encode($errorResponse);
    
        if ($jsonError === false) {
            echo 'Error en la aplicación: ' . $e->getMessage();
        } else {
            header('Content-Type: application/json');
            http_response_code(500); 
            echo $jsonError;
        }
    }
}


try {
    if (isset($_GET['method'])) {
        $method = $_GET['method'];

        switch ($method) {
            case 'GET':
                GET();
                break;
            case 'POST':
                postSolicitud();
                break;
            default:
                throw new Exception("Método no válido");
                break;
        }
    } else {
        throw new Exception("El parámetro 'method' no está presente en la URL");
    }
} catch (Exception $e) {

    $errorResponse = array(
        'error' => $e->getMessage()
    );

    header('Content-Type: application/json');
    http_response_code(400); 
    echo json_encode($errorResponse);
    exit();
}


?>

