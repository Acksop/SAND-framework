<?php

namespace MVC\Classe;

class Modular
{
    private $app = "";
    private $subapp_dir = "";
    private $subfile = "index.php";

    public function __construct($appName, $type = 'symfony', $options = array())
    {

        //Dumper::dump($options);die();

        $this->app = $appName;
        switch ($type) {
            case "gitlist":
                break;
            case "symfony":
                break;
            case "laravel":
                break;
            case "wordpress":
                if (isset($options[0])) {
                    switch ($options[0]) {
                        case 'wp-admin':
                            $this->subapp_dir = DIRECTORY_SEPARATOR . $options[0];
                            if (isset($options[1])) {
                                switch ($options[1]) {
                                    case 'setup-config.php':
                                    case 'install.php':
                                        $this->subfile = $options[1];
                                        break;
                                    default:
                                        if (preg_match('#(.)*\.php#', $options[1])) {
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
                if (isset($options[0])) {
                    switch ($options[0]) {
                        case 'admin-dev':
                        case 'install-dev':
                            $this->subapp_dir = DIRECTORY_SEPARATOR . $options[0];
                            break;
                        default:
                    }
                }
                break;
            case "phplist":
                if (isset($options[0])) {
                    switch ($options[0]) {
                        case 'admin':
                            $this->subapp_dir = DIRECTORY_SEPARATOR . 'public_html' . DIRECTORY_SEPARATOR . 'lists'. DIRECTORY_SEPARATOR . $options[0] ;
                            break;
                        default:
                    }
                } else {
                    $this->subapp_dir = DIRECTORY_SEPARATOR . 'public_html' . DIRECTORY_SEPARATOR . 'lists';
                }
                break;
            case "wanewsletter":
                $this->subfile = "install.php";
                if (isset($options[0])) {
                    switch ($options[0]) {
                        case 'admin':
                            $this->subapp_dir = DIRECTORY_SEPARATOR . $options[0] ;
                            $this->subfile = "index.php";
                            break;
                        default:
                            $this->subapp_dir = DIRECTORY_SEPARATOR . $options[0] ;
                    }
                }
                break;
            case "phpmynewsletter":
                break;
        }
    }

    public function getAppName()
    {
        return $this->app;
    }

    public function load($type = "symfony")
    {
        global $buffer_sand;
        switch ($type) {
            case "gitlist":
                ob_start(array('\\MVC\\Classe\\Modular', 'rappel'),0,PHP_OUTPUT_HANDLER_STDFLAGS ^ PHP_OUTPUT_HANDLER_FLUSHABLE);
                $level = ob_get_level();
                require MODULES_PATH . DIRECTORY_SEPARATOR . $this->getAppName() . DIRECTORY_SEPARATOR . "index.php";
                break;
            case "symfony":
                ob_start(array('\\MVC\\Classe\\Modular', 'rappel'),0,PHP_OUTPUT_HANDLER_STDFLAGS ^ PHP_OUTPUT_HANDLER_FLUSHABLE);
                $level = ob_get_level();
                $path = MODULES_PATH . DIRECTORY_SEPARATOR . $this->getAppName() . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "index.php";
                return require MODULES_PATH . DIRECTORY_SEPARATOR . $this->getAppName() . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "index.php";
                break;
            case "laravel":
                ob_start(array('\\MVC\\Classe\\Modular', 'rappel'),0,PHP_OUTPUT_HANDLER_STDFLAGS ^ PHP_OUTPUT_HANDLER_FLUSHABLE);
                $level = ob_get_level();
                require MODULES_PATH . DIRECTORY_SEPARATOR . $this->getAppName() . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "index.php";
                break;
            case "wordpress":
                ob_start(array('\\MVC\\Classe\\Modular', 'rappel'),0,PHP_OUTPUT_HANDLER_STDFLAGS ^ PHP_OUTPUT_HANDLER_FLUSHABLE);
                $level = ob_get_level();
                require MODULES_PATH . DIRECTORY_SEPARATOR . $this->getAppName() . $this->subapp_dir . DIRECTORY_SEPARATOR . $this->subfile;
                break;
            case "prestashop":
                ob_start(array('\\MVC\\Classe\\Modular', 'rappel'),0,PHP_OUTPUT_HANDLER_STDFLAGS ^ PHP_OUTPUT_HANDLER_FLUSHABLE);
                $level = ob_get_level();
                require MODULES_PATH . DIRECTORY_SEPARATOR . $this->getAppName() . $this->subapp_dir . DIRECTORY_SEPARATOR . "index.php";
                break;
            case "phplist":
                ob_start(array('\\MVC\\Classe\\Modular', 'rappel'),0,PHP_OUTPUT_HANDLER_STDFLAGS ^ PHP_OUTPUT_HANDLER_FLUSHABLE);
                $level = ob_get_level();
                require MODULES_PATH . DIRECTORY_SEPARATOR . $this->getAppName() . $this->subapp_dir . DIRECTORY_SEPARATOR . "index.php";
                break;
            case "wanewsletter":
                ob_start(array('\\MVC\\Classe\\Modular', 'rappel'),0,PHP_OUTPUT_HANDLER_STDFLAGS ^ PHP_OUTPUT_HANDLER_FLUSHABLE);
                $level = ob_get_level();
                require MODULES_PATH . DIRECTORY_SEPARATOR . $this->getAppName() . $this->subapp_dir . DIRECTORY_SEPARATOR . $this->subfile;
                break;
            case "phpmynewsletter":
                ob_start(array('\\MVC\\Classe\\Modular', 'rappel'),0,PHP_OUTPUT_HANDLER_STDFLAGS ^ PHP_OUTPUT_HANDLER_FLUSHABLE);
                $level = ob_get_level();
                require MODULES_PATH . DIRECTORY_SEPARATOR . $this->getAppName() . $this->subapp_dir . DIRECTORY_SEPARATOR . $this->subfile;
                break;
        }
        $buffer_sand = "";
        if (ob_get_level() == $level) {
            if (ob_get_level() > 1) {
                $buffer_sand = ob_get_contents();
                ob_end_clean();
            } else {
                $buffer_sand = "BUFFER CLOSURE BY FRAMEWORK !<br/>".$buffer_sand;
            }
        }else{
            $final_level = ob_get_level();
            $buffer_sand = "BUFFER LEVEL $level CLOSURE $final_level BY FRAMEWORK !<br/>".$buffer_sand;
        }
        return $buffer_sand;
    }
    public static function rappel($buffer)
        {
            global $buffer_sand;
            $buffer_sand = $buffer;
            // remplace toutes les pommes par des carottes
            return $buffer;
        }
}
