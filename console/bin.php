#!/usr/bin/php
<?php
global $argv;

require dirname(__DIR__) . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";

\MVC\Classe\Dumper::setPHPvalues();

//var_dump($argv);

if (isset($argv[1])) {
    $option = explode(':', $argv[1]);
    $command_file = dirname(__FILE__) . DIRECTORY_SEPARATOR . "command" . DIRECTORY_SEPARATOR . ucfirst($option[0]) . ".php";

    //récupération des arguments de la commande pour passage en paramètre de la méthode
    $arguments = array();
    if (isset($argv[2])) {
        for ($i = 2; $i < count($argv); $i++) {
            $arguments[] = $argv[$i];
        }
    }

    if (is_file($command_file)) {
        $class = "\MVC\Command\\" . ucfirst($option[0]);
        $static_method = $option[1];
        $errors = $class::$static_method(...$arguments);

        if ($errors !== null) {
            \MVC\Classe\Logger::logCommandErrors($errors);
        }
    } else {
        $command_file = dirname(__FILE__) . DIRECTORY_SEPARATOR . "command" . DIRECTORY_SEPARATOR . "App" . DIRECTORY_SEPARATOR. ucfirst($option[0]) . ".php";
        if (is_file($command_file)) {
            $class = "\MVC\Command\\App\\" . ucfirst($option[0]);
            $static_method = $option[1];
            $errors = $class::$static_method(...$arguments);

            if ($errors !== null) {
                \MVC\Classe\Logger::logCommandErrors($errors);
            }
        }else {
            $command_file = dirname(__FILE__) . DIRECTORY_SEPARATOR . "command" . DIRECTORY_SEPARATOR . "Sand" . DIRECTORY_SEPARATOR. ucfirst($option[0]) . ".php";
            if (is_file($command_file)) {
                $class = "\MVC\Command\\Sand\\" . ucfirst($option[0]);
                $static_method = $option[1];
                $errors = $class::$static_method(...$arguments);

                if ($errors !== null) {
                    \MVC\Classe\Logger::logCommandErrors($errors);
                }
            }else {
                print "Command not found !\n";
            }
        }
    }
} else {
    print "No command was specified !\n";
}
