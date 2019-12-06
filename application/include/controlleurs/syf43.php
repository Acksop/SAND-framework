<?php
\MVC\Classe\Session::start();
$app = new MVC\Classe\Modular($name);
$templateData = array('app' => $app);