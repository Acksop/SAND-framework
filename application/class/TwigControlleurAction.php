<?php


namespace MVC\Classe;


/**
 * Class MyActionTwigExtension
 *  with call {{ static_call("AppBundle\Entity\YourEntity", "GetSomething", ["var1", "var2"]) }}
 *  other {{ action("AppBundle\Entity\YourEntity", "GetSomething", ["var1", "var2"]) }}
 *
 * @package MVC\Classe
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

    /**
     * Fonction Twig « action » : même comportement que ControlleurAction::inserer().
     */
    public static function inserer($action, $data = array())
    {
        return ControlleurAction::inserer($action, $data);
    }
    public static function getClassMethodStatic($class, $method, $args = array())
    {
        return $class::$method(...$args);
    }
}