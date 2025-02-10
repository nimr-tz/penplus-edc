<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once 'php/core/init.php';

$API_KEY = "your_secure_api_key"; // Define a secure key

// if (!isset($_GET['api_key']) || $_GET['api_key'] !== $API_KEY) {
//     echo json_encode(["error" => "Unauthorized"]);
//     exit;
// }

$override = new OverideData();
$result = $override->get('site', 'status', 1);

echo json_encode($result ?: ["message" => "No users found"]);
?>