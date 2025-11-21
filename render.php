<?php
$xml = new DOMDocument;
$xml->load("drinks.xml");

$xsl = new DOMDocument;
$xsl->load("drinks_html.xsl");

$proc = new XSLTProcessor;
$proc->importStylesheet($xsl);

echo $proc->transformToXML($xml);
?>
