<?php

namespace MVC\Classe;

class ModularApp{
    public static function load($name){
        require MODULES_PATH . DIRECTORY_SEPARATOR . "setup" . DIRECTORY_SEPARATOR . $name . ".twig.class.php";
    }
}