<?php

namespace MVC\Command;
/**
 * Class Symfony
 * Commande Système du Framework permettant de gèrer les modules Symfony
 * @package MVC\Command
 */
class Symfony
{
    /**
     * Méthode permettant d'afficher l'aide de la commande
     */
    public static function help()
    {
        print "explaination of the command\n\n";
    }

    public static function stabilize()
    {
        print "stabilize symfony module...\n\n";
        //$symfony_module = shell_exec('sudo cp '.CONSOLE_PATH.'/skel/symfony '.VENDOR_PATH.' -Rf');
    }
}
