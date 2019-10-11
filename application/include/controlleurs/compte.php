<?php

\MVC\Classe\Session::start();
//\MVC\Classe\Session::isregistered();

require CONFIG_PATH . DIRECTORY_SEPARATOR . "authentification-config-example.php";

$hybridauth = new Hybridauth\Hybridauth($config);
$adapters = $hybridauth->getConnectedAdapters();

$templateData['adapters'] = $adapters;

/*$templateData['extractedData'] = [
    'token' => $_SESSION['userToken'],
    'identifier' => $_SESSION['userProfile']->identifier,
    'email' => $_SESSION['userProfile']->email,
    'first_name' => $_SESSION['userProfile']->firstName,
    'last_name' => $_SESSION['userProfile']->lastName,
    'photoURL' => strtok($_SESSION['userProfile']->photoURL, '?'),
];*/
$templateData['extractedData'] = [];
