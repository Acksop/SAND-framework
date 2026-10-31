<?php

namespace MVC\Classe;

class ControlleurAction
{
    /**
     * Appelle une action depuis un gabarit : 'nom' appelle NomAction::default(),
     * 'nom.methode' appelle NomAction::methode(), avec $data comme arguments.
     *
     * La classe est chargée depuis ACTION_PATH, sauf si elle est déjà définie.
     */
    public static function inserer($action, $data = array())
    {
        //on extrait la classe d'appel de l'action
        $action = explode('.', $action);
        $class = ucfirst($action[0]) . "Action";
        $fichier = ACTION_PATH . DIRECTORY_SEPARATOR . $class . ".php";
        //TODO: use try ... catch with \MVC\Classe\Logger to log error
        if (!class_exists($class, false) && is_file($fichier)) {
            require_once $fichier;
        }
        if (class_exists($class, false)) {
            //On charge la classe Action de façon réflextive
            $slot = new $class();
            //méthode pointée (« nom.methode ») si fournie, sinon la méthode default()
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
