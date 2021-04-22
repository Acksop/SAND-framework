<?php

namespace MVC\Command\Sand;
/**
 * Class Page
 * Commande Système du Framework permettant de gérer les pages
 * @package MVC\Command\Sand
 */
class Page
{
    /**
     * Méthode permettant d'afficher l'aide de la commande
     */
    public static function help()
    {
        print "Cette commande permet de manipuler les pages du framework\n\n";
        print "Elle peut accepter les attributs suivants\n";
        print "\t- add : pour ajouter une page\n";
        print "\t- remove : pour supprimer une page\n";
        print "\t- duplicate : pour dupliquer une page\n";
        print "\t- rename : pour renommer une page\n";
    }

    public static function add()
    {
        print "adding page...\n\n";
        print "Quel est le nom de la page a ajouter? ";
        $page = trim(fgets(STDIN));

        print "Es-ce un template blade?(Y,N) Par defaut:Y ";
        $template = trim(fgets(STDIN));
        $vue = "";
        if ($template == '' || $template == 'Y') {
            $template = 'blade';

            print "Es-ce une SPA vue.js? (Y,N) Par defaut:N ";
            $vue = trim(fgets(STDIN));
            if ($vue == '') {
                $vue = 'N';
            } else if ($vue !== 'Y') {
                $vue = 'N';
            }

        } else if ($template !== 'Y') {
            $template = 'twig';
        }


        $shell_controlleur = shell_exec('cp ' . CONSOLE_PATH . '/skel/page.php ' . CONTROLLERS_PATH . '/' . $page . '.php');
        $controlleur = file_get_contents(CONTROLLERS_PATH . '/' . $page . '.php');
        $controlleur = preg_replace('/%%PAGE%%/', $page, $controlleur);
        file_put_contents(CONTROLLERS_PATH . '/' . $page . '.php', $controlleur);
        print $shell_controlleur;

        $shell_modele = shell_exec('cp ' . CONSOLE_PATH . '/skel/page.model ' . MODELS_PATH . '/' . $page . '.model');
        $modele = file_get_contents(MODELS_PATH . '/' . $page . '.model');
        $modele = preg_replace('/%%PAGE%%/', $page, $modele);
        $modele = preg_replace('/%%ENGINE%%/', $template, $modele);
        file_put_contents(MODELS_PATH . '/' . $page . '.model', $modele);
        print $shell_modele;

        if ($template == 'blade'){
            if ($vue == 'N') {
                $shell_view = shell_exec('cp ' . CONSOLE_PATH . '/skel/page.blade.php ' . VIEW_PATH . '/view/' . $page . '.blade.php');
            } else {
                $shell_view = shell_exec('cp ' . CONSOLE_PATH . '/skel/page-vuejs.blade.php ' . VIEW_PATH . '/view/' . $page . '.blade.php');
            }
            $view = file_get_contents(VIEW_PATH . '/view/' . $page . '.blade.php');
            $view = preg_replace('/%%PAGE%%/', $page, $view);
            file_put_contents(VIEW_PATH.'/view/'.$page.'.blade.php', $view);
        }else{
            $shell_view = shell_exec('cp ' . CONSOLE_PATH . '/skel/page.html.twig ' . VIEW_PATH . '/view/' . $page . '.html.twig');
            $view = file_get_contents(VIEW_PATH . '/view/' . $page . '.html.twig');
            $view = preg_replace('/%%PAGE%%/', $page, $view);
            file_put_contents(VIEW_PATH.'/view/'.$page.'.html.twig', $view);
        }

        print $shell_view;
    }
    /**
     * Dupliquer une page
     */
    public static function duplicate()
    {
        print "duplicating page...\n\n";
        print "Quel est le nom de la page a dupliquer? ";
        $page = trim(fgets(STDIN));
        print "Quel est le nouveau nom de la page? ";
        $newpage = trim(fgets(STDIN));

        print "Es-ce un template blade?(Y,N) Par defaut:Y ";
        $template = trim(fgets(STDIN));
        if ($template == '' || $template == 'Y') {
            $template = 'blade';
        } else if ($template !== 'Y') {
            $template = 'twig';
        }

        $shell_controlleur = shell_exec('cp '.CONTROLLERS_PATH.'/'.$page.'.php '.CONTROLLERS_PATH.'/'.$newpage.'.php');
        print $shell_controlleur;
        $shell_modele = shell_exec('cp '.MODELS_PATH.'/'.$page.'.model '.MODELS_PATH.'/'.$newpage.'.model');
        $modele = file_get_contents(MODELS_PATH.'/'.$newpage.'.model');
        $modele = preg_replace('/name : '.$page.'/', 'name : '.$newpage, $modele);
        file_put_contents(MODELS_PATH.'/'.$newpage.'.model', $modele);
        print $shell_modele;
        if ($template == 'blade') {
            $shell_view = shell_exec('cp ' . VIEW_PATH . '/view/' . $page . '.blade.php ' . VIEW_PATH . '/view/' . $newpage . '.blade.php');
        }else{
            $shell_view = shell_exec('cp ' . VIEW_PATH . '/view/' . $page . '.html.twig ' . VIEW_PATH . '/view/' . $newpage . '.html.twig');
        }
        print $shell_view;
    }
    /**
     * Dupliquer une page
     */
    public static function rename()
    {
        print "renaming page...\n\n";
        print "Quel est le nom de la page a renommer? ";
        $page = trim(fgets(STDIN));
        print "Quel est le nouveau nom de la page? ";
        $newpage = trim(fgets(STDIN));

        print "Es-ce un template blade?(Y,N) Par defaut:Y ";
        $template = trim(fgets(STDIN));
        if ($template == '' || $template == 'Y') {
            $template = 'blade';
        } else if ($template !== 'Y') {
            $template = 'twig';
        }

        $shell_controlleur = shell_exec('mv '.CONTROLLERS_PATH.'/'.$page.'.php '.CONTROLLERS_PATH.'/'.$newpage.'.php');
        print $shell_controlleur;
        $shell_modele = shell_exec('mv '.MODELS_PATH.'/'.$page.'.model '.MODELS_PATH.'/'.$newpage.'.model');
        $modele = file_get_contents(MODELS_PATH.'/'.$newpage.'.model');
        $modele = preg_replace('/name : '.$page.'/', 'name : '.$newpage, $modele);
        file_put_contents(MODELS_PATH.'/'.$newpage.'.model', $modele);
        print $shell_modele;
        if ($template == 'blade') {
            $shell_view = shell_exec('mv '.VIEW_PATH.'/view/'.$page.'.blade.php '.VIEW_PATH.'/view/'.$newpage.'.blade.php');
        }else {
            $shell_view = shell_exec('mv '.VIEW_PATH.'/view/'.$page.'.html.twig '.VIEW_PATH.'/view/'.$newpage.'.html.twig');
        }
        print $shell_view;
    }

    /**
     * Supprimer en fonction du template  contenu dans le model
     */
    public static function remove()
    {
        print "removing page...\n\n";
        print "Quel est le nom de la page a supprimer? ";
        $page = trim(fgets(STDIN));

        print "Es-ce un template blade?(Y,N) Par defaut:Y ";
        $template = trim(fgets(STDIN));
        if ($template == '' || $template == 'Y') {
            $template = 'blade';
        } else if ($template !== 'Y') {
            $template = 'twig';
        }
        $shell_controlleur = shell_exec('rm -f '.CONTROLLERS_PATH.'/'.$page.'.php');
        print $shell_controlleur;
        $shell_modele = shell_exec('rm -f '.MODELS_PATH.'/'.$page.'.model');
        print $shell_modele;
        if ($template == 'blade') {
            $shell_view = shell_exec('rm -f '.VIEW_PATH.'/view/'.$page.'.blade.php');
        }else {
            $shell_view = shell_exec('rm -f '.VIEW_PATH.'/view/'.$page.'.html.twig');
        }
        print $shell_view;
    }
}
