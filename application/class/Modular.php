<?php

namespace MVC\Classe;

class Modular{

    private $app = "";

    public function  __construct($appName){
        $this->app = $appName;
    }

    public function getAppName(){
        return $this->app;
    }

    public function load(){
        require MODULES_PATH . DIRECTORY_SEPARATOR . $this->getAppName() . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "index.php";
    }
}