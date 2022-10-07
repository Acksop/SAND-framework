<?php


namespace SAND\Classe;

class ControlleurAction
{
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
}
