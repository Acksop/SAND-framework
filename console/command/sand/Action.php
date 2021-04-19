<?php

namespace MVC\Command\Sand;
/**
 * Class Action
 * Commande Système du Framework permettant de gérer les actions
 * @package MVC\Command\Sand
 */
class Action
{
    /**
     * Méthode permettant d'afficher l'aide de la commande
     */
    public static function help()
    {
        print "Cette commande permet de manipuler les actions du framework\n\n";
        print "Elle peut accepter les attributs suivants\n";
        print "\t- add : pour ajouter une action\n";
    }

    public static function add()
    {
        print "adding action...\n\n";
        print "Quel est le nom de l'action a ajouter? ";
        $action = trim(fgets(STDIN));


        $shell_controlleur = shell_exec('cp ' . CONSOLE_PATH . '/skel/action.php.skel ' . ACTION_PATH . '/' . ucfirst($action) . 'Action.php');
        $controlleur = file_get_contents(CONTROLLERS_PATH . '/' . ucfirst($action) . 'Action.php');
        $controlleur = preg_replace('%%ACTION%%', ucfirst($action), $controlleur);
        file_put_contents(CONTROLLERS_PATH . '/' . ucfirst($action) . 'Action.php', $controlleur);
        print $shell_controlleur;
    }
}
