<?php

define('XML_FILE', __DIR__ . '/drinks.xml');

function loadXml() {
    if (!file_exists(XML_FILE)) {
        throw new Exception('drinks.xml not found');
    }

    libxml_use_internal_errors(true);
    $xml = simplexml_load_file(XML_FILE, 'SimpleXMLElement', LIBXML_NOWARNING | LIBXML_NOERROR);

    if (!$xml) {
        throw new Exception("XML parsing error.");
    }

    return $xml;
}


function saveXml($xml) {
    $tmp = tempnam(sys_get_temp_dir(), 'xml');
    file_put_contents($tmp, $xml->asXML());
    rename($tmp, XML_FILE);
}


function logAction($text) {
    $line = "[" . date("Y-m-d H:i:s") . "] " . $text . "\n";
    file_put_contents(__DIR__ . "/logs/actions.log", $line, FILE_APPEND);
}


function validateDrink($data) {
    if (!isset($data['jooginimi']) || trim($data['jooginimi']) === '')
        throw new Exception("Jooginimi on kohustuslik");

    if (!isset($data['kogus']) || !is_numeric($data['kogus']) || $data['kogus'] <= 0)
        throw new Exception("Kogus peab olema positiivne");

    if (!isset($data['grupp']) || trim($data['grupp']) === '')
        throw new Exception("Kategooria on kohustuslik");

    if (!isset($data['maksmisviis']) || trim($data['maksmisviis']) === '')
        throw new Exception("Maksmisviis on kohustuslik");
}


function findDrinkById($xml, $id) {
    $res = $xml->xpath("//jook[@id='{$id}']");
    return $res ? $res[0] : null;
}


function generateNewId($xml) {
    $max = 0;
    foreach ($xml->xpath("//jook") as $d) {
        $id = intval($d['id']);
        if ($id > $max) $max = $id;
    }
    return $max + 1;
}


function addDrink($data) {
    validateDrink($data);

    $xml = loadXml();
    $category = $data['grupp'];

    foreach ($xml->grupp as $g) {
        if ((string)$g['id'] === $category) {
            $group = $g;
            break;
        }
    }

    if (!$group) throw new Exception("Category '$category' not found in XML.");

    if (!$group->joogid) $group->addChild('joogid');

    $newId = generateNewId($xml);

    $drink = $group->joogid->addChild('jook');
    $drink->addAttribute('id', $newId);
    $drink->addChild('jooginimi', $data['jooginimi']);
    $drink->addChild('kogus', intval($data['kogus']));
    $drink->addChild('topsitüüp', $data['topsitüüp']);
    $drink->addChild('maksmisviis', $data['maksmisviis']);

    saveXml($xml);

    logAction("ADD: id=$newId name={$data['jooginimi']}");

    return $newId;
}


function editDrink($data) {
    validateDrink($data);

    $xml = loadXml();
    $node = findDrinkById($xml, $data['id']);
    if (!$node) throw new Exception('Drink not found');

    foreach (['jooginimi','kogus','topsitüüp','maksmisviis'] as $child) {
        if (isset($data[$child])) {
            $node->$child = $data[$child];
        }
    }

    saveXml($xml);

    logAction("EDIT: id={$data['id']} name={$data['jooginimi']}");
}


function deleteDrink($id) {
    $xml = loadXml();

    $node = findDrinkById($xml, $id);
    if (!$node) throw new Exception('Drink not found');

    $dom = dom_import_simplexml($node);
    $dom->parentNode->removeChild($dom);

    saveXml($xml);

    logAction("DELETE: id=$id");
}

function exportJSON() {
    $xml = loadXml();
    return json_encode($xml, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
