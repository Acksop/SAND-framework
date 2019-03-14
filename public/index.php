<?php

error_reporting(-1);
ini_set('display_errors', 1);

define("APPLICATION_PATH", dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."application");
define("VENDOR_PATH", dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."vendor");

//require APPLICATION_PATH.DIRECTORY_SEPARATOR."autoloader.php";
require VENDOR_PATH.DIRECTORY_SEPARATOR."autoload.php";

$poo_v1 = new \MVC\Classe\Application();
$poo_v1->launch();
