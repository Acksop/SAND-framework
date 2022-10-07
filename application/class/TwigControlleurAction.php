<?php


namespace SAND\Classe;


/**
 * Class MyActionTwigExtension
 *  with call {{ static_call("AppBundle\Entity\YourEntity", "GetSomething", ["var1", "var2"]) }}
 *  other {{ action("AppBundle\Entity\YourEntity", "GetSomething", ["var1", "var2"]) }}
 *
 * @package SAND\Classe
 */

class TwigControlleurAction extends \Twig\Extension\AbstractExtension
{
    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return \Twig\TwigFunction[]
     */
    public function getFunctions() {
        return array(
            new \Twig\TwigFunction("action", array($this, "inserer")),
            new \Twig\TwigFunction("call", array($this, "getClassMethodStatic")),
            new \Twig\TwigFunction("session", array($this, "afficheSession")),
            new \Twig\TwigFunction("server", array($this, "afficheServer"))
        );
    }

    public function afficheServer($key_var = ''){
        if($key_var !== ""){
            if(isset($_SERVER[$key_var])) {
                return $_SERVER[$key_var];
            }else{
                return null;
            }
        }else {
            return $_SERVER;
        }
        return null;
    }


    public function afficheSession($key_var = ''){
        if($key_var !== ""){
            if(isset($_SESSION[$key_var])) {
                return $_SESSION[$key_var];
            }else{
                return null;
            }
        }else {
            return $_SESSION;
        }
        return null;
    }

    public static function inserer($action, $data = array())
    {
        //on extrait la classe d'appel de l'action
        $action = explode('.', $action);
        $class = ucfirst($action[0]) . "Action";
        //TODO: use try ... catch with \MVC\Classe\Logger to log error
        if (is_file(ACTION_PATH . DIRECTORY_SEPARATOR . $class . ".php")) {
            require_once ACTION_PATH . DIRECTORY_SEPARATOR . $class . ".php";
            //On charge la classe Action de façon réflextive
            $slot = new $class();
            //si l'action passé en parametre est fournit avec une methode pointée on charge celle demandée sinon on charge celle par defaut
            if (isset($action[1])) {
                $method = $action[1];
                //On appel la méthode de la classe action de manière reflextive
                return $slot->$method(...$data);
            } else {
                return $slot->default(...$data);
            }
        } else {
            /*HandleError*/
        }
    }
    public static function getClassMethodStatic($class, $method, $args = array())
    {
        return $class::$method(...$args);
    }
}