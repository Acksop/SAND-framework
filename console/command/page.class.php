<?php


class page
{

    static public function help(){
        print "explaination of the command\n\n";
    }

    static public function add(){
        print "adding page...\n\n";
        echo "Quel est le nom de la page a ajouter? ";
        $page = trim(fgets(STDIN));

        $git_controlleur = shell_exec('cp '.CONSOLE_PATH.'/skel/page.php '.CONTROLLERS_PATH.'/'.$page.'.php');
        $controlleur = file_get_contents(CONTROLLERS_PATH.'/'.$page.'.php');
        $controlleur = preg_replace('%PAGE%',$page,$controlleur);
        file_put_contents(CONTROLLERS_PATH.'/'.$page.'.php', $controlleur);
        echo $git_controlleur;
        $git_modele = shell_exec('cp '.CONSOLE_PATH.'/skel/page.model '.MODELS_PATH.'/'.$page.'.model');
        $controlleur = file_get_contents(MODELS_PATH.'/'.$page.'.model');
        $controlleur = preg_replace('%PAGE%',$page,$controlleur);
        file_put_contents(MODELS_PATH.'/'.$page.'.model', $controlleur);
        echo $git_modele;
        $git_view = shell_exec('cp '.CONSOLE_PATH.'/skel/page.blade.php '.VIEW_PATH.'/view/'.$page.'.blade.php');
        $controlleur = file_get_contents(VIEW_PATH.'/view/'.$page.'.blade.php');
        $controlleur = preg_replace('%PAGE%',$page,$controlleur);
        file_put_contents(VIEW_PATH.'/view/'.$page.'.blade.php', $controlleur);
        echo $git_view;
    }

    static public function remove(){
        print "removing page...\n\n";
        echo "Quel est le nom de la page a supprimer? ";
        $handle = fopen ("php://stdin","r");
        $page = fgets($handle);
        $git_controlleur = system('rm -f '.CONTROLLERS_PATH.'/'.$page.'.php', $git_controlleur_retval);
        echo $git_controlleur_retval;
        $git_modele = system('rm -f '.MODELS_PATH.'/'.$page.'.model', $git_modele_retval);
        echo $git_modele_retval;
        $git_view = system('rm -f '.VIEW_PATH.'/view/'.$page.'.blade.php', $git_view_retval);
        echo $git_view_retval;
    }

}