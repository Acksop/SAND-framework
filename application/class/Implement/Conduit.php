<?php


namespace MVC\Classe\Implement;

class Conduit extends Action
{
    public function initialize($var)
    {
        //Export variable from conduit
        foreach ($var as $key => $value) {
            if ($key != "controller") {
                if ($key != "_route") {
                    $this->$key = $value;
                }
            }
        }
        return;
    }
}
