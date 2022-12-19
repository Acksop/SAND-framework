<?php

define("VENDOR_PATH", dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."vendor");
require VENDOR_PATH.DIRECTORY_SEPARATOR."autoload.php";

\SAND\Classe\Dumper::setPHPvalues();

//print_r($_SERVER);die();

$poo_v5 = new \SAND\Classe\Application();
$poo_v5->launch();
