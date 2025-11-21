<?php
// functions.php

define('XML_FILE', __DIR__ . '/drinks.xml');

/**
 * Load XML
 */
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

/**
 * Save XML
 */
function saveXml($xml) {
    $tmp = tempnam(sys_get_temp_dir(), 'xml');
    file_put_contents($tmp, $xml->asXML());
    rename($tmp, XML_FILE);
}

/**
 * Find drink by ID
 */
function findDrinkById($xml, $id) {
    $res = $xml->xpath("//jook[@id='{$id}']");
    return $res ? $res[0] : null;
}

/**
 * Generate new numeric ID
 */
function generateNewId($xml) {
    $max = 0;
    foreach ($xml->xpath("//jook") as $d) {
        $id = intval($d['id']);
        if ($id > $max) $max = $id;
    }
    return $max + 1;
}

/**
 * Add drink
 */
function addDrink($data) {
    $xml = loadXml();

    $category = $data['grupp'];

    // find group (grupp) with this id
    $group = null;
    foreach ($xml->grupp as $g) {
        if ((string)$g['id'] === $category) {
            $group = $g;
            break;
        }
    }

    if (!$group) {
        throw new Exception("Category '$category' not found in XML.");
    }

    if (!$group->joogid) {
        $group->addChild('joogid');
    }

    $newId = generateNewId($xml);

    $drink = $group->joogid->addChild('jook');
    $drink->addAttribute('id', $newId);
    $drink->addChild('jooginimi', $data['jooginimi']);
    $drink->addChild('kogus', intval($data['kogus']));
    $drink->addChild('topsitüüp', $data['topsitüüp']);
    $drink->addChild('maksmisviis', $data['maksmisviis']);

    saveXml($xml);

    return $newId;
}

/**
 * Edit drink
 */
function editDrink($data) {
    $xml = loadXml();

    $node = findDrinkById($xml, $data['id']);
    if (!$node) throw new Exception("Drink not found");

    // Update fields
    foreach (['jooginimi','kogus','topsitüüp','maksmisviis'] as $child) {
        if (isset($data[$child])) {
            $node->$child = $data[$child];
        }
    }

    // Update group if changed
    if (isset($data['grupp'])) {
        $oldGroup = $node->xpath('ancestor::grupp')[0];
        if ((string)$oldGroup['id'] !== $data['grupp']) {
            // Remove from old group
            $domNode = dom_import_simplexml($node);
            $domNode->parentNode->removeChild($domNode);

            // Add to new group
            $newGroup = null;
            foreach ($xml->grupp as $g) {
                if ((string)$g['id'] === $data['grupp']) {
                    $newGroup = $g;
                    break;
                }
            }
            if (!$newGroup->joogid) $newGroup->addChild('joogid');
            $newGroup->joogid->appendChild($node);
        }
    }

    saveXml($xml);
}

/**
 * Delete drink
 */
function deleteDrink($id) {
    $xml = loadXml();

    $node = findDrinkById($xml, $id);
    if (!$node) throw new Exception('Drink not found');

    $dom = dom_import_simplexml($node);
    $dom->parentNode->removeChild($dom);

    saveXml($xml);
}

/**
 * Export XML to JSON
 */
function exportJSON() {
    $xml = loadXml();
    return json_encode($xml, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
