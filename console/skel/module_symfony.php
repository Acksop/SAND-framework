<?php

use MVC\Classe\Session;

Session::start();
$app = new MVC\Classe\Modular($name, 'MODULE', $url_params);
$templateData = array('app' => $app);
