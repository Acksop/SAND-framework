<?php


namespace MVC\Classe;

class Session
{
    public static function start()
    {
        session_start();
        return;
    }

    public static function destroy()
    {
        session_destroy();
        return;
    }

    public static function setUserProfile($userProfile)
    {
        $_SESSION['userProfile'] = $userProfile;
        return;
    }

    public static function setId($id)
    {
        $_SESSION['id'] = $id;
        return;
    }

    public static function setUserName($username)
    {
        $_SESSION['username'] = $username;
        return;
    }

    public static function setToken($token)
    {
        $_SESSION['userToken'] = $token;
        return;
    }

    public static function setStorage($hybriauthStorage)
    {
        $_SESSION['storage'] = $hybriauthStorage;
        return;
    }

    public static function getStorage()
    {
        return $_SESSION['storage'];
    }

    public static function setHybridAuth($hybriauth)
    {
        $_SESSION['auth'] = $hybriauth;
        return;
    }

    public static function getHybridAuth()
    {
        return $_SESSION['auth'];
    }

    public static function isRegistered()
    {
        if (isset($_SESSION['userProfile'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function redirectIfNotRegistered()
    {
        if (isset($_SESSION['userProfile'])) {
            return;
        } else {
            header("location : " . Url::link_rewrite(false, 'error', []));
            die('Ooops, something was wrong...');
        }
    }
}
