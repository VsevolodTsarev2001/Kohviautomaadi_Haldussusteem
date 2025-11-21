<?php
header('Content-Type: application/json');
require 'functions.php';

try {
    $id = addDrink($_POST);
    echo json_encode(['success'=>true,'id'=>$id]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
}
