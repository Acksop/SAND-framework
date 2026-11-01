<?php

namespace SAND\Object;

class Environment
{
    /**
     * Mode débogage : vrai seulement si DEBUG vaut le booléen true
     * (une chaîne comme "false" ou "1" ne l'active pas).
     */
    public static function debug(): bool
    {
        return defined('DEBUG') && DEBUG === true;
    }

    public static  function getColorMenuFromEnv()
    {
        switch(ENV){
            case 'TEST':
                return 'red';
                break;
            case 'PREPROD':
                return 'blue';
                break;
            case 'PROD':
                return 'black';
                break;
            default:
                return 'green';
        }
    }
    public static  function getTextEnvironment()
    {
        switch(ENV){
            case 'TEST':
                return '<li style="background-color: '.self::getColorMenuFromEnv().'"><b>TESTING ENVIRONMENT</b><li>';
                break;
            case 'PREPROD':
                return '<li style="background-color: '.self::getColorMenuFromEnv().'"><b>PREPROD ENVIRONMENT</b><li>';
                break;
            case 'PROD':
                return '';
                break;
            default:
                return '<li style="background-color: '.self::getColorMenuFromEnv().'"><b>DEVEL ENVIRONMENT</b><li>';
        }
    }

}