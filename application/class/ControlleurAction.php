<?php


namespace MVC\Classe;

class ControlleurAction
{
    public static function inserer($action, $data = array())
    {
        $action = explode('.', $action);
        $class = ucfirst($action[0]) . "Action";
        //echo $class;
        if (is_file(ACTION_PATH . DIRECTORY_SEPARATOR . $class . ".php")) {
            require_once ACTION_PATH . DIRECTORY_SEPARATOR . $class . ".php";
            $slot = new $class();
            if (isset($action[1])) {
                $method = $action[1];
                return $slot->$method(...$data);
            } else {
                return $slot->default($data);
            }
        } else {
            /*HandleError*/
        }
    }
}
