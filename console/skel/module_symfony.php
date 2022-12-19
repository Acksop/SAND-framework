<?php

use SAND\Classe\Session;

Session::start();
$app = new SAND\Classe\Modular($name, '%%MODULE%%', $url_params);
$templateData = array('app' => $app);
