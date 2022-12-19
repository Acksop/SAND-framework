<?php


use SAND\Classe\Dumper;
use SAND\Classe\Logger;

//Dumper::dump($_SESSION);

\SAND\Classe\ControlleurAction::inserer('default.makeHttp11', []);

$templateData["templating_a"] ='blade';
$templateData["templating_b"] ='twig';
$templateData["templating_c"] ='edge';
Logger::addLog('ok', 'Hello world');
