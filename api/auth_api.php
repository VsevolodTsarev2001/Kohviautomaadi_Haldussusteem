<?php
require_once __DIR__ . '/../functions_users.php';
header('Content-Type: application/json');

if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
    http_response_code(401);
    echo json_encode(["error" => "Missing Authorization header"]);
    exit;
}

$auth = trim(str_replace("Bearer", "", $_SERVER["HTTP_AUTHORIZATION"]));

$xml = loadUsers();
$valid = false;
$currentUser = null;

foreach ($xml->user as $u) {
    if ((string)$u->token === $auth) {
        $valid = true;
        $currentUser = $u;
        break;
    }
}

if (!$valid) {
    http_response_code(403);
    echo json_encode(["error" => "Invalid API token"]);
    exit;
}
