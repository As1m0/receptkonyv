<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

require_once("../config.php");
require_once("../core/DBParam.php");
require_once("../core/DBHandler.php");
require_once("../core/Model.php");
require_once("../core/RecepieHandler.php");
require_once("../core/enums.php");

session_start();
$userId = $_SESSION['userID'];

// Get the posted data
$data = json_decode(file_get_contents('php://input'), true);
$itemId = $data['item_id'];
$response = array();

try{
    DBHandler::Init();
    $result = Model::saveToFavorites($userId, $itemId);
    DBHandler::Disconnect();
}
catch(DBException $error)
{
    $response['success'] = false;
    $response['error'] = $error->getMessage();
    echo json_encode($response);
}
    

$response['success'] = true;
$response['result'] = $result;
$response['ids'] = "User id: ".$userId.", Recepie id: ".$itemId;
echo json_encode($response);

