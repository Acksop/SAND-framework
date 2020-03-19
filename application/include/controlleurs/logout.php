<?php

use MVC\Classe\Dumper;

require CONFIG_PATH . DIRECTORY_SEPARATOR . "authentification-config-example.php";

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
            header("location: ".MVC\Classe\Url::link_rewrite(false,'accueil'));
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



} catch (Exception $e) {
    error_log($e->getMessage());
    echo $e->getMessage();
}
