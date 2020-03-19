<?php

\MVC\Classe\Session::start();

require CONFIG_PATH . DIRECTORY_SEPARATOR . "authentification-config-example.php";

$hybridauth = new Hybridauth\Hybridauth($config);
$adapters = $hybridauth->getConnectedAdapters();

$templateData['hybridauth'] = $hybridauth;
$templateData['adapters'] = $adapters;