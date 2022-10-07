<?php

namespace SAND\Command\Sand;
/**
 * Class Conduit
 * Commande Système du Framework permettant de gérer les conduits
 * @package MVC\Command\Sand
 */
class Conduit
{
    /**
     * Méthode permettant d'afficher l'aide de la commande
     */
    public static function help()
    {
        print "Cette commande permet de manipuler les conduits du framework\n\n";
        print "Elle peut accepter les attributs suivants\n";
        print "\t- add : pour ajouter un conduit symfony\n";
    }

    public static function add()
    {
        print "adding conduit...\n\n";
        print "Quel est le nom du conduit symfony a ajouter? ";
        $conduit = trim(fgets(STDIN));


        $shell_controlleur = shell_exec('cp ' . CONSOLE_PATH . '/skel/conduit.php.skel ' . ACTION_PATH . '/' . ucfirst($conduit) . 'Conduit.php');
        $controlleur = file_get_contents(CONTROLLERS_PATH . '/' . ucfirst($conduit) . 'Conduit.php');
        $controlleur = preg_replace('%%CONDUIT%%', ucfirst($conduit), $controlleur);
        file_put_contents(CONTROLLERS_PATH . '/' . ucfirst($conduit) . 'Conduit.php', $controlleur);
        print $shell_controlleur;
    }
}
