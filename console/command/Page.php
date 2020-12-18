<?php

namespace MVC\Command;

class Page
{
    public static function help()
    {
        print "explaination of the command\n\n";
    }

    /**
     * TODO: ajouter en fonction du type de template  (blade ou twig)
     */
    public static function add()
    {
        print "adding page...\n\n";
        print "Quel est le nom de la page a ajouter? ";
        $page = trim(fgets(STDIN));

        $git_controlleur = shell_exec('cp ' . CONSOLE_PATH . '/skel/page.php ' . CONTROLLERS_PATH . '/' . $page . '.php');
        $controlleur = file_get_contents(CONTROLLERS_PATH . '/' . $page . '.php');
        $controlleur = preg_replace('%PAGE%', $page, $controlleur);
        file_put_contents(CONTROLLERS_PATH . '/' . $page . '.php', $controlleur);
        print $git_controlleur;
        $git_modele = shell_exec('cp ' . CONSOLE_PATH . '/skel/page.model ' . MODELS_PATH . '/' . $page . '.model');
        $controlleur = file_get_contents(MODELS_PATH . '/' . $page . '.model');
        $controlleur = preg_replace('%PAGE%', $page, $controlleur);
        file_put_contents(MODELS_PATH . '/' . $page . '.model', $controlleur);
        print $git_modele;
        $git_view = shell_exec('cp ' . CONSOLE_PATH . '/skel/page.blade.php ' . VIEW_PATH . '/view/' . $page . '.blade.php');
        $controlleur = file_get_contents(VIEW_PATH . '/view/' . $page . '.blade.php');
        $controlleur = preg_replace('%PAGE%', $page, $controlleur);
        file_put_contents(VIEW_PATH . '/view/' . $page . '.blade.php', $controlleur);
        print $git_view;
    }

    /**
     * Supprimer en fonction du template  contenu dans le model
     */
    public static function remove()
    {
        print "removing page...\n\n";
        print "Quel est le nom de la page a supprimer? ";
        $handle = fopen("php://stdin", "r");
        $page = fgets($handle);
        $git_controlleur = system('rm -f ' . CONTROLLERS_PATH . '/' . $page . '.php', $git_controlleur_retval);
        print $git_controlleur_retval;
        $git_modele = system('rm -f ' . MODELS_PATH . '/' . $page . '.model', $git_modele_retval);
        print $git_modele_retval;
        $git_view = system('rm -f ' . VIEW_PATH . '/view/' . $page . '.blade.php', $git_view_retval);
        print $git_view_retval;
    }
}
