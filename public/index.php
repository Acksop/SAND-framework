<?php

error_reporting(-1);
ini_set('display_errors', 1);


define("VENDOR_PATH", dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."vendor");
require VENDOR_PATH.DIRECTORY_SEPARATOR."autoload.php";

$poo_v5 = new \MVC\Classe\Application();
$poo_v5->launch();
