<?php

namespace MVC\Classe;


class Dumper{

    public static function dump($var){
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }

}