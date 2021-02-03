<?php

namespace MVC\Object;

class History
{

    public static function setPagePrecedente(){
        if(!isset($_SESSION['pagePrecedente'])){
            $_SESSION['pagePrecedente'] = '';
            $_SESSION['pageActuelle'] = $_SERVER['REQUEST_URI'];
        }else{
            $_SESSION['pagePrecedente'] = $_SESSION['pageActuelle'];
            $_SESSION['pageActuelle'] = $_SERVER['REQUEST_URI'];
        }
    }

    public static function getPagePrecedente(){
        return $_SESSION['pagePrecedente'];
    }

}