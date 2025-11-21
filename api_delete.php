<?php
header('Content-Type: application/json');
require 'functions.php';

try {
    if (!isset($_POST['id'])) throw new Exception("id missing");
    deleteDrink($_POST['id']);
    echo json_encode(['success'=>true]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
}
