#!/usr/bin/php
<?php
//var_dump($argv);

if(isset($argv[1])) {
    $option = explode(':', $argv[1]);
    $command_file = dirname(__FILE__) . DIRECTORY_SEPARATOR . "command" . DIRECTORY_SEPARATOR . $option[0] . ".class.php";
    if (is_file($command_file)) {
        require $command_file;
        $class = $option[0];
        $static_method = $option[1];
        $class::$static_method();
    } else {
        print "Command not found !\n";
    }
}else{
    print "No command was specified !\n";
}