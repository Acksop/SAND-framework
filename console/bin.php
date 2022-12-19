#!/usr/bin/env php
<?php
/**
 * Controlleur central du lancement des commandes
 * Package MVC\Command
 * @author Emmanuel ROY
 * @todo protect by chosen licence between an CC-licensed or MIT-licenced (open source)
 * @version 3.5
 * @uses \Command SAND Console-Script of commands
 */
global $argv;

require dirname(__DIR__) . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";
\MVC\Component\Debug::setPHPvalues();

function appel_cmd($class,$static_method,$argv){
    if(method_exists($class,$static_method)) {

        //récupération des arguments de la commande pour passage en paramètre de la méthode
        $arguments = array();
        if (isset($argv[2])) {
            for ($i = 2; $i < count($argv); $i++) {
                $arguments[] = $argv[$i];
            }
        }
        //récupération des arguments maitres "--env=????????????? ou --debug-aff=TRUEor????????"
        $i = 0;
        foreach ($arguments as $master_arg) {
            /*if (preg_match("/--env=([A-Z]*)/", $master_arg, $matches)) {
                define("ENV", $matches[1]);
                unset($arguments[$i]);
            }*/
            $i++;
        }

        //appel de la commande avec les paramètres spécifiés et récupération des erreurs
        $errors = $class::$static_method(...$arguments);

        if ($errors !== null) {
            \SAND\Component\Error::logErrors($errors);
        }
    }else{
        $static_method = 'help';
        $class::$static_method();
    }
}




if (isset($argv[1])) {
    $option = explode(':', $argv[1]);
    $command_file = dirname(__FILE__) . DIRECTORY_SEPARATOR . "command" . DIRECTORY_SEPARATOR . ucfirst($option[0]) . ".php";
    if (is_file($command_file)) {
        $class = "SAND\\Command\\" . ucfirst($option[0]);

        if(isset($option[1]) && $option[1] !== '') {
            $static_method = $option[1];
            appel_cmd($class,$static_method,$argv);

        }else{
            $static_method = 'help';
            $class::$static_method();
        }
    } else {
        $command_file = dirname(__FILE__) . DIRECTORY_SEPARATOR . "command" . DIRECTORY_SEPARATOR . "App" . DIRECTORY_SEPARATOR . ucfirst($option[0]) . ".php";
        if (is_file($command_file)) {
            $class = "SAND\\Command\\App\\" . ucfirst($option[0]);

            if(isset($option[1]) && $option[1] !== '') {
                $static_method = $option[1];
                appel_cmd($class,$static_method,$argv);

            }else{
                $static_method = 'help';
                $class::$static_method();
            }
        } else {
            $command_file = dirname(__FILE__) . DIRECTORY_SEPARATOR . "command" . DIRECTORY_SEPARATOR . "Sand" . DIRECTORY_SEPARATOR . ucfirst($option[0]) . ".php";
            if (is_file($command_file)) {
                $class = "SAND\\Command\\Sand\\" . ucfirst($option[0]);

                if(isset($option[1]) && $option[1] !== '') {
                    $static_method = $option[1];
                    appel_cmd($class,$static_method,$argv);

                }else{
                    $static_method = 'help';
                    $class::$static_method();
                }
            } else {
                print "SAND Command not found !\n";
                $class = "SAND\\Command\\Help";
                $static_method = 'help';
                $class::$static_method();
            }
        }
    }
} else {
    print "No command was specified !\n";
    $class = "SAND\\Command\\Help";
    $static_method = 'help';
    $class::$static_method();

}