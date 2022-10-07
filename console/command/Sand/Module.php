<?php

namespace SAND\Command\Sand;
/**
 * Class Module
 * Commande Système du Framework permettant de gérer les modules
 * @package MVC\Command\Sand
 */
class Module
{
    /**
     * Méthode permettant d'afficher l'aide de la commande
     */
    public static function help()
    {
        print "Cette commande permet de manipuler les modules du framework\n\n";
        print "Elle peut accepter les attributs suivants\n";
        print "\t- add : pour ajouter un module\n";
        print "\t- remove : pour supprimer un module\n";
    }

    public static function add()
    {
        print "adding module...\n\n";
        print "Quel est le module a ajouter ?\n0.Laravel\n1.Symfony\n2.Wordpress\n3.Prestashop\n4.PhpList\n5.Wanewsletter\n6.PHPmyNewletter\n7.GitList\n>";
        $module = trim(fgets(STDIN));
        switch ($module) {
            case 0:
                print "Quel est le nom du module laravel à ajouter (default : laravel) ? ";
                $name = trim(fgets(STDIN));
                if ($name !== '' && preg_match('#(.)+#', $name)) {
                    Module::addLaravel($name);
                } else {
                    Module::addLaravel('laravel');
                }
                break;
            case 1:
                print "Quel est le nom du module symfony à ajouter (default : symfony) ? ";
                $name = trim(fgets(STDIN));
                print "Quel est la version de Symfony à ajouter (default : 4.4) ? ";
                $version = trim(fgets(STDIN));
                if ($version == '' && !preg_match('#(.)\.(.)\.(.)\.(.)#', $version)) {
                    $version = "4.4";
                }
                if ($name !== '' && preg_match('#(.)+#', $name)) {
                    Module::addSymfony($name,$version);
                } else {
                    Module::addSymfony('symfony',$version);
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
			case 7:
                print "Quel est la version de Gitlist à ajouter (default : 1.1.1) ? ";
                $version = trim(fgets(STDIN));
                if ($version !== '' && preg_match('#(.)\.(.)\.(.)#', $version)) {
                    Module::addGitlist($version);
                } else {
                    Module::addGitlist('1.1.1');
                }
                break;
            default:
        }
    }

    public static function remove()
    {
        print "removing module...\n\n";
        print "Quel est le module a supprimer?\n0.Laravel\n1.Symfony\n2.Wordpress\n3.Prestashop\n4.PhpList\n5.Wanewsletter\n6.PHPmyNewletter\n7.GitList\n>";
        $module = trim(fgets(STDIN));
        switch ($module) {			
            case 0:
                print "Quel est le nom du module laravel à supprimer (default : laravel) ? ";
                $name = trim(fgets(STDIN));
                if ($name !== '' && preg_match('#(.)+#', $name)) {
                    Module::removeLaravel($name);
                } else {
                    Module::removeLaravel('laravel');
                }
                break;
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
            case 7:
                Module::removeGitlist();
                break;
            default:
        }
    }

    private static function addLaravel($name = 'laravel')
    {
        $git_clone = shell_exec('cd '.MODULES_PATH.' && composer create-project laravel/laravel '.$name);
        print $git_clone;
        $git_chmod = shell_exec('chmod 775 '.MODULES_PATH.DIRECTORY_SEPARATOR.$name.' -R');
        print $git_chmod;
        $git_chown = shell_exec('chown acksop:www-data '.MODULES_PATH.DIRECTORY_SEPARATOR.$name.' -R');
        print $git_chown;
        $git_controlleur = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'module.laravel.php '.CONTROLLERS_PATH.DIRECTORY_SEPARATOR.$name.'.php');
        $controlleur = file_get_contents(CONTROLLERS_PATH.DIRECTORY_SEPARATOR.$name.'.php');
        $controlleur = preg_replace('/%%MODULE_NAME%%/', $name, $controlleur);
        $controlleur = preg_replace('/%%MODULE%%/', 'laravel', $controlleur);
        file_put_contents(CONTROLLERS_PATH.DIRECTORY_SEPARATOR.$name.'.php', $controlleur);
        print $git_controlleur;
        $git_modele = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'module.laravel.model '.MODELS_PATH.DIRECTORY_SEPARATOR.$name.'.model');
        $modele = file_get_contents(MODELS_PATH.DIRECTORY_SEPARATOR.$name.'.model');
        $modele = preg_replace('/%%MODULE_NAME%%/', $name, $modele);
        $modele = preg_replace('/%%MODULE%%/', 'laravel', $modele);
        file_put_contents(MODELS_PATH.DIRECTORY_SEPARATOR.$name.'.model', $modele);
        print $git_modele;
        $git_view = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'module.laravel.html.twig '.VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.''.$name.'.blade.php');
        $vue = file_get_contents(VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.''.$name.'.html.twig');
        $vue = preg_replace('/%%MODULE_NAME%%/', $name, $vue);
        $vue = preg_replace('/%%MODULE%%/', 'laravel', $vue);
        file_put_contents(VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.''.$name.'.html.twig', $vue);
        print $git_view;

        //stabilize symfony application
        //print "stabilize symfony module...\n\n";
        //$symfony_module = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'symfony-app/* '.MODULES_PATH.DIRECTORY_SEPARATOR.$name.' -Rf');
        //$symfony_composer = shell_exec('cd '.MODULES_PATH.DIRECTORY_SEPARATOR.$name.' && composer update');

        print "\n\nN'oubliez pas d'ajouter au fichier '/application/modules/setup/registre.model' :"
            ."\n'$name' : Application permettant d'intégrer un module avec laravel"
            ."\n "
            ."\n et de créer la base de données!\n";
    }
    public static function removeLaravel($name = 'laravel')
    {
        $git_clone = system('rm -Rf '.MODULES_PATH.DIRECTORY_SEPARATOR.$name, $git_clone_retval);
        print $git_clone_retval;
        $git_controlleur = system('rm -f '.CONTROLLERS_PATH.DIRECTORY_SEPARATOR.$name.'.php', $git_controlleur_retval);
        print $git_controlleur_retval;
        $git_modele = system('rm -f '.MODELS_PATH.DIRECTORY_SEPARATOR.$name.'.model', $git_modele_retval);
        print $git_modele_retval;
        $git_view = system('rm -f '.VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.''.$name.'.html.twig', $git_view_retval);
        print $git_view_retval;
    }

    private static function addSymfony($name = 'symfony',$version="4.4")
    {
        $git_clone = shell_exec('cd '.MODULES_PATH.' && composer create-project symfony/website-skeleton:"^'.$version.'" '.$name);
        print $git_clone;
        $git_chmod = shell_exec('chmod 775 '.MODULES_PATH.DIRECTORY_SEPARATOR.$name.' -R');
        print $git_chmod;
        $git_chown = shell_exec('chown acksop:www-data '.MODULES_PATH.DIRECTORY_SEPARATOR.$name.' -R');
        print $git_chown;
        $git_controlleur = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'module.symfony.php '.CONTROLLERS_PATH.DIRECTORY_SEPARATOR.$name.'.php');
        $controlleur = file_get_contents(CONTROLLERS_PATH.DIRECTORY_SEPARATOR.$name.'.php');
        $controlleur = preg_replace('/%%MODULE_NAME%%/', $name, $controlleur);
        $controlleur = preg_replace('/%%MODULE%%/', 'symfony', $controlleur);
        file_put_contents(CONTROLLERS_PATH.DIRECTORY_SEPARATOR.$name.'.php', $controlleur);
        print $git_controlleur;
        $git_modele = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'module.symfony.model '.MODELS_PATH.DIRECTORY_SEPARATOR.$name.'.model');
        $modele = file_get_contents(MODELS_PATH.DIRECTORY_SEPARATOR.$name.'.model');
        $modele = preg_replace('/%%MODULE_NAME%%/', $name, $modele);
        $modele = preg_replace('/%%MODULE%%/', 'symfony', $modele);
        file_put_contents(MODELS_PATH.DIRECTORY_SEPARATOR.$name.'.model', $modele);
        print $git_modele;
        $git_view = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'module.symfony.blade.php '.VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.''.$name.'.blade.php');
        $vue = file_get_contents(VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.''.$name.'.blade.php');
        $vue = preg_replace('/%%MODULE_NAME%%/', $name, $vue);
        $vue = preg_replace('/%%MODULE%%/', 'symfony', $vue);
        file_put_contents(VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.''.$name.'.blade.php', $vue);
        print $git_view;

        //stabilize symfony application
        //print "stabilize symfony module...\n\n";
        //$symfony_module = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'symfony-app/* '.MODULES_PATH.DIRECTORY_SEPARATOR.$name.' -Rf');
        //$symfony_composer = shell_exec('cd '.MODULES_PATH.DIRECTORY_SEPARATOR.$name.' && composer update');

        print "\n\nN'oubliez pas d'ajouter au fichier '/application/modules/setup/registre.model' :"
            ."\n'$name' : Application permettant d'intégrer un module avec symfony"
            ."\n "
            ."\n et de créer la base de données!\n";
    }
    public static function removeSymfony($name = 'symfony')
    {
        $git_clone = system('rm -Rf '.MODULES_PATH.DIRECTORY_SEPARATOR.$name, $git_clone_retval);
        print $git_clone_retval;
        /*$git_ln_1 = system('rm -Rf '.PUBLIC_PATH.DIRECTORY_SEPARATOR.$name, $git_ln_1_retval);
        print $git_ln_1_retval;*/
        $git_controlleur = system('rm -f '.CONTROLLERS_PATH.DIRECTORY_SEPARATOR.$name.'.php', $git_controlleur_retval);
        print $git_controlleur_retval;
        $git_modele = system('rm -f '.MODELS_PATH.DIRECTORY_SEPARATOR.$name.'.model', $git_modele_retval);
        print $git_modele_retval;
        $git_view = system('rm -f '.VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.''.$name.'.blade.php', $git_view_retval);
        print $git_view_retval;
    }

    public static function addWordpress($version = '5.4')
    {
        $git_clone = shell_exec('cd '.MODULES_PATH.' && git clone https://github.com/WordPress/WordPress.git wordpress');
        print $git_clone;
        $git_fetch = shell_exec('cd '.MODULES_PATH.DIRECTORY_SEPARATOR.'wordpress && git fetch --all --tags');
        print $git_fetch;
        $git_checkout = shell_exec('cd '.MODULES_PATH.DIRECTORY_SEPARATOR.'wordpress && git checkout tags/'.$version.' -b actual-branch');
        print $git_checkout;
        /*$git_chmod = shell_exec('sudo chmod 775 '.MODULES_PATH.DIRECTORY_SEPARATOR.'wordpress -R');
        print $git_chmod;
        $git_chown = shell_exec('sudo chown acksop:www-data '.MODULES_PATH.DIRECTORY_SEPARATOR.'wordpress -R');
        print $git_chown;*/
        $git_ln_1 = shell_exec('cd '.PUBLIC_PATH.' && ln -s ..'.DIRECTORY_SEPARATOR.'application'.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'wordpress'.DIRECTORY_SEPARATOR.' wordpress');
        print $git_ln_1;

        $languages = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'wordpress '.MODULES_PATH);

        $git_controlleur = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'module.php '.CONTROLLERS_PATH.DIRECTORY_SEPARATOR.'wordpress.php');
        $controlleur = file_get_contents(CONTROLLERS_PATH.DIRECTORY_SEPARATOR.'wordpress.php');
        $controlleur = preg_replace('/%%MODULE%%/', 'wordpress', $controlleur);
        $controlleur = preg_replace('/%%MODULE_NAME%%/', 'wordpress', $controlleur);
        file_put_contents(CONTROLLERS_PATH.DIRECTORY_SEPARATOR.'wordpress.php', $controlleur);
        print $git_controlleur;
        $git_modele = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'module.model '.MODELS_PATH.DIRECTORY_SEPARATOR.'wordpress.model');
        $modele = file_get_contents(MODELS_PATH.DIRECTORY_SEPARATOR.'wordpress.model');
        $modele = preg_replace('/%%MODULE%%/', 'wordpress', $modele);
        $controlleur = preg_replace('/%%MODULE_NAME%%/', 'wordpress', $controlleur);
        file_put_contents(MODELS_PATH.DIRECTORY_SEPARATOR.'wordpress.model', $modele);
        print $git_modele;
        $git_view = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'module.blade.php '.VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'wordpress.blade.php');
        $vue = file_get_contents(VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'wordpress.blade.php');
        $vue = preg_replace('/%%MODULE%%/', 'wordpress', $vue);
        $controlleur = preg_replace('/%%MODULE_NAME%%/', 'wordpress', $controlleur);
        file_put_contents(VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'wordpress.blade.php', $vue);
        print $git_view;

        print "\n\nN'oubliez pas d'ajouter au fichier '/application/modules/setup/registre.model' :"
            ."\nwordpress : Application permettant de générer un blog wordpress"
            ."\n "
            ."\n et de créer la base de données!\n";
    }
    public static function removeWordpress()
    {
        $git_clone = system('rm -Rf '.MODULES_PATH.DIRECTORY_SEPARATOR.'wordpress', $git_clone_retval);
        print $git_clone_retval;
        $git_ln_1 = system('rm -Rf '.PUBLIC_PATH.DIRECTORY_SEPARATOR.'wordpress', $git_ln_1_retval);
        print $git_ln_1_retval;
        $git_controlleur = system('rm -f '.CONTROLLERS_PATH.DIRECTORY_SEPARATOR.'wordpress.php', $git_controlleur_retval);
        print $git_controlleur_retval;
        $git_modele = system('rm -f '.MODELS_PATH.DIRECTORY_SEPARATOR.'wordpress.model', $git_modele_retval);
        print $git_modele_retval;
        $git_view = system('rm -f '.VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'wordpress.blade.php', $git_view_retval);
        print $git_view_retval;
    }

    public static function addPrestashop($version = '1.7.5.0')
    {
        $git_clone = shell_exec('cd '.MODULES_PATH.' && git clone https://github.com/PrestaShop/PrestaShop.git prestashop');
        print $git_clone;
        $git_fetch = shell_exec('cd '.MODULES_PATH.DIRECTORY_SEPARATOR.'prestashop && git fetch --all --tags');
        print $git_fetch;
        $git_checkout = shell_exec('cd '.MODULES_PATH.DIRECTORY_SEPARATOR.'prestashop && git checkout tags/'.$version.' -b actual-branch ');
        print $git_checkout;
        $composer_update = shell_exec('cd '.MODULES_PATH.DIRECTORY_SEPARATOR.'prestashop && composer update');
        print $composer_update;
        $git_ln_1 = shell_exec('cd '.PUBLIC_PATH.' && ln -s ../application/modules/prestashop/ prestashop');
        print $git_ln_1;
        /*$git_chmod = shell_exec('sudo chmod 775 '.MODULES_PATH.DIRECTORY_SEPARATOR.'prestashop -R');
        print $git_chmod;
        $git_chown = shell_exec('sudo chown acksop:www-data '.MODULES_PATH.DIRECTORY_SEPARATOR.'prestashop -R');
        print $git_chown;*/
        $git_ln_2 = shell_exec('cd '.MODULES_PATH.DIRECTORY_SEPARATOR.'prestashop && ln -s ..'.DIRECTORY_SEPARATOR.'prestashop'.DIRECTORY_SEPARATOR.' prestashop');
        print $git_ln_2;
        $git_controlleur = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'module.php '.CONTROLLERS_PATH.DIRECTORY_SEPARATOR.'prestashop.php');
        $controlleur = file_get_contents(CONTROLLERS_PATH.DIRECTORY_SEPARATOR.'prestashop.php');
        $controlleur = preg_replace('/%%MODULE%%/', 'prestashop', $controlleur);
        $controlleur = preg_replace('/%%MODULE_NAME%%/', 'prestashop', $controlleur);
        file_put_contents(CONTROLLERS_PATH.DIRECTORY_SEPARATOR.'prestashop.php', $controlleur);
        print $git_controlleur;
        $git_modele = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'module.model '.MODELS_PATH.DIRECTORY_SEPARATOR.'prestashop.model');
        $controlleur = file_get_contents(MODELS_PATH.DIRECTORY_SEPARATOR.'prestashop.model');
        $controlleur = preg_replace('/%%MODULE%%/', 'prestashop', $controlleur);
        $controlleur = preg_replace('/%%MODULE_NAME%%/', 'prestashop', $controlleur);
        file_put_contents(MODELS_PATH.DIRECTORY_SEPARATOR.'prestashop.model', $controlleur);
        print $git_modele;
        $git_view = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'module.blade.php '.VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'prestashop.blade.php');
        $controlleur = file_get_contents(VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'prestashop.blade.php');
        $controlleur = preg_replace('/%%MODULE%%/', 'prestashop', $controlleur);
        $controlleur = preg_replace('/%%MODULE_NAME%%/', 'prestashop', $controlleur);
        file_put_contents(VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'prestashop.blade.php', $controlleur);
        print $git_view;

        print "\n\nN'oubliez pas d'ajouter au fichier '/application/modules/setup/registre.model' :"
            ."\nprestashop : Application permettant de générer une site e-commerce prestashop"
            ."\n "
            ."\n et de créer la base de données!\n";
    }
    public static function removePrestashop()
    {
        $git_clone = system('rm -Rf '.MODULES_PATH.DIRECTORY_SEPARATOR.'prestashop', $git_clone_retval);
        print $git_clone_retval;
        $git_ln_1 = system('rm -Rf '.PUBLIC_PATH.DIRECTORY_SEPARATOR.'prestashop', $git_ln_1_retval);
        print $git_ln_1_retval;
        $git_controlleur = system('rm -f '.CONTROLLERS_PATH.DIRECTORY_SEPARATOR.'prestashop.php', $git_controlleur_retval);
        print $git_controlleur_retval;
        $git_modele = system('rm -f '.MODELS_PATH.DIRECTORY_SEPARATOR.'prestashop.model', $git_modele_retval);
        print $git_modele_retval;
        $git_view = system('rm -f '.VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'prestashop.blade.php', $git_view_retval);
        print $git_view_retval;
    }

    public static function addPhplist($version = '3.5.2')
    {

        /*$git_clone = shell_exec('cd '.MODULES_PATH.' && git clone https://github.com/phpList/phplist3.git phplist');
        print $git_clone;
        $git_fetch = shell_exec('cd '.MODULES_PATH.DIRECTORY_SEPARATOR.'phplist && git fetch --all --tags');
        print $git_fetch;
        $git_checkout = shell_exec('cd '.MODULES_PATH.DIRECTORY_SEPARATOR.'phplist && git checkout '.$version);
        print $git_checkout;
        $composer_update = shell_exec('cd '.MODULES_PATH.DIRECTORY_SEPARATOR.'phplist && composer update');
        print $composer_update;*/
        $wget_sourceforge = shell_exec('cd '.MODULES_PATH.' && wget -O phplist-'.$version.'.tar.gz https://sourceforge.net/projects/phplist/files/phplist/'.$version.'/phplist-'.$version.'.tgz/download');
        //$wget_sourceforge = shell_exec('cd '.MODULES_PATH.' && wget -O phplist-'.$version.'.tar.gz https://sourceforge.net/projects/phplist/files/phplist-development/'.$version.DIRECTORY_SEPARATOR.'phplist-'.$version.'.tgz/download');
        $wget_sourceforge = shell_exec('chmod 775 '.MODULES_PATH.DIRECTORY_SEPARATOR.'phplist-'.$version.'.tar.gz');
        $wget_sourceforge = shell_exec('cd '.MODULES_PATH.' && gunzip ./phplist-'.$version.'.tar.gz');
        $wget_sourceforge = shell_exec('cd '.MODULES_PATH.' && tar -xvf ./phplist-'.$version.'.tar');
        $wget_sourceforge = shell_exec('cd '.MODULES_PATH.' && mv phplist-'.$version.' phplist');
        $git_ln_1 = shell_exec('cd '.PUBLIC_PATH.' && ln -s ..'.DIRECTORY_SEPARATOR.'application'.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'phplist'.DIRECTORY_SEPARATOR.'public_html'.DIRECTORY_SEPARATOR.'lists phplist');
        print $git_ln_1;
        $upload_images = shell_exec('cd '.PUBLIC_PATH.' && mkdir uploadimages');
        print $upload_images;
        $git_chmod = shell_exec('chmod 775 '.MODULES_PATH.DIRECTORY_SEPARATOR.'phplist -R');
        $git_chmod = shell_exec('chmod 775 '.PUBLIC_PATH.DIRECTORY_SEPARATOR.'uploadimages');
        $git_chown = shell_exec('chown acksop:www-data '.MODULES_PATH.DIRECTORY_SEPARATOR.'phplist -R');
        $git_chown = shell_exec('chown acksop:www-data '.PUBLIC_PATH.DIRECTORY_SEPARATOR.'uploadimages');
        print $git_chown;
        $git_controlleur = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'module.php '.CONTROLLERS_PATH.DIRECTORY_SEPARATOR.'phplist.php');
        $controlleur = file_get_contents(CONTROLLERS_PATH.DIRECTORY_SEPARATOR.'phplist.php');
        $controlleur = preg_replace('/%%MODULE%%/', 'phplist', $controlleur);
        $controlleur = preg_replace('/%%MODULE_NAME%%/', 'phplist', $controlleur);
        file_put_contents(CONTROLLERS_PATH.DIRECTORY_SEPARATOR.'phplist.php', $controlleur);
        print $git_controlleur;
        $git_modele = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'module.model '.MODELS_PATH.DIRECTORY_SEPARATOR.'phplist.model');
        $controlleur = file_get_contents(MODELS_PATH.DIRECTORY_SEPARATOR.'phplist.model');
        $controlleur = preg_replace('/%%MODULE%%/', 'phplist', $controlleur);
        $controlleur = preg_replace('/%%MODULE_NAME%%/', 'phplist', $controlleur);
        file_put_contents(MODELS_PATH.DIRECTORY_SEPARATOR.'phplist.model', $controlleur);
        print $git_modele;
        $git_view = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'module.blade.php '.VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'phplist.blade.php');
        $controlleur = file_get_contents(VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'phplist.blade.php');
        $controlleur = preg_replace('/%%MODULE%%/', 'phplist', $controlleur);
        $controlleur = preg_replace('/%%MODULE_NAME%%/', 'phplist', $controlleur);
        file_put_contents(VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'phplist.blade.php', $controlleur);
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

        $config_skel = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'phplist'.DIRECTORY_SEPARATOR.'config.skel.php '.MODULES_PATH.DIRECTORY_SEPARATOR.'phplist'.DIRECTORY_SEPARATOR.'public_html'.DIRECTORY_SEPARATOR.'lists'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php');
        $config = file_get_contents(MODULES_PATH.DIRECTORY_SEPARATOR.'phplist'.DIRECTORY_SEPARATOR.'public_html'.DIRECTORY_SEPARATOR.'lists'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php');
        $config = preg_replace('%HOST_HOSTNAME%', $host, $config);
        $config = preg_replace('%HOST_USERNAME%', $user, $config);
        $config = preg_replace('%HOST_NAME%', $host_name, $config);
        $config = preg_replace('%HOST_PASSWORD%', $pass, $config);
        $config = preg_replace('%HOST_PAGEROOT%', DIRECTORY_SEPARATOR.'phplist', $config);
        file_put_contents(MODULES_PATH.DIRECTORY_SEPARATOR.'phplist'.DIRECTORY_SEPARATOR.'public_html'.DIRECTORY_SEPARATOR.'lists'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php', $config);

        print "\n\nN'oubliez pas d'ajouter au fichier '/application/modules/setup/registre.model' :"
            ."\nphplist : Application permettant de générer une newsletter phplist"
            ."\n "
            ."\n et de créer la base de données!\n";
    }
    public static function removePhplist()
    {
        $git_clone = system('rm -Rf '.MODULES_PATH.DIRECTORY_SEPARATOR.'phplist', $git_clone_retval);
        print $git_clone_retval;
        $git_ln_1 = system('rm -Rf '.PUBLIC_PATH.DIRECTORY_SEPARATOR.'phplist', $git_ln_1_retval);
        print $git_ln_1_retval;
        $git_ln_1 = system('rm -Rf '.PUBLIC_PATH.DIRECTORY_SEPARATOR.'uploadimages', $git_ln_1_retval);
        print $git_ln_1_retval;
        $git_controlleur = system('rm -f '.CONTROLLERS_PATH.DIRECTORY_SEPARATOR.'phplist.php', $git_controlleur_retval);
        print $git_controlleur_retval;
        $git_modele = system('rm -f '.MODELS_PATH.DIRECTORY_SEPARATOR.'phplist.model', $git_modele_retval);
        print $git_modele_retval;
        $git_view = system('rm -f '.VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'phplist.blade.php', $git_view_retval);
        print $git_view_retval;
    }
    public static function addWanewsletter($version = 'release-3.0.1')
    {
        $git_clone = shell_exec('cd '.MODULES_PATH.' && git clone https://github.com/wascripts/wanewsletter.git wanewsletter');
        print $git_clone;
        $git_fetch = shell_exec('cd '.MODULES_PATH.DIRECTORY_SEPARATOR.'wanewsletter && git fetch --all --tags');
        print $git_fetch;
        $git_checkout = shell_exec('cd '.MODULES_PATH.DIRECTORY_SEPARATOR.'wanewsletter && git checkout tags/'.$version);
        print $git_checkout;
        $composer_update = shell_exec('cd '.MODULES_PATH.DIRECTORY_SEPARATOR.'wanewsletter && composer update');
        print $composer_update;
        $git_ln_1 = shell_exec('cd '.PUBLIC_PATH.' && ln -s ..'.DIRECTORY_SEPARATOR.'application'.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'wanewsletter wanewsletter');
        print $git_ln_1;
        /*$git_chmod = shell_exec('chmod 775 '.MODULES_PATH.DIRECTORY_SEPARATOR.'wanewsletter -R');
        print $git_chmod;
        $git_chown = shell_exec('chown acksop:www-data '.MODULES_PATH.DIRECTORY_SEPARATOR.'wanewsletter -R');
        print $git_chown;*/
        $git_controlleur = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'module.php '.CONTROLLERS_PATH.DIRECTORY_SEPARATOR.'wanewsletter.php');
        $controlleur = file_get_contents(CONTROLLERS_PATH.DIRECTORY_SEPARATOR.'wanewsletter.php');
        $controlleur = preg_replace('/%%MODULE%%/', 'wanewsletter', $controlleur);
        $controlleur = preg_replace('/%%MODULE_NAME%%/', 'wanewsletter', $controlleur);
        file_put_contents(CONTROLLERS_PATH.DIRECTORY_SEPARATOR.'wanewsletter.php', $controlleur);
        print $git_controlleur;
        $git_modele = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'module.model '.MODELS_PATH.DIRECTORY_SEPARATOR.'wanewsletter.model');
        $controlleur = file_get_contents(MODELS_PATH.DIRECTORY_SEPARATOR.'wanewsletter.model');
        $controlleur = preg_replace('/%%MODULE%%/', 'wanewsletter', $controlleur);
        $controlleur = preg_replace('/%%MODULE_NAME%%/', 'wanewsletter', $controlleur);
        file_put_contents(MODELS_PATH.DIRECTORY_SEPARATOR.'wanewsletter.model', $controlleur);
        print $git_modele;
        $git_view = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'module.blade.php '.VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'wanewsletter.blade.php');
        $controlleur = file_get_contents(VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'wanewsletter.blade.php');
        $controlleur = preg_replace('/%%MODULE%%/', 'wanewsletter', $controlleur);
        $controlleur = preg_replace('/%%MODULE_NAME%%/', 'wanewsletter', $controlleur);
        file_put_contents(VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'wanewsletter.blade.php', $controlleur);
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

        $git_view = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'phplist/config.skel.php '.MODULES_PATH.DIRECTORY_SEPARATOR.'phplist/public_html/lists/config/config.php');
        $config = file_get_contents(MODULES_PATH.DIRECTORY_SEPARATOR.'phplist/public_html/lists/config/config.php');
        $config = preg_replace('%HOST_HOSTNAME%',$host,$config);
        $config = preg_replace('%HOST_USERNAME%',$user,$config);
        $config = preg_replace('%HOST_NAME%',$host_name,$config);
        $config = preg_replace('%HOST_PASSWORD%',$pass,$config);
        $config = preg_replace('%HOST_PAGEROOT%',DIRECTORY_SEPARATOR.'phplist',$config);
        file_put_contents(MODULES_PATH.DIRECTORY_SEPARATOR.'phplist/public_html/lists/config/config.php',$config);*/

        print "\n\nN'oubliez pas d'ajouter au fichier '/application/modules/setup/registre.model' :"
            ."\nwanewsletter : Application permettant de générer une newsletter wanewsletter"
            ."\n "
            ."\n et de créer la base de données!\n";
    }
    public static function removeWanewsletter()
    {
        $git_clone = system('rm -Rf '.MODULES_PATH.DIRECTORY_SEPARATOR.'wanewsletter', $git_clone_retval);
        print $git_clone_retval;
        $git_ln_1 = system('rm -Rf '.PUBLIC_PATH.DIRECTORY_SEPARATOR.'wanewsletter', $git_ln_1_retval);
        print $git_ln_1_retval;
        $git_controlleur = system('rm -f '.CONTROLLERS_PATH.DIRECTORY_SEPARATOR.'wanewsletter.php', $git_controlleur_retval);
        print $git_controlleur_retval;
        $git_modele = system('rm -f '.MODELS_PATH.DIRECTORY_SEPARATOR.'wanewsletter.model', $git_modele_retval);
        print $git_modele_retval;
        $git_view = system('rm -f '.VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'wanewsletter.blade.php', $git_view_retval);
        print $git_view_retval;
    }
    public static function addPHPMyNewsletter($version = 'v2.0.5')
    {
        $git_clone = shell_exec('cd '.MODULES_PATH.' && git clone https://github.com/Arnaud69/phpmynewsletter-2.0.git phpmynewsletter');
        print $git_clone;
        $git_fetch = shell_exec('cd '.MODULES_PATH.DIRECTORY_SEPARATOR.'phpmynewsletter && git fetch --all --tags');
        print $git_fetch;
        $git_checkout = shell_exec('cd '.MODULES_PATH.DIRECTORY_SEPARATOR.'phpmynewsletter && git checkout tags/'.$version.' -b actual-dev');
        print $git_checkout;
        //$composer_update = shell_exec('cd '.MODULES_PATH.DIRECTORY_SEPARATOR.'phpmynewsletter && composer update');
        //print $composer_update;
        $git_ln_1 = shell_exec('cd '.PUBLIC_PATH.' && ln -s ..'.DIRECTORY_SEPARATOR.'application'.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'phpmynewsletter phpmynewsletter');
        print $git_ln_1;
        /*$git_chmod = shell_exec('chmod 775 '.MODULES_PATH.DIRECTORY_SEPARATOR.'phpmynewsletter -R');
        print $git_chmod;
        $git_chown = shell_exec('chown acksop:www-data '.MODULES_PATH.DIRECTORY_SEPARATOR.'phpmynewsletter -R');
        print $git_chown;*/
        $git_controlleur = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'module.php '.CONTROLLERS_PATH.DIRECTORY_SEPARATOR.'phpmynewsletter.php');
        $controlleur = file_get_contents(CONTROLLERS_PATH.DIRECTORY_SEPARATOR.'phpmynewsletter.php');
        $controlleur = preg_replace('/%%MODULE%%/', 'phpmynewsletter', $controlleur);
        $controlleur = preg_replace('/%%MODULE_NAME%%/', 'phpmynewsletter', $controlleur);
        file_put_contents(CONTROLLERS_PATH.DIRECTORY_SEPARATOR.'phpmynewsletter.php', $controlleur);
        print $git_controlleur;
        $git_modele = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'module.model '.MODELS_PATH.DIRECTORY_SEPARATOR.'phpmynewsletter.model');
        $controlleur = file_get_contents(MODELS_PATH.DIRECTORY_SEPARATOR.'phpmynewsletter.model');
        $controlleur = preg_replace('/%%MODULE%%/', 'phpmynewsletter', $controlleur);
        $controlleur = preg_replace('/%%MODULE_NAME%%/', 'phpmynewsletter', $controlleur);
        file_put_contents(MODELS_PATH.DIRECTORY_SEPARATOR.'phpmynewsletter.model', $controlleur);
        print $git_modele;
        $git_view = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'module.blade.php '.VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'phpmynewsletter.blade.php');
        $controlleur = file_get_contents(VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'phpmynewsletter.blade.php');
        $controlleur = preg_replace('/%%MODULE%%/', 'phpmynewsletter', $controlleur);
        $controlleur = preg_replace('/%%MODULE_NAME%%/', 'phpmynewsletter', $controlleur);
        file_put_contents(VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'phpmynewsletter.blade.php', $controlleur);
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

        $git_view = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'phplist/config.skel.php '.MODULES_PATH.DIRECTORY_SEPARATOR.'phplist/public_html/lists/config/config.php');
        $config = file_get_contents(MODULES_PATH.DIRECTORY_SEPARATOR.'phplist/public_html/lists/config/config.php');
        $config = preg_replace('%HOST_HOSTNAME%',$host,$config);
        $config = preg_replace('%HOST_USERNAME%',$user,$config);
        $config = preg_replace('%HOST_NAME%',$host_name,$config);
        $config = preg_replace('%HOST_PASSWORD%',$pass,$config);
        $config = preg_replace('%HOST_PAGEROOT%',DIRECTORY_SEPARATOR.'phplist',$config);
        file_put_contents(MODULES_PATH.DIRECTORY_SEPARATOR.'phplist/public_html/lists/config/config.php',$config);*/

        print "\n\nN'oubliez pas d'ajouter au fichier '/application/modules/setup/registre.model' :"
            ."\nphpmynewsletter : Application permettant de générer une newsletter phpmynewsletter"
            ."\n "
            ."\n et de créer la base de données!\n";
    }
	
    public static function removePHPMyNewsletter()
    {
        $git_clone = system('rm -Rf '.MODULES_PATH.DIRECTORY_SEPARATOR.'phpmynewsletter', $git_clone_retval);
        print $git_clone_retval;
        $git_ln_1 = system('rm -Rf '.PUBLIC_PATH.DIRECTORY_SEPARATOR.'phpmynewsletter', $git_ln_1_retval);
        print $git_ln_1_retval;
        $git_controlleur = system('rm -f '.CONTROLLERS_PATH.DIRECTORY_SEPARATOR.'phpmynewsletter.php', $git_controlleur_retval);
        print $git_controlleur_retval;
        $git_modele = system('rm -f '.MODELS_PATH.DIRECTORY_SEPARATOR.'phpmynewsletter.model', $git_modele_retval);
        print $git_modele_retval;
        $git_view = system('rm -f '.VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'phpmynewsletter.blade.php', $git_view_retval);
        print $git_view_retval;
    }

    public static function addGitlist($version = '1.1.1')
    {
        $git_clone = shell_exec('cd '.MODULES_PATH.' && git clone https://github.com/klaussilveira/gitlist.git gitlist');
        print $git_clone;
        $git_fetch = shell_exec('cd '.MODULES_PATH.DIRECTORY_SEPARATOR.'gitlist && git fetch --all --tags');
        print $git_fetch;
        $git_checkout = shell_exec('cd '.MODULES_PATH.DIRECTORY_SEPARATOR.'gitlist && git checkout tags/'.$version.' -b actual-dev');
        print $git_checkout;
        //add skel rewriting path of module
        /*if (!\MVC\Component\Copy::rcopy(CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'gitlist'.DIRECTORY_SEPARATOR.'*', MODULES_PATH.DIRECTORY_SEPARATOR.'gitlist')) {
            echo "failed to copy gitlist controlleur skeleton...\n";
        }*/
        $git_stabilise = shell_exec('cp -Rf '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'gitlist'.DIRECTORY_SEPARATOR.'* '.MODULES_PATH.DIRECTORY_SEPARATOR.'gitlist'.DIRECTORY_SEPARATOR);
        $composer_update = shell_exec('cd '.MODULES_PATH.DIRECTORY_SEPARATOR.'gitlist && composer update');
        print $composer_update;
        $git_ln_1 = shell_exec('cd '.PUBLIC_PATH.' && ln -s ..'.DIRECTORY_SEPARATOR.'application'.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'gitlist'.DIRECTORY_SEPARATOR.'themes themes');
        print $git_ln_1;
        /*$git_chmod = shell_exec('chmod 775 '.MODULES_PATH.DIRECTORY_SEPARATOR.'gitlist -R');
        print $git_chmod;
        $git_chown = shell_exec('chown acksop:www-data '.MODULES_PATH.DIRECTORY_SEPARATOR.'gitlist -R');
        print $git_chown;*/
        if (!copy(CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'module.php', CONTROLLERS_PATH.DIRECTORY_SEPARATOR.'gitlist.php')) {
            echo "failed to copy controlleur skel...\n";
        }
        //$git_controlleur = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'module.php '.CONTROLLERS_PATH.DIRECTORY_SEPARATOR.'gitlist.php');
        $controlleur = file_get_contents(CONTROLLERS_PATH.DIRECTORY_SEPARATOR.'gitlist.php');
        $controlleur = preg_replace('/%%MODULE%%/', 'gitlist', $controlleur);
        $controlleur = preg_replace('/%%MODULE_NAME%%/', 'gitlist', $controlleur);
        file_put_contents(CONTROLLERS_PATH.DIRECTORY_SEPARATOR.'gitlist.php', $controlleur);
        //print $git_controlleur;
        if (!copy(CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'module.model', MODELS_PATH.DIRECTORY_SEPARATOR.'gitlist.model')) {
            echo "failed to copy model skel...\n";
        }
        //$git_modele = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'module.model '.MODELS_PATH.DIRECTORY_SEPARATOR.'gitlist.model');
        $controlleur = file_get_contents(MODELS_PATH.DIRECTORY_SEPARATOR.'gitlist.model');
        $controlleur = preg_replace('/%%MODULE%%/', 'gitlist', $controlleur);
        $controlleur = preg_replace('/%%MODULE_NAME%%/', 'gitlist', $controlleur);
        file_put_contents(MODELS_PATH.DIRECTORY_SEPARATOR.'gitlist.model', $controlleur);
        //print $git_modele;
        if (!copy(CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'module.blade.php', VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'gitlist.blade.php')) {
            echo "failed to copy view blade skel...\n";
        }
        //$git_view = shell_exec('cp '.CONSOLE_PATH.DIRECTORY_SEPARATOR.'skel'.DIRECTORY_SEPARATOR.'module.blade.php '.VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'gitlist.blade.php');
        $controlleur = file_get_contents(VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'gitlist.blade.php');
        $controlleur = preg_replace('/%%MODULE%%/', 'gitlist', $controlleur);
        $controlleur = preg_replace('/%%MODULE_NAME%%/', 'gitlist', $controlleur);
        file_put_contents(VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'gitlist.blade.php', $controlleur);
        //print $git_view;

        print "\n\nN'oubliez pas d'ajouter au fichier '/application/modules/setup/registre.model' :"
            ."\ngitlist : Application permettant  mettre en ligne des code source au format git sur des sites web en php"
            ."\n ha, et si une des copy du skeleton n'as pas fonctionné : tenter de la réaliser à la main, sous windows la commande copy de php est encore perfectible....";
    }
	
	public static function removeGitlist()
    {
        $git_clone = system('rm -Rf '.MODULES_PATH.DIRECTORY_SEPARATOR.'gitlist', $git_clone_retval);
        print $git_clone_retval;
        $git_ln_1 = system('rm -Rf '.PUBLIC_PATH.DIRECTORY_SEPARATOR.'gitlist', $git_ln_1_retval);
        print $git_ln_1_retval;
        $git_controlleur = system('rm -f '.CONTROLLERS_PATH.DIRECTORY_SEPARATOR.'gitlist.php', $git_controlleur_retval);
        print $git_controlleur_retval;
        $git_modele = system('rm -f '.MODELS_PATH.DIRECTORY_SEPARATOR.'gitlist.model', $git_modele_retval);
        print $git_modele_retval;
        $git_view = system('rm -f '.VIEW_PATH.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'gitlist.blade.php', $git_view_retval);
        print $git_view_retval;
    }

}