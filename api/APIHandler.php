<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

require_once("../config.php");
require_once("../core/DBParam.php");
require_once("../core/DBHandler.php");
require_once("../core/Model.php");
require_once("../core/Enums.php");

ini_set('display_errors', 1);
error_reporting(E_ALL);

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

        $lastChecked = str_replace("T", " ", $lastChecked);

        DBHandler::Init();
        $data = Model::CheckNewRecepie($lastChecked);
        DBHandler::Disconnect();

        if (empty($data)) {
            $response = [
                'status' => 'success',
                "lastChecked" => $lastChecked,
                'newRecipe' => ""
            ];
        } else {
            $response = [
                'status' => 'success',
                "lastChecked" => $lastChecked,
                'newRecipe' => $data[0]["recept_neve"]
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
