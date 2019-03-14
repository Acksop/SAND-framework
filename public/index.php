<?php

error_reporting(-1);
ini_set('display_errors', 1);

define("APPLICATION_PATH", dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."application");
define("CLASSES_PATH", APPLICATION_PATH.DIRECTORY_SEPARATOR."class");


require CLASSES_PATH.DIRECTORY_SEPARATOR."application.class.php";

$poo_v1 = new Application();
$poo_v1->launch();
