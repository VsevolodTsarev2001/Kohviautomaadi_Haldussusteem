<?php
// Laeme XML
$xml = new DOMDocument;
$xml->load('drinks.xml');

// Laeme XSL
$xsl = new DOMDocument;
$xsl->load('drinks.xsl');

// XSLT töötlemine
$processor = new XSLTProcessor;
$processor->importStylesheet($xsl);

// Teisendame XML → HTML
echo $processor->transformToXML($xml);
?>
