<?php

use MVC\Classe\Dumper;

\MVC\Classe\Session::start();
\MVC\Classe\Session::redirectIfNotRegistered();

require CONFIG_PATH . DIRECTORY_SEPARATOR . "hybrid-authentification-config-example.php";

$hybridauth = new Hybridauth\Hybridauth($config);
$hybridauth->authenticate(\MVC\Classe\Session::getStorage()->get('provider'));
$adapters = $hybridauth->getConnectedAdapters();
foreach ($adapters as $adapter){
 $userProfile[] = $adapter->getUserProfile();
    \MVC\Classe\Session::setId($adapter->getUserProfile()->identifier);
    \MVC\Classe\Session::setUserName($adapter->getUserProfile()->displayName);
}
\MVC\Classe\Session::setUserProfile($userProfile);

//Dumper::dump($_SESSION);

$templateData['adapters'] = $adapters;