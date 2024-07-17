<?php
session_start();
require_once 'nusoap/lib/nusoap.php';
function conectar($url, $funcion, $parametros)
{
    $client = new nusoap_client($url, true);
    $err = $client->getError();
    if ($err) {
        echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
        echo '<h2>Debug</h2><pre>' . htmlspecialchars($client, ENT_QUOTES) . '</pre>';
        exit();
    }
    $result = $client->call($funcion, $parametros);
    return $result;
}
function getsolicitudes()
{
    $params = array(
        'Userconect' => 'SiteAlmango',
        'Key' => 'd3d3LmF6bWl0YS5jb20=',
    );
    $servicios = conectar('https://sis.almango.com.uy/awsocsolisitudes.aspx?wsdl', 'Execute', $params);
    $res = $servicios['Rescode'];
    if ($servicios && $res == 0) {
        $servicios = $servicios['Resmessage'];
    } else if ($servicios && $res > 0) {
        $servicios = $servicios['Resmessage'];
    }
    return $servicios;
}


function getServicios()
{
    $params = array(
        'Userconect' => 'SiteAlmango',
        'Key' => 'd3d3LmF6bWl0YS5jb20=',
    );
    $servicios = conectar('https://sis.almango.com.uy/awsoservicios.aspx?wsdl', 'Execute', $params);
    $res = $servicios['Rescode'];
    if ($servicios && $res == 0) {
        $servicios = $servicios['Sdtservicios']['Item'];
    } else if ($servicios && $res > 0) {
        $servicios = $servicios['Resmessage'];
    }
    return $servicios;
}

function findOneServicio($idServicio)
{
    $servicios = getServicios();
    $encontro = false;
    $detalle = null;
    $i = 0;
    while (!$encontro && $i < count($servicios)) {
        $servicio = $servicios[$i];
        if ($servicio['ServiciosId'] == $idServicio) {
            $encontro = true;
        }
        $i++;
    }
    return $servicio;
}

function getDepartamentos($pais)
{
    $params = array(
        'Userconect' => 'SiteAlmango',
        'Key' => 'd3d3LmF6bWl0YS5jb20=',
        'Paisiso' => $pais //uy
    );
    $departamentos = conectar('https://sis.almango.com.uy//awsozonas.aspx?wsdl', 'Execute', $params);
    $res = $departamentos['Rescode'];
    if ($departamentos && $res == 0) {
        $departamentos = $departamentos['Sdtzonas']['Departamentos']['DepartamentosItem'];
    } else if ($departamentos && $res > 0) {
        $departamentos = $departamentos['Resmessage'];
    }
    return $departamentos;
}

function getMunicipio($pais, $idDepartamento)
{
    $departamentos = getDepartamentos($pais);
    $municipios = '';
    $encontro = false;
    $i = 0;
    while (!$encontro && $i < count($departamentos)) {
        $departamento = $departamentos[$i];

        if ($departamento['DepartamentoId'] == $idDepartamento) {
            $encontro = true;
            $municipios = $departamento['Municipios']['MunicipiosItem'];
        }
        $i++;
    }

    return $municipios;
}

function getZonas($pais, $idDepartamento, $idMunicipio)
{
    $municipios = getMunicipio($pais, $idDepartamento);
    $zonas = '';
    $encontro = false;
    $i = 0;
    while (!$encontro && $i < count($municipios)) {
        $municipio = $municipios[$i];
        if ($municipio['MunicipioId'] == $idMunicipio) {
            $encontro = true;
            if ($municipio['Zonas'] != '') {
                if (!isset($municipio['Zonas']['ZonasItem']['ZonasID'])) {
                    $zonas = $municipio['Zonas']['ZonasItem'];
                } else {
                    $zonas = [$municipio['Zonas']['ZonasItem']];
                }
            }
        }
        $i++;
    }

    return $zonas;
}

function getProductos($rubro)
{
    $params = array(
        'Userconect' => 'SiteAlmango',
        'Key' => 'd3d3LmF6bWl0YS5jb20=',
        'Rubrosid' => $rubro,
        'Proveedorid' => $_SESSION['proveedor']
    );

    $servicios = conectar('https://sis.almango.com.uy/awsoserviciosdet.aspx?wsdl', 'Execute', $params);
    $res = $servicios['Rescode'];
    if ($servicios && $res == 0) {
        if (!isset($servicios['Sdtservicios']['Producto']['Item']['ProductoID'])) {
            $servicios = $servicios['Sdtservicios']['Producto']['Item'];
        } else {
            $servicios = [$servicios['Sdtservicios']['Producto']['Item']];
        }
    } else if ($servicios && $res > 0) {
        $servicios = $servicios['Resmessage'];
    }
    return $servicios;
}

/* function getProductosDetalle($rubro, $proveedor, $idProducto)
{
   $params = array(
        'Userconect' => 'SiteAlmango',
        'Key' => 'd3d3LmF6bWl0YS5jb20=',
        'Rubrosid' => $rubro, //1
        'Proveedorid' => $_SESSION['proveedor']
    );
    $servicios = conectar('https://sis.almango.com.uy/awsoserviciosdet.aspx?wsdl', 'Execute', $params);
    $res = $servicios['Rescode'];
    $producto = '';

    if ($servicios && $res == 0) {
        if (!isset($servicios['Sdtservicios']['Producto']['Item']['ProductoID'])) {
            $servicios = $servicios['Sdtservicios']['Producto']['Item'];
        } else {
            $servicios = [$servicios['Sdtservicios']['Producto']['Item']];
        }
        $encontro = false;
        $i = 0;
        while (!$encontro && $i < count($servicios)) {
            $servicio = $servicios[$i];
            if ($servicio['ProductoID'] == $idProducto) {
                $producto = $servicio['Detalles']['DetalleItem'];
                $encontro = true;
            }

            $i++;
        }
    } else if ($servicios && $res > 0) {
        $producto = $servicios['Resmessage'];
    }

    return $producto;
}
 */

function getProductosDetalle($rubro, $proveedor, $idProducto = null)
{
    $params = array(
        'Userconect' => 'SiteAlmango',
        'Key' => 'd3d3LmF6bWl0YS5jb20=',
        'Rubrosid' => $rubro,
        'Proveedorid' => $proveedor
    );

    $servicios = conectar('https://sis.almango.com.uy/awsoserviciosdet.aspx?wsdl', 'Execute', $params);
    var_dump($servicios);
    $res = $servicios['Rescode'];
    $producto = array(); // Initialize an empty array to hold the results

    if ($servicios && $res == 0) {
        $items = $servicios['Sdtservicios']['Producto']['Item'];

        // Convert to array if it's not already (for single product)
        if (!is_array($items)) {
            $items = array($items);
        }

        // If a specific $idProducto is provided, find and return the matching product
        if ($idProducto !== null) {
            foreach ($items as $item) {
                if (isset($item['ProductoID']) && $item['ProductoID'] == $idProducto) {
                    $producto = array($item['Detalles']['DetalleItem']); // Wrap the single product in an array
                    break; // Found the product, exit the loop
                }
            }
        } else {
            // If no $idProducto is provided, return all products
            $foundProducts = array();
            foreach ($items as $item) {
                $foundProducts[] = $item['Detalles']['DetalleItem'];
            }
            $producto = array($foundProducts); // Wrap the array of products in another array
        }
    } else if ($servicios && $res > 0) {
        $producto = array(array($servicios['Resmessage'])); // Wrap the error message in a double array
    }

    return $producto;
}










function findOneProductoDetalle($rubro, $proveedor, $idProducto, $idDetalle)
{
    $detalles = getProductosDetalle($rubro, 21034, $idProducto);

    $encontro = false;
    $detalle = null;
    $i = 0;
    while (!$encontro && $i < count($detalles)) {
        $detalle = $detalles[$i];
        if ($detalle['DetalleID'] == $idDetalle) {
            $encontro = true;
        }

        $i++;
    }

    return $detalle;
}

function getFormasDePago($proveedor)
{

    $params = array(
        'Userconect' => 'SiteAlmango',
        'Key' => 'd3d3LmF6bWl0YS5jb20=',
        'Proveedorid' => $_SESSION['proveedor']
    );

    //$servicios = conectar('http://sis.almango.com.uy/awsoserviciosmasdat.aspx?wsdl', 'Execute', $params);
    $servicios = conectar('https://sis.almango.com.uy/awsoserviciosmasdat.aspx?wsdl', 'Execute', $params);
    $res = $servicios['Rescode'];

    if ($servicios && $res == 0) {
        $servicios = $servicios['Sdtmasdatos']['ModoDePago'];
    } else if ($servicios && $res > 0) {
        $servicios = $servicios['Resmessage'];
    }

    return $servicios;
}

function getProveedores()
{

    $params = array(
        'Userconect' => 'SiteAlmango',
        'Key' => 'd3d3LmF6bWl0YS5jb20='

    );

    //$servicios = conectar('http://sis.almango.com.uy/awsoserviciosmasdat.aspx?wsdl', 'Execute', $params);
    $servicios = conectar('https://sis.almango.com.uy/awsoproveedores.aspx?wsdl', 'Execute', $params);
    $res = $servicios['Rescode'];

    if ($servicios && $res == 0) {
        $servicios = $servicios['Sdtproveedores']['Item'];
    } else if ($servicios && $res > 0) {
        $servicios = $servicios['Resmessage'];
    }

    return $servicios;
}

function getTurnos($proveedor)
{

    $params = array(
        'Userconect' => 'SiteAlmango',
        'Key' => 'd3d3LmF6bWl0YS5jb20=',
        'Proveedorid' => $_SESSION['proveedor']
    );

    $servicios = conectar('https://sis.almango.com.uy/awsoserviciosmasdat.aspx?wsdl', 'Execute', $params);
    $res = $servicios['Rescode'];

    if ($servicios && $res == 0) {
        $servicios = $servicios['Sdtmasdatos']['TurnosInstalacion'];
    } else if ($servicios && $res > 0) {
        $servicios = $servicios['Resmessage'];
    }

    return $servicios;
}


function generarSolicitud($string_xml)
{
    $params = array(
        'Userconect' => 'test',
        'Key' => 'd3d3LmF6bWl0YS5jb20=',
        'Proveedorid' => $_SESSION['proveedor'],
        'Usuarioid' => 'web',
        'Xmlsolicitud' => $string_xml
    );


    $res = conectar('http://sis.almango.com.uy//awsasolicitudser.aspx?wsdl', 'Execute', $params);


    return $res;
}

function cargarArrayLevel1($Items)
{
    $Level1 = array(
        'Level1' => array()
    );

    for ($i = 0; $i < count($Items); $i++) {
        $Level1['Level1']['Item'] = $Items[$i];
    }

    return $Level1;
}
