<?php

namespace MVC\Classe;


class Dumper{

    public static function dump($var){
        echo "<pre>";
        if (is_bool($var)) {
            echo ($var) ? "true" : "false";
        } else {
            print_r($var);
        }
        echo "</pre>";
    }
    /**
     * Fonction Statique permettant d'initialiser les valeurs de php lors du script courant
     *
     * @return void
     */
    public static function setPHPvalues()
    {

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        ini_set('default_socket_timeout', -1);

        error_reporting(E_ALL);

        return;
    }
}