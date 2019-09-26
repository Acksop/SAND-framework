<?php


namespace MVC\Classe;


class Session
{

    static public function isRegistered()
    {
        if (isset($_SESSION['userProfile'])) {
            return;
        } else {
            header("location : " . Url::link_rewrite(false, 'error', []));
            die('Ooops, something was wrong...');
        }
    }

}