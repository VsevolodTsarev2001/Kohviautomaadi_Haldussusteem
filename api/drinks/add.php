<?php
require "../auth_api.php";
require "../../functions.php";

$data = json_decode(file_get_contents("php://input"), true);

try {
    $id = addDrink($data);
    echo json_encode(["success" => true, "id" => $id]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
