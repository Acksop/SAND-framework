<?php 

define("APPLICATION_PATH", dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."application");
define("CLASSES_PATH", APPLICATION_PATH.DIRECTORY_SEPARATOR."classes");

require CLASSES_PATH.DIRECTORY_SEPARATOR."application.class.php";

$poo_v1 = new Applications();
$poo_v1->launch();