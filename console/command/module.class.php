<?php


class module
{
    static public function help(){
        print "explaination of the command\n\n";
    }

    static public function add(){
        print "adding module...\n\n";
    }

    static public function remove(){
        print "removing module...\n\n";
    }

    static private function addSymfony(){
        /*
         * composer create-project symfony/website-skeleton my_module_name
         *
         * add symbolic links (not necessary, it comes with the way you progam on sand-module)
         *
         * add controlleur method
         * add model file
         * add blade view
         */
    }

    static public function addWordpress(){

        $git_clone = shell_exec('cd '.MODULES_PATH.' && git clone https://github.com/WordPress/WordPress.git wordpress');
        echo $git_clone;
        $git_fetch = shell_exec('cd '.MODULES_PATH.'/wordpress && git fetch --all --tags');
        echo $git_fetch;
        $git_checkout = shell_exec('cd '.MODULES_PATH.'/wordpress && git checkout tags/5.4 -b actual-branch');
        echo $git_checkout;
        $git_chmod = shell_exec('sudo chmod 775 '.MODULES_PATH.'/wordpress');
        echo $git_chmod;
        $git_chown = shell_exec('sudo chown acksop:www-data '.MODULES_PATH.'/wordpress -R');
        echo $git_chown;
        $git_ln_1 = shell_exec('cd '.PUBLIC_PATH.' && ln -s ../application/modules/wordpress/ wordpress');
        echo $git_ln_1;
        $git_controlleur = shell_exec('cp '.CONSOLE_PATH.'/skel/module.php '.CONTROLLERS_PATH.'/wordpress.php');
        $controlleur = file_get_contents(CONTROLLERS_PATH.'/wordpress.php');
        $controlleur = preg_replace('%MODULE%','wordpress',$controlleur);
        file_put_contents(CONTROLLERS_PATH.'/wordpress.php', $controlleur);
        echo $git_controlleur;
        $git_modele = shell_exec('cp '.CONSOLE_PATH.'/skel/module.model '.MODELS_PATH.'/wordpress.model');
        $controlleur = file_get_contents(MODELS_PATH.'/wordpress.model');
        $controlleur = preg_replace('%MODULE%','wordpress',$controlleur);
        file_put_contents(MODELS_PATH.'/wordpress.model', $controlleur);
        echo $git_modele;
        $git_view = shell_exec('cp '.CONSOLE_PATH.'/skel/module.blade.php '.VIEW_PATH.'/view/wordpress.blade.php');
        $controlleur = file_get_contents(VIEW_PATH.'/view/wordpress.blade.php');
        $controlleur = preg_replace('%MODULE%','wordpress',$controlleur);
        file_put_contents(VIEW_PATH.'/view/wordpress.blade.php', $controlleur);
        echo $git_view;

    }
    static public function removeWordpress(){

        $git_clone = system('rm -Rf '.MODULES_PATH.'/wordpress', $git_clone_retval);
        echo $git_clone_retval;
        $git_ln_1 = system('rm -Rf '.PUBLIC_PATH.'/wordpress', $git_ln_1_retval);
        echo $git_ln_1_retval;
        $git_controlleur = system('rm -f '.CONTROLLERS_PATH.'/wordpress.php', $git_controlleur_retval);
        echo $git_controlleur_retval;
        $git_modele = system('rm -f '.MODELS_PATH.'/wordpress.model', $git_modele_retval);
        echo $git_modele_retval;
        $git_view = system('rm -f '.VIEW_PATH.'/view/wordpress.blade.php', $git_view_retval);
        echo $git_view_retval;
    }

    static public function addPrestashop(){

        $git_clone = shell_exec('cd '.MODULES_PATH.' && git clone https://github.com/PrestaShop/PrestaShop.git prestashop');
        echo $git_clone;
        $git_fetch = shell_exec('cd '.MODULES_PATH.'/prestashop && git fetch --all --tags');
        echo $git_fetch;
        $git_checkout = shell_exec('cd '.MODULES_PATH.'/prestashop && git checkout tags/1.7.5.0 -b actual-branch ');
        echo $git_checkout;
        $composer_update = shell_exec('cd '.MODULES_PATH.'/prestashop && composer update');
        echo $composer_update;
        $git_ln_1 = shell_exec('cd '.PUBLIC_PATH.' && ln -s ../application/modules/prestashop/ prestashop');
        echo $git_ln_1;
        $git_chmod = shell_exec('sudo chmod 775 '.MODULES_PATH.'/prestashop -R');
        echo $git_chmod;
        $git_chown = shell_exec('sudo chown acksop:www-data '.MODULES_PATH.'/prestashop -R');
        echo $git_chown;
        $git_ln_2 = shell_exec('cd '.MODULES_PATH.'/prestashop && ln -s ../prestashop/ prestashop');
        echo $git_ln_2;
        $git_controlleur = shell_exec('cp '.CONSOLE_PATH.'/skel/module.php '.CONTROLLERS_PATH.'/prestashop.php');
        $controlleur = file_get_contents(CONTROLLERS_PATH.'/prestashop.php');
        $controlleur = preg_replace('%MODULE%','prestashop',$controlleur);
        file_put_contents(CONTROLLERS_PATH.'/prestashop.php', $controlleur);
        echo $git_controlleur;
        $git_modele = shell_exec('cp '.CONSOLE_PATH.'/skel/module.model '.MODELS_PATH.'/prestashop.model');
        $controlleur = file_get_contents(MODELS_PATH.'/prestashop.model');
        $controlleur = preg_replace('%MODULE%','prestashop',$controlleur);
        file_put_contents(MODELS_PATH.'/prestashop.model', $controlleur);
        echo $git_modele;
        $git_view = shell_exec('cp '.CONSOLE_PATH.'/skel/module.blade.php '.VIEW_PATH.'/view/prestashop.blade.php');
        $controlleur = file_get_contents(VIEW_PATH.'/view/prestashop.blade.php');
        $controlleur = preg_replace('%MODULE%','prestashop',$controlleur);
        file_put_contents(VIEW_PATH.'/view/prestashop.blade.php', $controlleur);
        echo $git_view;
    }
    static public function removePrestashop(){

        $git_clone = system('rm -Rf '.MODULES_PATH.'/prestashop', $git_clone_retval);
        echo $git_clone_retval;
        $git_ln_1 = system('rm -Rf '.PUBLIC_PATH.'/prestashop', $git_ln_1_retval);
        echo $git_ln_1_retval;
        $git_controlleur = system('rm -f '.CONTROLLERS_PATH.'/prestashop.php', $git_controlleur_retval);
        echo $git_controlleur_retval;
        $git_modele = system('rm -f '.MODELS_PATH.'/prestashop.model', $git_modele_retval);
        echo $git_modele_retval;
        $git_view = system('rm -f '.VIEW_PATH.'/view/prestashop.blade.php', $git_view_retval);
        echo $git_view_retval;
    }

    static public function addPhplist(){

        $git_clone = shell_exec('cd '.MODULES_PATH.' && git clone https://github.com/phpList/phplist3.git phplist');
        echo $git_clone;
        $git_fetch = shell_exec('cd '.MODULES_PATH.'/phplist && git fetch --all --tags');
        echo $git_fetch;
        $git_checkout = shell_exec('cd '.MODULES_PATH.'/phplist && git checkout tags/3.4.2 -b actual-branch ');
        echo $git_checkout;
        $composer_update = shell_exec('cd '.MODULES_PATH.'/phplist && composer update');
        echo $composer_update;
        $git_ln_1 = shell_exec('cd '.PUBLIC_PATH.' && ln -s ../application/modules/phplist/public_html/lists phplist');
        echo $git_ln_1;
        $git_chmod = shell_exec('sudo chmod 777 '.MODULES_PATH.'/phplist -R');
        echo $git_chmod;
        $git_chown = shell_exec('sudo chown acksop:www-data '.MODULES_PATH.'/phplist -R');
        echo $git_chown;
        $git_controlleur = shell_exec('cp '.CONSOLE_PATH.'/skel/module.php '.CONTROLLERS_PATH.'/phplist.php');
        $controlleur = file_get_contents(CONTROLLERS_PATH.'/phplist.php');
        $controlleur = preg_replace('%MODULE%','phplist',$controlleur);
        file_put_contents(CONTROLLERS_PATH.'/phplist.php', $controlleur);
        echo $git_controlleur;
        $git_modele = shell_exec('cp '.CONSOLE_PATH.'/skel/module.model '.MODELS_PATH.'/phplist.model');
        $controlleur = file_get_contents(MODELS_PATH.'/phplist.model');
        $controlleur = preg_replace('%MODULE%','phplist',$controlleur);
        file_put_contents(MODELS_PATH.'/phplist.model', $controlleur);
        echo $git_modele;
        $git_view = shell_exec('cp '.CONSOLE_PATH.'/skel/module.blade.php '.VIEW_PATH.'/view/phplist.blade.php');
        $controlleur = file_get_contents(VIEW_PATH.'/view/phplist.blade.php');
        $controlleur = preg_replace('%MODULE%','phplist',$controlleur);
        file_put_contents(VIEW_PATH.'/view/phplist.blade.php', $controlleur);
        echo $git_view;
    }
    static public function removePhplist(){

        $git_clone = system('rm -Rf '.MODULES_PATH.'/phplist', $git_clone_retval);
        echo $git_clone_retval;
        $git_ln_1 = system('rm -Rf '.PUBLIC_PATH.'/phplist', $git_ln_1_retval);
        echo $git_ln_1_retval;
        $git_controlleur = system('rm -f '.CONTROLLERS_PATH.'/phplist.php', $git_controlleur_retval);
        echo $git_controlleur_retval;
        $git_modele = system('rm -f '.MODELS_PATH.'/phplist.model', $git_modele_retval);
        echo $git_modele_retval;
        $git_view = system('rm -f '.VIEW_PATH.'/view/phplist.blade.php', $git_view_retval);
        echo $git_view_retval;
    }
}