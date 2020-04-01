<?php


namespace MVC\Classe;


class Session
{

    static public function start()
    {
        session_start();
        return;
    }
    static public function destroy()
    {
        session_destroy();
        return;
    }

    static public function setUserProfile($userProfile)
    {
        $_SESSION['userProfile'] = $userProfile;
        return;
    }
    static public function setId($id)
    {
        $_SESSION['id'] = $id;
        return;
    }
    static public function setUserName($username)
    {
        $_SESSION['username'] = $username;
        return;
    }

    static public function setToken($token)
    {
        $_SESSION['userToken'] = $token;
        return;
    }

    static public function setStorage($hybriauthStorage)
    {
        $_SESSION['storage'] = $hybriauthStorage;
        return;
    }
    static public function getStorage()
    {
        return $_SESSION['storage'] ;
    }

    static public function setHybridAuth($hybriauth)
    {
        $_SESSION['auth'] = $hybriauth;
        return;
    }
    static public function getHybridAuth()
    {
        return $_SESSION['auth'] ;
    }

    static public function isRegistered()
    {
        if (isset($_SESSION['userProfile'])) {
            return true;
        } else {
            return false;
        }
    }

    static public function redirectIfNotRegistered()
    {
        if (isset($_SESSION['userProfile'])) {
            return ;
        } else {
            header("location : " . Url::link_rewrite(false, 'error', []));
            die('Ooops, something was wrong...');
        }
    }

}