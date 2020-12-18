<?php

namespace MVC\Command;

class Symfony
{

    static public function help(){
        print "explaination of the command\n\n";
    }

    static public function stabilize(){
        print "stabilize symfony module...\n\n";
        $symfony_module = shell_exec('sudo cp '.CONSOLE_PATH.'/skel/symfony '.VENDOR_PATH.' -Rf');
    }

}