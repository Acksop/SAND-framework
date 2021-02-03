<?php

namespace MVC\Object;

class XssToken
{

    public static function getNew($title,$message,$type)
    {
        $_SESSION['xss_token'] = generateUniqueToken('xss',25);
    }

    public static function remove(){
        $_SESSION['xss_token'] = '';
    }

    public static function generateUniqueToken($prefix = 'xss_', $length = 13){
            // uniqid gives 13 chars, but you could adjust it to your needs.
            if (function_exists("random_bytes")) {
                $bytes = random_bytes(ceil($length / 2));
            } elseif (function_exists("openssl_random_pseudo_bytes")) {
                $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
            } else {
                throw new Exception("no cryptographically secure random function available");
            }
            return $prefix . substr(bin2hex($bytes), 0, $length);
    }

}