<?php


namespace SAND\Object;

class Asynchronous
{

    public static function declare()
    {
        $_SESSION['css'] = "";
        $_SESSION['javascript'] = "";
    }

    public static function addCss($code)
    {
        $_SESSION['css'] .= "\n";
        $_SESSION['css'] .= $code;
    }

    public static function addJs($code)
    {
        $_SESSION['javascript'] .= "\n";
        $_SESSION['javascript'] .= $code;
    }

    public static function printCss()
    {
        echo $_SESSION['css'];
    }

    public static function printJs()
    {
        echo $_SESSION['javascript'];
    }
}
