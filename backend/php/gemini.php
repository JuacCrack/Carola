<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function geminiAPI($query)
{

    $api_key = 'AIzaSyAda_s4KRy6SirOFbV4WaO5upQNqdMX4tY';

    $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro-latest:generateContent?key=' . $api_key;

    $data = [
        "contents" => [
            [
                "parts" => $query
            ]
        ],
        "generationConfig" => [
            "temperature" => 1,
            "topK" => 64,
            "topP" => 0.95,
            "maxOutputTokens" => 50,
            "stopSequences" => []
        ],
        "safetySettings" => [
            ["category" => "HARM_CATEGORY_HARASSMENT", "threshold" => "BLOCK_NONE"],
            ["category" => "HARM_CATEGORY_HATE_SPEECH", "threshold" => "BLOCK_NONE"],
            ["category" => "HARM_CATEGORY_SEXUALLY_EXPLICIT", "threshold" => "BLOCK_NONE"],
            ["category" => "HARM_CATEGORY_DANGEROUS_CONTENT", "threshold" => "BLOCK_NONE"]
        ]
    ];

    $options = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data)
    ];

    $ch = curl_init();

    try {
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception('Error en cURL: ' . curl_error($ch));
        }

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code !== 200) {
            throw new Exception('Respuesta inesperada del servidor: ' . $http_code);
        }

        $response_data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Error al decodificar la respuesta JSON: ' . json_last_error_msg());
        }

        return $response_data;

    } catch (Exception $e) {
        return json_encode([
            "error" => $e->getMessage()
        ]);
    } finally {
        curl_close($ch);
    }

}

try {

    // session_start();

    // if(isset($_GET['index'])) {
    //     $index = $_GET['index'];

    //     $_SESSION['index'] = $index;
        
    //     if($_SESSION['index'] === '10') {
    //         if(time() - $_SESSION['hora'] >= 3600) {
    //             $_SESSION['index'] = 0;
    //             $_SESSION['hora'] = time();
    //         } else {
    //             throw new Exception('Parece ser que no puedo ayudarte');
    //         }
    //     }
    // }    

    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Error al decodificar el JSON de entrada: ' . json_last_error_msg());
    }

    $data ?? null;
    if ($data === null) {
        throw new Exception('La conversación no fue proporcionada');
    }

    $response = geminiAPI($data);

    if (!isset($response['error'])) {
        http_response_code(200);
        echo json_encode([
            "response" => $response
        ]);
    } else {
        throw new Exception($response["error"]);
    }

} catch (Exception $e) {
    http_response_code(200);
    echo json_encode([
        "error" => $e->getMessage()
    ]);
}

?>