<?php

namespace MVC\Command;

class Module
{
    public static function help()
    {
        print "explaination of the command\n\n";
    }

    public static function add()
    {
        print "adding module...\n\n";
        print "Quel est le module a ajouter ?\n1.Symfony\n2.Wordpress\n3.Prestashop\n4.PhpList\n5.Wanewsletter\n6.PHPmyNewletter\n>";
        $module = trim(fgets(STDIN));
        switch ($module) {
            case 1:
                print "Quel est le nom du module symfony à ajouter (default : symfony) ? ";
                $name = trim(fgets(STDIN));
                if ($name !== '' && preg_match('#(.)+#', $name)) {
                    Module::addSymfony($name);
                } else {
                    Module::addSymfony('symfony');
                }
                break;
            case 2:
                print "Quel est la version de Wordpress à ajouter (default : 5.4) ? ";
                $version = trim(fgets(STDIN));
                if ($version !== '' && preg_match('#(.)\.(.)#', $version)) {
                    Module::addWordpress($version);
                } else {
                    Module::addWordpress('5.4');
                }
                break;
            case 3:
                print "Quel est la version de Prestashop à ajouter (default : 1.7.5.0) ? ";
                $version = trim(fgets(STDIN));
                if ($version !== '' && preg_match('#(.)\.(.)\.(.)\.(.)#', $version)) {
                    Module::addPrestashop($version);
                } else {
                    Module::addPrestashop('1.7.5.0');
                }
                break;
            case 4:
                print "Quel est la version de PhpList à ajouter (default : 3.5.2) ? ";
                $version = trim(fgets(STDIN));
                if ($version !== '' && preg_match('#(.)\.(.)\.(.)#', $version)) {
                    Module::addPhplist($version);
                } else {
                    Module::addPhplist('3.5.2');
                }
                break;
            case 5:
                print "Quel est la version de Wanewletter à ajouter (default : release-3.0.1) ? ";
                $version = trim(fgets(STDIN));
                if ($version !== '' && preg_match('#(.)\.(.)\.(.)#', $version)) {
                    Module::addWanewsletter($version);
                } else {
                    Module::addWanewsletter('release-3.0.1');
                }
                break;
            case 6:
                print "Quel est la version de PHPmyNewletter à ajouter (default : v2.0.5) ? ";
                $version = trim(fgets(STDIN));
                if ($version !== '' && preg_match('#(.)\.(.)\.(.)#', $version)) {
                    Module::addPHPMyNewsletter($version);
                } else {
                    Module::addPHPMyNewsletter('v2.0.5');
                }
                break;
            default:
        }
    }

    public static function remove()
    {
        print "removing module...\n\n";
        print "Quel est le module a supprimer?\n1.Symfony\n2.Wordpress\n3.Prestashop\n4.PhpList\n5.Wanewsletter\n6.PHPmyNewletter\n>";
        $module = trim(fgets(STDIN));
        switch ($module) {
            case 1:
                print "Quel est le nom du module symfony à supprimer (default : symfony) ? ";
                $name = trim(fgets(STDIN));
                if ($name !== '' && preg_match('#(.)+#', $name)) {
                    Module::removeSymfony($name);
                } else {
                    Module::removeSymfony('symfony');
                }
                break;
            case 2:
                Module::removeWordpress();
                break;
            case 3:
                Module::removePrestashop();
                break;
            case 4:
                Module::removePhplist();
                break;
            case 5:
                Module::removeWanewsletter();
                break;
            case 6:
                Module::removePHPMyNewsletter();
                break;
            default:
        }
    }

    private static function addSymfony($name = 'symfony')
    {
        $git_clone = shell_exec('cd '.MODULES_PATH.' && composer create-project symfony/website-skeleton '.$name);
        print $git_clone;
        $git_chmod = shell_exec('sudo chmod 775 '.MODULES_PATH.'/'.$name.' -R');
        print $git_chmod;
        $git_chown = shell_exec('sudo chown acksop:www-data '.MODULES_PATH.'/'.$name.' -R');
        print $git_chown;
        $git_controlleur = shell_exec('cp '.CONSOLE_PATH.'/skel/module_symfony.php '.CONTROLLERS_PATH.'/'.$name.'.php');
        $controlleur = file_get_contents(CONTROLLERS_PATH.'/'.$name.'.php');
        $controlleur = preg_replace('%MODULE%', $name, $controlleur);
        file_put_contents(CONTROLLERS_PATH.'/'.$name.'.php', $controlleur);
        print $git_controlleur;
        $git_modele = shell_exec('cp '.CONSOLE_PATH.'/skel/module.model '.MODELS_PATH.'/'.$name.'.model');
        $modele = file_get_contents(MODELS_PATH.'/'.$name.'.model');
        $modele = preg_replace('%MODULE%', $name, $modele);
        file_put_contents(MODELS_PATH.'/'.$name.'.model', $modele);
        print $git_modele;
        $git_view = shell_exec('cp '.CONSOLE_PATH.'/skel/module.blade.php '.VIEW_PATH.'/view/'.$name.'.blade.php');
        $vue = file_get_contents(VIEW_PATH.'/view/'.$name.'.blade.php');
        $vue = preg_replace('%MODULE%', 'symfony', $vue);
        file_put_contents(VIEW_PATH.'/view/'.$name.'.blade.php', $vue);
        print $git_view;

        //stabilize symfony application
        include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Symfony.php';
        Symfony::stabilize();

        $symfony_root = shell_exec('cp '.CONSOLE_PATH.'/skel/symfony-app/src '.MODULES_PATH.'/'.$name.'/ -Rf');
        $symfony_root = shell_exec('cp '.CONSOLE_PATH.'/skel/symfony-app/config '.MODULES_PATH.'/'.$name.'/ -Rf');
        $symfony_root = shell_exec('cp '.CONSOLE_PATH.'/skel/symfony-app/* '.MODULES_PATH.'/'.$name.'/ -Rf');
        $symfony_composer = shell_exec('cd '.MODULES_PATH.'/'.$name.' && composer update');

        print "\n\nN'oubliez pas d'ajouter au fichier '/application/modules/setup/registre.model' :"
            ."\n'.$name.' : Application permettant d'intégrer un module avec symfony"
            ."\n "
            ."\n et de créer la base de données!\n";
    }
    public static function removeSymfony($name = 'symfony')
    {
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

    public static function addWordpress($version = '5.4')
    {
        $git_clone = shell_exec('cd '.MODULES_PATH.' && git clone https://github.com/WordPress/WordPress.git wordpress');
        print $git_clone;
        $git_fetch = shell_exec('cd '.MODULES_PATH.'/wordpress && git fetch --all --tags');
        print $git_fetch;
        $git_checkout = shell_exec('cd '.MODULES_PATH.'/wordpress && git checkout tags/'.$version.' -b actual-branch');
        print $git_checkout;
        $git_chmod = shell_exec('sudo chmod 775 '.MODULES_PATH.'/wordpress -R');
        print $git_chmod;
        $git_chown = shell_exec('sudo chown acksop:www-data '.MODULES_PATH.'/wordpress -R');
        print $git_chown;
        $git_ln_1 = shell_exec('cd '.PUBLIC_PATH.' && ln -s ../application/modules/wordpress/ wordpress');
        print $git_ln_1;

        $languages = shell_exec('cp '.CONSOLE_PATH.'/skel/wordpress '.MODULES_PATH);

        $git_controlleur = shell_exec('cp '.CONSOLE_PATH.'/skel/module.php '.CONTROLLERS_PATH.'/wordpress.php');
        $controlleur = file_get_contents(CONTROLLERS_PATH.'/wordpress.php');
        $controlleur = preg_replace('%MODULE%', 'wordpress', $controlleur);
        file_put_contents(CONTROLLERS_PATH.'/wordpress.php', $controlleur);
        print $git_controlleur;
        $git_modele = shell_exec('cp '.CONSOLE_PATH.'/skel/module.model '.MODELS_PATH.'/wordpress.model');
        $modele = file_get_contents(MODELS_PATH.'/wordpress.model');
        $modele = preg_replace('%MODULE%', 'wordpress', $modele);
        file_put_contents(MODELS_PATH.'/wordpress.model', $modele);
        print $git_modele;
        $git_view = shell_exec('cp '.CONSOLE_PATH.'/skel/module.blade.php '.VIEW_PATH.'/view/wordpress.blade.php');
        $vue = file_get_contents(VIEW_PATH.'/view/wordpress.blade.php');
        $vue = preg_replace('%MODULE%', 'wordpress', $vue);
        file_put_contents(VIEW_PATH.'/view/wordpress.blade.php', $vue);
        print $git_view;

        print "\n\nN'oubliez pas d'ajouter au fichier '/application/modules/setup/registre.model' :"
            ."\nwordpress : Application permettant de générer un blog wordpress"
            ."\n "
            ."\n et de créer la base de données!\n";
    }
    public static function removeWordpress()
    {
        $git_clone = system('sudo rm -Rf '.MODULES_PATH.'/wordpress', $git_clone_retval);
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

    public static function addPrestashop($version = '1.7.5.0')
    {
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
        $controlleur = preg_replace('%MODULE%', 'prestashop', $controlleur);
        file_put_contents(CONTROLLERS_PATH.'/prestashop.php', $controlleur);
        print $git_controlleur;
        $git_modele = shell_exec('cp '.CONSOLE_PATH.'/skel/module.model '.MODELS_PATH.'/prestashop.model');
        $controlleur = file_get_contents(MODELS_PATH.'/prestashop.model');
        $controlleur = preg_replace('%MODULE%', 'prestashop', $controlleur);
        file_put_contents(MODELS_PATH.'/prestashop.model', $controlleur);
        print $git_modele;
        $git_view = shell_exec('cp '.CONSOLE_PATH.'/skel/module.blade.php '.VIEW_PATH.'/view/prestashop.blade.php');
        $controlleur = file_get_contents(VIEW_PATH.'/view/prestashop.blade.php');
        $controlleur = preg_replace('%MODULE%', 'prestashop', $controlleur);
        file_put_contents(VIEW_PATH.'/view/prestashop.blade.php', $controlleur);
        print $git_view;

        print "\n\nN'oubliez pas d'ajouter au fichier '/application/modules/setup/registre.model' :"
            ."\nprestashop : Application permettant de générer une site e-commerce prestashop"
            ."\n "
            ."\n et de créer la base de données!\n";
    }
    public static function removePrestashop()
    {
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

    public static function addPhplist($version = '3.5.2')
    {

        /*$git_clone = shell_exec('cd '.MODULES_PATH.' && git clone https://github.com/phpList/phplist3.git phplist');
        print $git_clone;
        $git_fetch = shell_exec('cd '.MODULES_PATH.'/phplist && git fetch --all --tags');
        print $git_fetch;
        $git_checkout = shell_exec('cd '.MODULES_PATH.'/phplist && git checkout '.$version);
        print $git_checkout;
        $composer_update = shell_exec('cd '.MODULES_PATH.'/phplist && composer update');
        print $composer_update;*/
        $wget_sourceforge = shell_exec('cd '.MODULES_PATH.' && wget -O phplist-'.$version.'.tar.gz https://sourceforge.net/projects/phplist/files/phplist/'.$version.'/phplist-'.$version.'.tgz/download');
        //$wget_sourceforge = shell_exec('cd '.MODULES_PATH.' && wget -O phplist-'.$version.'.tar.gz https://sourceforge.net/projects/phplist/files/phplist-development/'.$version.'/phplist-'.$version.'.tgz/download');
        $wget_sourceforge = shell_exec('sudo chmod 775 '.MODULES_PATH.'/phplist-'.$version.'.tar.gz');
        $wget_sourceforge = shell_exec('cd '.MODULES_PATH.' && gunzip ./phplist-'.$version.'.tar.gz');
        $wget_sourceforge = shell_exec('cd '.MODULES_PATH.' && tar -xvf ./phplist-'.$version.'.tar');
        $wget_sourceforge = shell_exec('cd '.MODULES_PATH.' && mv phplist-'.$version.' phplist');
        $git_ln_1 = shell_exec('cd '.PUBLIC_PATH.' && ln -s ../application/modules/phplist/public_html/lists phplist');
        print $git_ln_1;
        $upload_images = shell_exec('cd '.PUBLIC_PATH.' && mkdir uploadimages');
        print $upload_images;
        $git_chmod = shell_exec('sudo chmod 775 '.MODULES_PATH.'/phplist -R');
        $git_chmod = shell_exec('sudo chmod 775 '.PUBLIC_PATH.'/uploadimages');
        $git_chown = shell_exec('sudo chown acksop:www-data '.MODULES_PATH.'/phplist -R');
        $git_chown = shell_exec('sudo chown acksop:www-data '.PUBLIC_PATH.'/uploadimages');
        print $git_chown;
        $git_controlleur = shell_exec('cp '.CONSOLE_PATH.'/skel/module.php '.CONTROLLERS_PATH.'/phplist.php');
        $controlleur = file_get_contents(CONTROLLERS_PATH.'/phplist.php');
        $controlleur = preg_replace('%MODULE%', 'phplist', $controlleur);
        file_put_contents(CONTROLLERS_PATH.'/phplist.php', $controlleur);
        print $git_controlleur;
        $git_modele = shell_exec('cp '.CONSOLE_PATH.'/skel/module.model '.MODELS_PATH.'/phplist.model');
        $controlleur = file_get_contents(MODELS_PATH.'/phplist.model');
        $controlleur = preg_replace('%MODULE%', 'phplist', $controlleur);
        file_put_contents(MODELS_PATH.'/phplist.model', $controlleur);
        print $git_modele;
        $git_view = shell_exec('cp '.CONSOLE_PATH.'/skel/module.blade.php '.VIEW_PATH.'/view/phplist.blade.php');
        $controlleur = file_get_contents(VIEW_PATH.'/view/phplist.blade.php');
        $controlleur = preg_replace('%MODULE%', 'phplist', $controlleur);
        file_put_contents(VIEW_PATH.'/view/phplist.blade.php', $controlleur);
        print $git_view;

        print "Quel est le host de la base de donnees (default:192.168.1.70) ? ";
        $host = trim(fgets(STDIN));
        if ($host !== '' && preg_match('#(.)+#', $host)) {
            $host = $host;
        } else {
            $host = '192.168.1.70';
        }
        print "Quel est le nom de la base de donnees (default:SAND_phplist) ? ";
        $host_name = trim(fgets(STDIN));
        if ($host_name !== '' && preg_match('#(.)+#', $host_name)) {
            $host_name = $host_name;
        } else {
            $host_name = 'SAND_phplist';
        }
        print "Quel est le user de la base de donnees (default:sand) ? ";
        $user = trim(fgets(STDIN));
        if ($user !== '' && preg_match('#(.)+#', $user)) {
            $user = $user;
        } else {
            $user = 'sand';
        }
        print "Quel est le pass de la base de donnees (default:sand) ? ";
        $pass = trim(fgets(STDIN));
        if ($pass !== '' && preg_match('#(.)+#', $pass)) {
            $pass = $pass;
        } else {
            $pass = 'sand';
        }

        $config_skel = shell_exec('cp '.CONSOLE_PATH.'/skel/phplist/config.skel.php '.MODULES_PATH.'/phplist/public_html/lists/config/config.php');
        $config = file_get_contents(MODULES_PATH.'/phplist/public_html/lists/config/config.php');
        $config = preg_replace('%HOST_HOSTNAME%', $host, $config);
        $config = preg_replace('%HOST_USERNAME%', $user, $config);
        $config = preg_replace('%HOST_NAME%', $host_name, $config);
        $config = preg_replace('%HOST_PASSWORD%', $pass, $config);
        $config = preg_replace('%HOST_PAGEROOT%', '/phplist', $config);
        file_put_contents(MODULES_PATH.'/phplist/public_html/lists/config/config.php', $config);

        print "\n\nN'oubliez pas d'ajouter au fichier '/application/modules/setup/registre.model' :"
            ."\nphplist : Application permettant de générer une newsletter phplist"
            ."\n "
            ."\n et de créer la base de données!\n";
    }
    public static function removePhplist()
    {
        $git_clone = system('rm -Rf '.MODULES_PATH.'/phplist', $git_clone_retval);
        print $git_clone_retval;
        $git_ln_1 = system('rm -Rf '.PUBLIC_PATH.'/phplist', $git_ln_1_retval);
        print $git_ln_1_retval;
        $git_ln_1 = system('rm -Rf '.PUBLIC_PATH.'/uploadimages', $git_ln_1_retval);
        print $git_ln_1_retval;
        $git_controlleur = system('rm -f '.CONTROLLERS_PATH.'/phplist.php', $git_controlleur_retval);
        print $git_controlleur_retval;
        $git_modele = system('rm -f '.MODELS_PATH.'/phplist.model', $git_modele_retval);
        print $git_modele_retval;
        $git_view = system('rm -f '.VIEW_PATH.'/view/phplist.blade.php', $git_view_retval);
        print $git_view_retval;
    }
    public static function addWanewsletter($version = 'release-3.0.1')
    {
        $git_clone = shell_exec('cd '.MODULES_PATH.' && git clone https://github.com/wascripts/wanewsletter.git wanewsletter');
        print $git_clone;
        $git_fetch = shell_exec('cd '.MODULES_PATH.'/wanewsletter && git fetch --all --tags');
        print $git_fetch;
        $git_checkout = shell_exec('cd '.MODULES_PATH.'/wanewsletter && git checkout tags/'.$version);
        print $git_checkout;
        $composer_update = shell_exec('cd '.MODULES_PATH.'/wanewsletter && composer update');
        print $composer_update;
        $git_ln_1 = shell_exec('cd '.PUBLIC_PATH.' && ln -s ../application/modules/wanewsletter wanewsletter');
        print $git_ln_1;
        $git_chmod = shell_exec('sudo chmod 775 '.MODULES_PATH.'/wanewsletter -R');
        print $git_chmod;
        $git_chown = shell_exec('sudo chown acksop:www-data '.MODULES_PATH.'/wanewsletter -R');
        print $git_chown;
        $git_controlleur = shell_exec('cp '.CONSOLE_PATH.'/skel/module.php '.CONTROLLERS_PATH.'/wanewsletter.php');
        $controlleur = file_get_contents(CONTROLLERS_PATH.'/wanewsletter.php');
        $controlleur = preg_replace('%MODULE%', 'wanewsletter', $controlleur);
        file_put_contents(CONTROLLERS_PATH.'/wanewsletter.php', $controlleur);
        print $git_controlleur;
        $git_modele = shell_exec('cp '.CONSOLE_PATH.'/skel/module.model '.MODELS_PATH.'/wanewsletter.model');
        $controlleur = file_get_contents(MODELS_PATH.'/wanewsletter.model');
        $controlleur = preg_replace('%MODULE%', 'wanewsletter', $controlleur);
        file_put_contents(MODELS_PATH.'/wanewsletter.model', $controlleur);
        print $git_modele;
        $git_view = shell_exec('cp '.CONSOLE_PATH.'/skel/module.blade.php '.VIEW_PATH.'/view/wanewsletter.blade.php');
        $controlleur = file_get_contents(VIEW_PATH.'/view/wanewsletter.blade.php');
        $controlleur = preg_replace('%MODULE%', 'wanewsletter', $controlleur);
        file_put_contents(VIEW_PATH.'/view/wanewsletter.blade.php', $controlleur);
        print $git_view;

        /*print "Quel est le host de la base de donnees (default:192.168.1.70) ? ";
        $host = trim(fgets(STDIN));
        if($host !== '' && preg_match('#(.)+#',$host)){
            $host = $host;
        }else{
            $host = '192.168.1.70';
        }
        print "Quel est le nom de la base de donnees (default:SAND_phplist) ? ";
        $host_name = trim(fgets(STDIN));
        if($host_name !== '' && preg_match('#(.)+#',$host_name)){
            $host_name = $host_name;
        }else{
            $host_name = 'SAND_phplist';
        }
        print "Quel est le user de la base de donnees (default:sand) ? ";
        $user = trim(fgets(STDIN));
        if($user !== '' && preg_match('#(.)+#',$user)){
            $user = $user;
        }else{
            $user = 'sand';
        }
        print "Quel est le pass de la base de donnees (default:sand) ? ";
        $pass = trim(fgets(STDIN));
        if($pass !== '' && preg_match('#(.)+#',$pass)){
            $pass = $pass;
        }else{
            $pass = 'sand';
        }

        $git_view = shell_exec('cp '.CONSOLE_PATH.'/skel/phplist/config.skel.php '.MODULES_PATH.'/phplist/public_html/lists/config/config.php');
        $config = file_get_contents(MODULES_PATH.'/phplist/public_html/lists/config/config.php');
        $config = preg_replace('%HOST_HOSTNAME%',$host,$config);
        $config = preg_replace('%HOST_USERNAME%',$user,$config);
        $config = preg_replace('%HOST_NAME%',$host_name,$config);
        $config = preg_replace('%HOST_PASSWORD%',$pass,$config);
        $config = preg_replace('%HOST_PAGEROOT%','/phplist',$config);
        file_put_contents(MODULES_PATH.'/phplist/public_html/lists/config/config.php',$config);*/

        print "\n\nN'oubliez pas d'ajouter au fichier '/application/modules/setup/registre.model' :"
            ."\nwanewsletter : Application permettant de générer une newsletter wanewsletter"
            ."\n "
            ."\n et de créer la base de données!\n";
    }
    public static function removeWanewsletter()
    {
        $git_clone = system('rm -Rf '.MODULES_PATH.'/wanewsletter', $git_clone_retval);
        print $git_clone_retval;
        $git_ln_1 = system('rm -Rf '.PUBLIC_PATH.'/wanewsletter', $git_ln_1_retval);
        print $git_ln_1_retval;
        $git_controlleur = system('rm -f '.CONTROLLERS_PATH.'/wanewsletter.php', $git_controlleur_retval);
        print $git_controlleur_retval;
        $git_modele = system('rm -f '.MODELS_PATH.'/wanewsletter.model', $git_modele_retval);
        print $git_modele_retval;
        $git_view = system('rm -f '.VIEW_PATH.'/view/wanewsletter.blade.php', $git_view_retval);
        print $git_view_retval;
    }
    public static function addPHPMyNewsletter($version = 'v2.0.5')
    {
        $git_clone = shell_exec('cd '.MODULES_PATH.' && git clone https://github.com/Arnaud69/phpmynewsletter-2.0.git phpmynewsletter');
        print $git_clone;
        $git_fetch = shell_exec('cd '.MODULES_PATH.'/phpmynewsletter && git fetch --all --tags');
        print $git_fetch;
        $git_checkout = shell_exec('cd '.MODULES_PATH.'/phpmynewsletter && git checkout tags/'.$version.' -b actual-dev');
        print $git_checkout;
        //$composer_update = shell_exec('cd '.MODULES_PATH.'/phpmynewsletter && composer update');
        //print $composer_update;
        $git_ln_1 = shell_exec('cd '.PUBLIC_PATH.' && ln -s ../application/modules/phpmynewsletter phpmynewsletter');
        print $git_ln_1;
        $git_chmod = shell_exec('sudo chmod 775 '.MODULES_PATH.'/phpmynewsletter -R');
        print $git_chmod;
        $git_chown = shell_exec('sudo chown acksop:www-data '.MODULES_PATH.'/phpmynewsletter -R');
        print $git_chown;
        $git_controlleur = shell_exec('cp '.CONSOLE_PATH.'/skel/module.php '.CONTROLLERS_PATH.'/phpmynewsletter.php');
        $controlleur = file_get_contents(CONTROLLERS_PATH.'/phpmynewsletter.php');
        $controlleur = preg_replace('%MODULE%', 'phpmynewsletter', $controlleur);
        file_put_contents(CONTROLLERS_PATH.'/phpmynewsletter.php', $controlleur);
        print $git_controlleur;
        $git_modele = shell_exec('cp '.CONSOLE_PATH.'/skel/module.model '.MODELS_PATH.'/phpmynewsletter.model');
        $controlleur = file_get_contents(MODELS_PATH.'/phpmynewsletter.model');
        $controlleur = preg_replace('%MODULE%', 'phpmynewsletter', $controlleur);
        file_put_contents(MODELS_PATH.'/phpmynewsletter.model', $controlleur);
        print $git_modele;
        $git_view = shell_exec('cp '.CONSOLE_PATH.'/skel/module.blade.php '.VIEW_PATH.'/view/phpmynewsletter.blade.php');
        $controlleur = file_get_contents(VIEW_PATH.'/view/phpmynewsletter.blade.php');
        $controlleur = preg_replace('%MODULE%', 'phpmynewsletter', $controlleur);
        file_put_contents(VIEW_PATH.'/view/phpmynewsletter.blade.php', $controlleur);
        print $git_view;

        /*print "Quel est le host de la base de donnees (default:192.168.1.70) ? ";
        $host = trim(fgets(STDIN));
        if($host !== '' && preg_match('#(.)+#',$host)){
            $host = $host;
        }else{
            $host = '192.168.1.70';
        }
        print "Quel est le nom de la base de donnees (default:SAND_phplist) ? ";
        $host_name = trim(fgets(STDIN));
        if($host_name !== '' && preg_match('#(.)+#',$host_name)){
            $host_name = $host_name;
        }else{
            $host_name = 'SAND_phplist';
        }
        print "Quel est le user de la base de donnees (default:sand) ? ";
        $user = trim(fgets(STDIN));
        if($user !== '' && preg_match('#(.)+#',$user)){
            $user = $user;
        }else{
            $user = 'sand';
        }
        print "Quel est le pass de la base de donnees (default:sand) ? ";
        $pass = trim(fgets(STDIN));
        if($pass !== '' && preg_match('#(.)+#',$pass)){
            $pass = $pass;
        }else{
            $pass = 'sand';
        }

        $git_view = shell_exec('cp '.CONSOLE_PATH.'/skel/phplist/config.skel.php '.MODULES_PATH.'/phplist/public_html/lists/config/config.php');
        $config = file_get_contents(MODULES_PATH.'/phplist/public_html/lists/config/config.php');
        $config = preg_replace('%HOST_HOSTNAME%',$host,$config);
        $config = preg_replace('%HOST_USERNAME%',$user,$config);
        $config = preg_replace('%HOST_NAME%',$host_name,$config);
        $config = preg_replace('%HOST_PASSWORD%',$pass,$config);
        $config = preg_replace('%HOST_PAGEROOT%','/phplist',$config);
        file_put_contents(MODULES_PATH.'/phplist/public_html/lists/config/config.php',$config);*/

        print "\n\nN'oubliez pas d'ajouter au fichier '/application/modules/setup/registre.model' :"
            ."\nphpmynewsletter : Application permettant de générer une newsletter phpmynewsletter"
            ."\n "
            ."\n et de créer la base de données!\n";
    }
    public static function removePHPMyNewsletter()
    {
        $git_clone = system('rm -Rf '.MODULES_PATH.'/phpmynewsletter', $git_clone_retval);
        print $git_clone_retval;
        $git_ln_1 = system('rm -Rf '.PUBLIC_PATH.'/phpmynewsletter', $git_ln_1_retval);
        print $git_ln_1_retval;
        $git_controlleur = system('rm -f '.CONTROLLERS_PATH.'/phpmynewsletter.php', $git_controlleur_retval);
        print $git_controlleur_retval;
        $git_modele = system('rm -f '.MODELS_PATH.'/phpmynewsletter.model', $git_modele_retval);
        print $git_modele_retval;
        $git_view = system('rm -f '.VIEW_PATH.'/view/phpmynewsletter.blade.php', $git_view_retval);
        print $git_view_retval;
    }
}
