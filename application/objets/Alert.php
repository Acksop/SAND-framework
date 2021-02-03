<?php

namespace MVC\Object;

class Alert
{

    public static  function addAlert($title,$message,$type)
    {
        $alert = array(
            'title' => $title,
            'message' => $message,
            'type' => $type
        );

        $_SESSION['alerts'][] = $alert;
    }

    public static  function remove(){
        $_SESSION['alerts'] = array();
    }

}