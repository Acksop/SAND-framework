<?php

namespace MVC\Command;

class Conduit
{
    public static function help()
    {
        print "explaination of the command\n\n";
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
