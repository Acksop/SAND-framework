<?php


use MVC\Classe\Dumper;
use MVC\Classe\Logger;

//Dumper::dump($_SESSION);

\MVC\Classe\ControlleurAction::inserer('default.makeHttp11',[]);

$templateData = array("templating_a"=>'blade',"templating_b"=>'twig',"templating_c"=>'edge');
Logger::addLog('ok', 'Hello world');