<?php

require CONFIG_PATH . DIRECTORY_SEPARATOR . "hybrid-authentification-config-example.php";

try {

    \MVC\Classe\Session::start();

    $hybridauth = new Hybridauth\Hybridauth($config);
    $storage = new Hybridauth\Storage\Session();
    $error = false;

    //
    // Event 2: User clicked LOGOUT link
    //
    if (isset($url_params['logout'])) {
        if (in_array($url_params['logout'], $hybridauth->getProviders())) {
            // Disconnect the adapter
            $adapter = $hybridauth->getAdapter($url_params['logout']);
            $adapter->disconnect();
            \MVC\Classe\Session::destroy();
            header("location: " . MVC\Classe\Url::link_rewrite(false, 'accueil'));
        } else {
            $error = $url_params['logout'];
        }
    }


} catch (Exception $e) {
    error_log($e->getMessage());
    echo $e->getMessage();
}
