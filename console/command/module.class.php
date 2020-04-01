<?php


class module
{
    static public function help(){
        print "explaination of the command\n\n";
    }

    static public function add(){
        print "adding module...\n\n";
        print "Quel est le module a ajouter?\n1.Symfony\n2.Wordpress\n3.Prestashop\n4.PhpList ";
        $module = trim(fgets(STDIN));
        switch($module){
            case 1:
                print "Quel est le nom du module symfony à ajouter (default:symfony) ? ";
                $name = trim(fgets(STDIN));
                if($name !== '' && preg_match('#(.)+#',$name)){
                    module::addSymfony($name);
                }else{
                    module::addSymfony('symfony');
                }
                break;
            case 2:
                print "Quel est la version de Wordpress à ajouter (default:5.4) ? ";
                $version = trim(fgets(STDIN));
                if($version !== '' && preg_match('#(.)\.(.)#',$version)){
                    module::addWordpress($version);
                }else{
                    module::addWordpress('5.4');
                }
                break;
            case 3:
                print "Quel est la version de Prestashop à ajouter (default:1.7.5.0) ? ";
                $version = trim(fgets(STDIN));
                if($version !== '' && preg_match('#(.)\.(.)\.(.)\.(.)#',$version)){
                    module::addPrestashop($version);
                }else{
                    module::addPrestashop('1.7.6.4');
                }
                break;
            case 4:
                print "Quel est la version de PhpList à ajouter (default:3.4.2) ? ";
                $version = trim(fgets(STDIN));
                if($version !== '' && preg_match('#(.)\.(.)\.(.)\.(.)#',$version)){
                    module::addPhplist($version);
                }else{
                    module::addPhplist('3.4.2');
                }
                break;
            default:
        }
    }

    static public function remove(){
        print "removing module...\n\n";
        print "Quel est le module a supprimer?\n1.Symfony\n2.Wordpress\n3.Prestashop\n4.PhpList ";
        $module = trim(fgets(STDIN));
        switch($module){
            case 1:
                print "Quel est le nom du module symfony à supprimer (default:symfony) ? ";
                $name = trim(fgets(STDIN));
                if($name !== '' && preg_match('#(.)+#',$name)){
                    module::removeSymfony($name);
                }else{
                    module::removeSymfony('symfony');
                }
                break;
            case 2:
                module::removeWordpress();
                break;
            case 3:
                module::removePrestashop();
                break;
            case 4:
                module::removePhplist();
                break;
            default:
        }
    }

    static private function addSymfony($name = 'symfony'){

        $git_clone = shell_exec('cd '.MODULES_PATH.' && composer create-project symfony/website-skeleton '.$name);
        print $git_clone;
        $git_controlleur = shell_exec('cp '.CONSOLE_PATH.'/skel/module.php '.CONTROLLERS_PATH.'/'.$name.'.php');
        $controlleur = file_get_contents(CONTROLLERS_PATH.'/'.$name.'.php');
        $controlleur = preg_replace('%MODULE%',$name,$controlleur);
        file_put_contents(CONTROLLERS_PATH.'/'.$name.'.php', $controlleur);
        print $git_controlleur;
        $git_modele = shell_exec('cp '.CONSOLE_PATH.'/skel/module.model '.MODELS_PATH.'/'.$name.'.model');
        $modele = file_get_contents(MODELS_PATH.'/'.$name.'.model');
        $modele = preg_replace('%MODULE%',$name,$modele);
        file_put_contents(MODELS_PATH.'/'.$name.'.model', $modele);
        print $git_modele;
        $git_view = shell_exec('cp '.CONSOLE_PATH.'/skel/module.blade.php '.VIEW_PATH.'/view/'.$name.'.blade.php');
        $vue = file_get_contents(VIEW_PATH.'/view/'.$name.'.blade.php');
        $vue = preg_replace('%MODULE%','symfony',$vue);
        file_put_contents(VIEW_PATH.'/view/'.$name.'.blade.php', $vue);
        print $git_view;

        //stabilize symfony application
        include dirname(__FILE__).DIRECTORY_SEPARATOR.'symfony.class.php';
        symfony::stabilize();

        $symfony_root = shell_exec('cp -r '.CONSOLE_PATH.'/skel/symfony-app '.MODULES_PATH.'/'.$name);
        $symfony_composer = shell_exec('cd '.MODULES_PATH.'/'.$name.' && composer update');

        print 'n\'oublier pas d\'ajouter:\n'
            .'\n'.$name.' : Application permettant d\'intégrer un module avec symfony'
            .'\n au fichier /application/modules/setup/registre.model\n'
            .'\n et de créer la base de données!';

    }
    static public function removeSymfony($name = 'symfony'){

        $git_clone = system('rm -Rf '.MODULES_PATH.'/'.$name, $git_clone_retval);
        print $git_clone_retval;
        $git_ln_1 = system('rm -Rf '.PUBLIC_PATH.'/'.$name, $git_ln_1_retval);
        print $git_ln_1_retval;
        $git_controlleur = system('rm -f '.CONTROLLERS_PATH.'/'.$name.'.php', $git_controlleur_retval);
        print $git_controlleur_retval;
        $git_modele = system('rm -f '.MODELS_PATH.'/'.$name.'.model', $git_modele_retval);
        print $git_modele_retval;
        $git_view = system('rm -f '.VIEW_PATH.'/view/'.$name.'.blade.php', $git_view_retval);
        print $git_view_retval;
    }

    static public function addWordpress($version = '5.4'){

        $git_clone = shell_exec('cd '.MODULES_PATH.' && git clone https://github.com/WordPress/WordPress.git wordpress');
        print $git_clone;
        $git_fetch = shell_exec('cd '.MODULES_PATH.'/wordpress && git fetch --all --tags');
        print $git_fetch;
        $git_checkout = shell_exec('cd '.MODULES_PATH.'/wordpress && git checkout tags/'.$version.' -b actual-branch');
        print $git_checkout;
        $git_chmod = shell_exec('sudo chmod 775 '.MODULES_PATH.'/wordpress');
        print $git_chmod;
        $git_chown = shell_exec('sudo chown acksop:www-data '.MODULES_PATH.'/wordpress -R');
        print $git_chown;
        $git_ln_1 = shell_exec('cd '.PUBLIC_PATH.' && ln -s ../application/modules/wordpress/ wordpress');
        print $git_ln_1;
        $git_controlleur = shell_exec('cp '.CONSOLE_PATH.'/skel/module.php '.CONTROLLERS_PATH.'/wordpress.php');
        $controlleur = file_get_contents(CONTROLLERS_PATH.'/wordpress.php');
        $controlleur = preg_replace('%MODULE%','wordpress',$controlleur);
        file_put_contents(CONTROLLERS_PATH.'/wordpress.php', $controlleur);
        print $git_controlleur;
        $git_modele = shell_exec('cp '.CONSOLE_PATH.'/skel/module.model '.MODELS_PATH.'/wordpress.model');
        $modele = file_get_contents(MODELS_PATH.'/wordpress.model');
        $modele = preg_replace('%MODULE%','wordpress',$modele);
        file_put_contents(MODELS_PATH.'/wordpress.model', $modele);
        print $git_modele;
        $git_view = shell_exec('cp '.CONSOLE_PATH.'/skel/module.blade.php '.VIEW_PATH.'/view/wordpress.blade.php');
        $vue = file_get_contents(VIEW_PATH.'/view/wordpress.blade.php');
        $vue = preg_replace('%MODULE%','wordpress',$vue);
        file_put_contents(VIEW_PATH.'/view/wordpress.blade.php', $vue);
        print $git_view;

        print 'n\'oublier pas d\'ajouter:\n'
            .'\nwordpress : Application permettant de générer un blog wordpress'
            .'\n au fichier /application/modules/setup/registre.model\n'
            .'\n et de créer la base de données!';

    }
    static public function removeWordpress(){

        $git_clone = system('rm -Rf '.MODULES_PATH.'/wordpress', $git_clone_retval);
        print $git_clone_retval;
        $git_ln_1 = system('rm -Rf '.PUBLIC_PATH.'/wordpress', $git_ln_1_retval);
        print $git_ln_1_retval;
        $git_controlleur = system('rm -f '.CONTROLLERS_PATH.'/wordpress.php', $git_controlleur_retval);
        print $git_controlleur_retval;
        $git_modele = system('rm -f '.MODELS_PATH.'/wordpress.model', $git_modele_retval);
        print $git_modele_retval;
        $git_view = system('rm -f '.VIEW_PATH.'/view/wordpress.blade.php', $git_view_retval);
        print $git_view_retval;
    }

    static public function addPrestashop($version = '1.7.6.4'){

        $git_clone = shell_exec('cd '.MODULES_PATH.' && git clone https://github.com/PrestaShop/PrestaShop.git prestashop');
        print $git_clone;
        $git_fetch = shell_exec('cd '.MODULES_PATH.'/prestashop && git fetch --all --tags');
        print $git_fetch;
        $git_checkout = shell_exec('cd '.MODULES_PATH.'/prestashop && git checkout tags/'.$version.' -b actual-branch ');
        print $git_checkout;
        $composer_update = shell_exec('cd '.MODULES_PATH.'/prestashop && composer update');
        print $composer_update;
        $git_ln_1 = shell_exec('cd '.PUBLIC_PATH.' && ln -s ../application/modules/prestashop/ prestashop');
        print $git_ln_1;
        $git_chmod = shell_exec('sudo chmod 775 '.MODULES_PATH.'/prestashop -R');
        print $git_chmod;
        $git_chown = shell_exec('sudo chown acksop:www-data '.MODULES_PATH.'/prestashop -R');
        print $git_chown;
        $git_ln_2 = shell_exec('cd '.MODULES_PATH.'/prestashop && ln -s ../prestashop/ prestashop');
        print $git_ln_2;
        $git_controlleur = shell_exec('cp '.CONSOLE_PATH.'/skel/module.php '.CONTROLLERS_PATH.'/prestashop.php');
        $controlleur = file_get_contents(CONTROLLERS_PATH.'/prestashop.php');
        $controlleur = preg_replace('%MODULE%','prestashop',$controlleur);
        file_put_contents(CONTROLLERS_PATH.'/prestashop.php', $controlleur);
        print $git_controlleur;
        $git_modele = shell_exec('cp '.CONSOLE_PATH.'/skel/module.model '.MODELS_PATH.'/prestashop.model');
        $controlleur = file_get_contents(MODELS_PATH.'/prestashop.model');
        $controlleur = preg_replace('%MODULE%','prestashop',$controlleur);
        file_put_contents(MODELS_PATH.'/prestashop.model', $controlleur);
        print $git_modele;
        $git_view = shell_exec('cp '.CONSOLE_PATH.'/skel/module.blade.php '.VIEW_PATH.'/view/prestashop.blade.php');
        $controlleur = file_get_contents(VIEW_PATH.'/view/prestashop.blade.php');
        $controlleur = preg_replace('%MODULE%','prestashop',$controlleur);
        file_put_contents(VIEW_PATH.'/view/prestashop.blade.php', $controlleur);
        print $git_view;

        print 'n\'oublier pas d\'ajouter:\n'
            .'\nprestashop : Application permettant de générer une site e-commerce prestashop'
            .'\n au fichier /application/modules/setup/registre.model\n'
            .'\n et de créer la base de données!';
    }
    static public function removePrestashop(){

        $git_clone = system('rm -Rf '.MODULES_PATH.'/prestashop', $git_clone_retval);
        print $git_clone_retval;
        $git_ln_1 = system('rm -Rf '.PUBLIC_PATH.'/prestashop', $git_ln_1_retval);
        print $git_ln_1_retval;
        $git_controlleur = system('rm -f '.CONTROLLERS_PATH.'/prestashop.php', $git_controlleur_retval);
        print $git_controlleur_retval;
        $git_modele = system('rm -f '.MODELS_PATH.'/prestashop.model', $git_modele_retval);
        print $git_modele_retval;
        $git_view = system('rm -f '.VIEW_PATH.'/view/prestashop.blade.php', $git_view_retval);
        print $git_view_retval;
    }

    static public function addPhplist($version = '3.4.2'){

        $git_clone = shell_exec('cd '.MODULES_PATH.' && git clone https://github.com/phpList/phplist3.git phplist');
        print $git_clone;
        $git_fetch = shell_exec('cd '.MODULES_PATH.'/phplist && git fetch --all --tags');
        print $git_fetch;
        $git_checkout = shell_exec('cd '.MODULES_PATH.'/phplist && git checkout tags/'.$version.' -b actual-branch ');
        print $git_checkout;
        $composer_update = shell_exec('cd '.MODULES_PATH.'/phplist && composer update');
        print $composer_update;
        $git_ln_1 = shell_exec('cd '.PUBLIC_PATH.' && ln -s ../application/modules/phplist/public_html/lists phplist');
        print $git_ln_1;
        $git_chmod = shell_exec('sudo chmod 777 '.MODULES_PATH.'/phplist -R');
        print $git_chmod;
        $git_chown = shell_exec('sudo chown acksop:www-data '.MODULES_PATH.'/phplist -R');
        print $git_chown;
        $git_controlleur = shell_exec('cp '.CONSOLE_PATH.'/skel/module.php '.CONTROLLERS_PATH.'/phplist.php');
        $controlleur = file_get_contents(CONTROLLERS_PATH.'/phplist.php');
        $controlleur = preg_replace('%MODULE%','phplist',$controlleur);
        file_put_contents(CONTROLLERS_PATH.'/phplist.php', $controlleur);
        print $git_controlleur;
        $git_modele = shell_exec('cp '.CONSOLE_PATH.'/skel/module.model '.MODELS_PATH.'/phplist.model');
        $controlleur = file_get_contents(MODELS_PATH.'/phplist.model');
        $controlleur = preg_replace('%MODULE%','phplist',$controlleur);
        file_put_contents(MODELS_PATH.'/phplist.model', $controlleur);
        print $git_modele;
        $git_view = shell_exec('cp '.CONSOLE_PATH.'/skel/module.blade.php '.VIEW_PATH.'/view/phplist.blade.php');
        $controlleur = file_get_contents(VIEW_PATH.'/view/phplist.blade.php');
        $controlleur = preg_replace('%MODULE%','phplist',$controlleur);
        file_put_contents(VIEW_PATH.'/view/phplist.blade.php', $controlleur);
        print $git_view;

        print 'n\'oublier pas d\'ajouter:\n'
            .'\nphplist : Application permettant de générer une newsletter phplist'
            .'\n au fichier /application/modules/setup/registre.model\n'
            .'\n et de créer la base de données!';
    }
    static public function removePhplist(){

        $git_clone = system('rm -Rf '.MODULES_PATH.'/phplist', $git_clone_retval);
        print $git_clone_retval;
        $git_ln_1 = system('rm -Rf '.PUBLIC_PATH.'/phplist', $git_ln_1_retval);
        print $git_ln_1_retval;
        $git_controlleur = system('rm -f '.CONTROLLERS_PATH.'/phplist.php', $git_controlleur_retval);
        print $git_controlleur_retval;
        $git_modele = system('rm -f '.MODELS_PATH.'/phplist.model', $git_modele_retval);
        print $git_modele_retval;
        $git_view = system('rm -f '.VIEW_PATH.'/view/phplist.blade.php', $git_view_retval);
        print $git_view_retval;
    }
}