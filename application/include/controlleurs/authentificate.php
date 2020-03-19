<?php

use MVC\Classe\Dumper;

require CONFIG_PATH . DIRECTORY_SEPARATOR . "authentification-config-example.php";

try {

    \MVC\Classe\Session::start();

    $hybridauth = new Hybridauth\Hybridauth($config);
    $storage = new Hybridauth\Storage\Session();
    $error = false;

    //
    // Event 1: User clicked SIGN-IN link
    //
    if (isset($url_params['provider'])) {
        // Validate provider exists in the $config
        if (in_array($url_params['provider'], $hybridauth->getProviders())) {
            // Store the provider for the callback event
            $storage->set('provider', $url_params['provider']);
            \MVC\Classe\Session::setStorage($storage);
        } else {
            $error = $url_params['provider'];
        }
    }

    //
    // Event 2: User clicked LOGOUT link
    //
    if (isset($url_params['logout'])) {
        if (in_array($url_params['logout'], $hybridauth->getProviders())) {
            // Disconnect the adapter
            $adapter = $hybridauth->getAdapter($url_params['logout']);
            $adapter->disconnect();
        } else {
            $error = $url_params['logout'];
        }
    }

    //
    // Handle invalid provider errors
    //
    if ($error !== false) {
        error_log('HybridAuth Error: Provider ' . json_encode($error) . ' not found or not enabled in $config');
        // Close the pop-up window
        echo "
            <script>
                window.opener.location.reload();
                window.close();
            </script>";
        exit;
    }



    //
    // Event 3: Provider returns via CALLBACK
    //
    if ($url_params['provider'] = $storage->get('provider')) {


        \MVC\Classe\Session::setHybridAuth($hybridauth);
        $hybridauth->authenticate($url_params['provider']);
        //Suite à l'authentification il ne permet plus de modifier la page il redirige directement sur le callback

        /*
        // Retrieve the provider record
        $adapter = $hybridauth->getAdapter($url_params['provider']);

        \MVC\Classe\Session::setUserProfile($adapter->getUserProfile());
        \MVC\Classe\Session::setToken($adapter->getAccessToken());

        $compte  = $config['callback'];
        // Close pop-up window
        echo <<<EOD

            <script>
                window.opener.location.href = '$compte';
                window.close();
            </script>
EOD;
        */




    }

} catch (Exception $e) {
    error_log($e->getMessage());
    echo $e->getMessage();
}
