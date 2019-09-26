<?php
/**
 * A simple example that shows how to use multiple providers, opening provider authentication in a pop-up.
 */

use Hybridauth\Hybridauth;

require CONFIG_PATH . DIRECTORY_SEPARATOR . "authentification-config-example.php";

try {

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
            $storage->set('provider', $_GET['provider']);
        } else {
            $error = $_GET['provider'];
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
    if ($error) {
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
    if ($provider = $storage->get('provider')) {

        $hybridauth->authenticate($provider);
        $storage->set('provider', null);

        // Retrieve the provider record
        $adapter = $hybridauth->getAdapter($provider);
        $userProfile = $adapter->getUserProfile();
        $accessToken = $adapter->getAccessToken();

        // Close pop-up window
        echo "
            <script>
                window.opener.location.reload();
                window.close();
            </script>";

    }

} catch (Exception $e) {
    error_log($e->getMessage());
    echo $e->getMessage();
}
