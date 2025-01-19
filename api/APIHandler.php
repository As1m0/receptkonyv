<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once("../config.php");
require_once("../core/DBParam.php");
require_once("../core/DBHandler.php");
require_once("../core/Model.php");
require_once("../core/Enums.php");

if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
    handleApiRequest();
    return;
}

function handleApiRequest(): void
{
    header('Content-Type: application/json');
    
    try {
        $lastChecked = $_GET['lastChecked'] ?? null;
        
        if (!$lastChecked) {
            throw new Exception("Missing lastChecked parameter.");
        }

        $date = new DateTime($_GET['lastChecked']);
        $formattedDate = $date->format('Y-m-d H:i:s');

        DBHandler::Init();
        $data = Model::CheckNewRecepie($formattedDate);
        DBHandler::Disconnect();

        if (empty($data))
        {
            $response = [
                'status' => 'success',
                "lastChecked" => $formattedDate
            ];
        } else {
            $response = [
                'status' => 'success',
                "lastChecked" => $formattedDate,
                'RecepieName' => $data[0]["recept_neve"],
                'RecepieID' => $data[0]["recept_id"]
            ];
        }

        error_log(json_encode($response));

        echo json_encode($response);
    } catch (Exception $ex) {
        http_response_code(500);
        $errorResponse = [
            'status' => 'error',
            'message' => $ex->getMessage()
        ];

        error_log(json_encode($errorResponse));

        echo json_encode($errorResponse);
    }
    exit;
}
