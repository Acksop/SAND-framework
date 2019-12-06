<?php

require CONFIG_PATH . DIRECTORY_SEPARATOR . "authentification-config-example.php";

try {
    //Feed configuration array to Hybridauth
    $hybridauth = new \Hybridauth\Hybridauth($config);

    //Then we can proceed and sign in with Twitter as an example. If you want to use a diffirent provider,
    //simply replace 'Twitter' with 'Google' or 'Facebook'.

    //Attempt to authenticate users with a Twitter provider
    $adapter = $hybridauth->authenticate('Twitter');
    //Returns a boolean of whether the user is connected with Twitter
    $isConnected = $adapter->isConnected();

    if ($isConnected == false) {
        //Attempt to authenticate users with a Google provider
        $adapter = $hybridauth->authenticate('Google');
        $isConnected = $adapter->isConnected();
    }
    if ($isConnected == false) {
        //Attempt to authenticate users with a Facebook provider
        $adapter = $hybridauth->authenticate('Facebook');
        $isConnected = $adapter->isConnected();
    }
    if ($isConnected == false) {
        //Attempt to authenticate users with a Github provider
        $adapter = $hybridauth->authenticate('Github');
        $isConnected = $adapter->isConnected();
    }


    if ($isConnected) {
        session_start();
        //Retrieve the user's token
        $token = $adapter->getAccessToken();
        $_SESSION['accessToken'] = $token;

        //Retrieve the user's profile
        $userProfile = $adapter->getUserProfile();
        $_SESSION['userProfile'] = $userProfile;

        //Disconnect the adapter
        $adapter->disconnect();

        header("location:" . Url::link_rewrite(false, 'compte', []));

    } else {

        header("location:" . Url::link_rewrite(false, 'error', []));

    }

} catch (\Exception $e) {
    echo 'Oops, we ran into an issue! ' . $e->getMessage();
}