<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");

// Get the request method
$method = $_SERVER["REQUEST_METHOD"];

if ($method === "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    if (isset($input["name"]) && isset($input["email"])) {
        echo json_encode(["message" => "User created", "user" => $input]);
    } else {
        echo json_encode(["error" => "Invalid input"]);
    }
} elseif ($method === "GET") {
    echo json_encode(["message" => "GET request received"]);
} elseif ($method === "PUT") {
    echo json_encode(["message" => "PUT request received"]);
} elseif ($method === "DELETE") {
    echo json_encode(["message" => "DELETE request received"]);
} else {
    echo json_encode(["error" => "Method not allowed"]);
}
?>
