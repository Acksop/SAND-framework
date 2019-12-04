<?php

namespace MVC\Classe;


class Dumper{

    public static function dump($var){
        echo "<pre>";
        if (is_bool($var)) {
            echo ($var) ? "true" : "false";
        } else {
            print_r($var);
        }
        echo "</pre>";
    }

}