<?php
require "../auth_api.php";
require "../../functions.php";

$xml = loadXml();

$data = [];

foreach ($xml->grupp as $g) {
    foreach ($g->joogid->jook as $j) {
        $data[] = [
            "id" => (int)$j['id'],
            "name" => (string)$j->jooginimi,
            "amount" => (int)$j->kogus,
            "cup" => (string)$j->topsitüüp,
            "payment" => (string)$j->maksmisviis,
            "category" => (string)$g['id']
        ];
    }
}

echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
