<?php
require "../auth_api.php";
require "../../functions.php";

$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['id'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing id"]);
    exit;
}

try {
    deleteDrink($input['id']);
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
