<?php

namespace SAND\Classe;

class Dumper
{
    public static function dump($var)
    {
        echo "<pre>";
        if (is_bool($var)) {
            echo ($var) ? "true" : "false";
        } else {
            print_r($var);
        }
        echo "</pre>";
    }
    /**
     * Fonction Statique permettant d'initialiser les valeurs de php lors du script courant.
     * Erreurs affichées seulement en débogage ; sinon journalisées, sans
     * affichage (les messages contiennent les chemins du serveur).
     *
     * @return void
     */
    public static function setPHPvalues()
    {
        if (\SAND\Object\Environment::debug()) {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
        } else {
            ini_set('display_errors', 0);
            ini_set('display_startup_errors', 0);
            ini_set('log_errors', 1);
        }
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
        ini_set('default_socket_timeout', -1);

        error_reporting(E_ALL);

        return;
    }
	    public static function show_globals(){

        Dumper::dump($_SERVER);
        Dumper::dump($_GET);
        Dumper::dump($_POST);
        Dumper::dump($_FILES);
        Dumper::dump($_REQUEST);

    }

    public static function display_errors()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        ini_set('xdebug.max_nesting_level', -1);
        // E_ALL : notices et dépréciations comprises (migration vers PHP 8.3)
        error_reporting(E_ALL);
        return;
    }
    public static function needMoreTime($parsecs){
        ini_set('max_execution_time', "$parsecs"); //300 seconds = 5 minutes
        set_time_limit($parsecs);

    }
}
