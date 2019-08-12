<?php
session_start();
$app = new MVC\Classe\Modular($name);
$templateData = array('app' => $app);