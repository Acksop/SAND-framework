<?php

use MVC\Classe\Session;

Session::start();
$app = new MVC\Classe\Modular($name, 'symfony', $url_params);
$templateData = array('app' => $app);