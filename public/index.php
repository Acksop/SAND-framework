<?php

use MVC\Classe\Application;
use MVC\Classe\Dumper;

define("VENDOR_PATH", dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . "vendor");
require VENDOR_PATH . DIRECTORY_SEPARATOR . "autoload.php";

Dumper::setPHPvalues();

//print_r($_SERVER);die();

$poo_v5 = new Application();
$poo_v5->launch();
