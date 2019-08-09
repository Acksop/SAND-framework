<?php
namespace MVC\Module\Setup;

class Syf43 {

    /**
     * @param none
     * @return modular_symfony_web
     */

    public function load($name) {
        ob_start();
        require( MODULES_PATH . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "index.php" );
        $modularApp = ob_get_clean();
        echo $modularApp;
    }

    public static function twigLoader($name){
        require MODULES_PATH . DIRECTORY_SEPARATOR . "setup" . DIRECTORY_SEPARATOR . $name . ".twig.class.php";
    }
}