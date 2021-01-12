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

        /*print "Es-ce un template blade?(Y,N) Par defaut:Y ";
        $template = trim(fgets(STDIN));
        if($template == ''){
            $template = 'Y';
        }else if($template !== 'Y'){
            $template = 'N';
        }*/

        print "Es-ce une SPA vue.js? (Y,N) Par defaut:N ";
        $vue = trim(fgets(STDIN));
        if($vue == ''){
            $vue = 'N';
        }else if($vue !== 'Y'){
            $vue = 'N';
        }

        $shell_controlleur = shell_exec('cp '.CONSOLE_PATH.'/skel/page.php '.CONTROLLERS_PATH.'/'.$page.'.php');
        $controlleur = file_get_contents(CONTROLLERS_PATH.'/'.$page.'.php');
        $controlleur = preg_replace('%PAGE%', $page, $controlleur);
        file_put_contents(CONTROLLERS_PATH.'/'.$page.'.php', $controlleur);
        print $shell_controlleur;

        $shell_modele = shell_exec('cp '.CONSOLE_PATH.'/skel/page.model '.MODELS_PATH.'/'.$page.'.model');
        $modele = file_get_contents(MODELS_PATH.'/'.$page.'.model');
        $modele = preg_replace('%PAGE%', $page, $modele);
        file_put_contents(MODELS_PATH.'/'.$page.'.model', $modele);
        print $shell_modele;

        if($vue == 'Y'){
            $shell_view = shell_exec('cp '.CONSOLE_PATH.'/skel/page.blade.php '.VIEW_PATH.'/view/'.$page.'.blade.php');
        }else{
            $shell_view = shell_exec('cp '.CONSOLE_PATH.'/skel/page-vuejs.blade.php '.VIEW_PATH.'/view/'.$page.'.blade.php');
        }
        $view = file_get_contents(VIEW_PATH.'/view/'.$page.'.blade.php');
        $view = preg_replace('%PAGE%', $page, $view);
        file_put_contents(VIEW_PATH.'/view/'.$page.'.blade.php', $view);
        print $shell_view;
    }
    /**
     * Dupliquer une page
     */
    public static function duplicate()
    {
        print "duplicating page...\n\n";
        print "Quel est le nom de la page a dupliquer? ";
        $handle = fopen("php://stdin", "r");
        $page = fgets($handle);
        print "Quel est le nouveau nom de la page? ";
        $handle = fopen("php://stdin", "r");
        $newpage = fgets($handle);

        $controlleur = shell_exec('cp '.CONTROLLERS_PATH.'/'.$page.'.php '.CONTROLLERS_PATH.'/'.$newpage.'.php');
        print $controlleur;
        $modele = shell_exec('cp '.MODELS_PATH.'/'.$page.'.model '.MODELS_PATH.'/'.$newpage.'.model');
        $modele = file_get_contents(MODELS_PATH.'/'.$page.'.model');
        $modele = preg_replace('name : '.$page, 'name : '.$newpage, $modele);
        file_put_contents(MODELS_PATH.'/'.$page.'.model', $modele);
        print $modele;
        $view = shell_exec('cp '.VIEW_PATH.'/view/'.$page.'.blade.php '.VIEW_PATH.'/view/'.$newpage.'.blade.php');
        print $view;
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
        $git_controlleur = system('rm -f '.CONTROLLERS_PATH.'/'.$page.'.php', $git_controlleur_retval);
        print $git_controlleur_retval;
        $git_modele = system('rm -f '.MODELS_PATH.'/'.$page.'.model', $git_modele_retval);
        print $git_modele_retval;
        $git_view = system('rm -f '.VIEW_PATH.'/view/'.$page.'.blade.php', $git_view_retval);
        print $git_view_retval;
    }
}
