<?php

$xml = simplexml_load_file("drinks.xml");

$json = json_encode($xml, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

file_put_contents("drinks.json", $json);

echo "Eksport valmis: <a href='drinks.json'>drinks.json</a>";
