<?php

// CHargement du source XML
$xml = new DOMDocument;
$xml->load(__DIR__ . DIRECTORY_SEPARATOR .'jdepend-summary.xml');

$xsl = new DOMDocument;
$xsl->load(__DIR__ . DIRECTORY_SEPARATOR .'jdepend-summary.xsl');

// Configuration du transformateur
$proc = new XSLTProcessor;
$proc->importStyleSheet($xsl); // attachement des règles xsl

echo $proc->transformToXML($xml);
