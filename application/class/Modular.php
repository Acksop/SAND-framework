<?php

namespace MVC\Classe;

class Modular{

    private $app = "";
    private $subapp_dir = "";
    private $subfile = "index.php";

    public function  __construct($appName,$type = 'symfony',$options = array()){

        //Dumper::dump($options);die();

        $this->app = $appName;
        switch ($type) {
            case "symfony":
                break;
            case "wordpress":
                if(isset($options[0])) {
                    switch ($options[0]) {
                        case 'wp-admin':
                            $this->subapp_dir = DIRECTORY_SEPARATOR . $options[0];
                            if(isset($options[1])) {
                                switch ($options[1]) {
                                    case 'setup-config.php':
                                    case 'install.php':
                                        $this->subfile = $options[1];
                                        break;
                                    default:
                                        if(preg_match('#(.)*\.php#',$options[1])){
                                            $this->subfile = $options[1];
                                        }
                                }
                            }
                            break;
                        case 'wp-login.php':
                            $this->subfile = $options[0];
                            break;
                        default:
                    }
                }
                break;
            case "prestashop":
                if(isset($options[0])) {
                    switch ($options[0]) {
                        case 'admin-dev':
                        case 'install-dev':
                            $this->subapp_dir = DIRECTORY_SEPARATOR . $options[0];
                            break;
                        default:
                    }
                }

                break;
        }
    }

    public function getAppName(){
        return $this->app;
    }

    public function load($type = "symfony"){
        switch ($type) {
            case "symfony":
            require MODULES_PATH . DIRECTORY_SEPARATOR . $this->getAppName() . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "index.php";
            break;
            case "wordpress":
                require MODULES_PATH . DIRECTORY_SEPARATOR . $this->getAppName() . $this->subapp_dir . DIRECTORY_SEPARATOR . $this->subfile;
                break;
            case "prestashop":
                require MODULES_PATH . DIRECTORY_SEPARATOR . $this->getAppName() . $this->subapp_dir . DIRECTORY_SEPARATOR . "index.php";
                break;
        }
    }
}