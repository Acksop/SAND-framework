<?php

\MVC\Classe\Session::start();
//\MVC\Classe\Session::isregistered();

require CONFIG_PATH . DIRECTORY_SEPARATOR . "authentification-config-example.php";

//$hybridauth = new Hybridauth\Hybridauth($config);
$hybridauth = \MVC\Classe\Session::getHybridAuth();
//$adapters = $hybridauth->getConnectedAdapters();

$adapter = $hybridauth->getAdapter(\MVC\Classe\Session::getStorage()->get('provider'));

    \MVC\Classe\Dumper::dump($adapter);

$isConnected = $adapter->isConnected();
//Retrieve the user's profile
$userProfile = $adapter->getUserProfile();

//Inspect profile's public attributes
    \MVC\Classe\Dumper::dump($isConnected);
    \MVC\Classe\Dumper::dump($userProfile);

$templateData['adapters'] = [\MVC\Classe\Session::getStorage()->get('provider')=>$adapter];
