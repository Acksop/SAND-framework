<?php


use MVC\Classe\Dumper;
use MVC\Classe\Logger;

//Dumper::dump($_SESSION);

\MVC\Classe\ControlleurAction::inserer('default.makeHttp11', []);

$templateData["templating_a"] ='blade';
$templateData["templating_b"] ='twig';
$templateData["templating_c"] ='edge';
Logger::addLog('ok', 'Hello world');
