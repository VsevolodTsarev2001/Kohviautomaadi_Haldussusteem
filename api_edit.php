<?php
header('Content-Type: application/json');
require 'functions.php';

try {
    editDrink($_POST);
    echo json_encode(['success'=>true]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
}
