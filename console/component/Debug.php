<?php

namespace MVC\Component;

/**
 *  Composant permettant de debugger et d'initialiser certaines valeurs de php
 * @package Default
 */
class Debug
{
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
        date_default_timezone_set('Europe/Paris');

        error_reporting(E_ALL);

        return;
    }
}
