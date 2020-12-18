<?php

namespace App\Session\AuthBundle\Utils;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Controls
 *
 * @author belhadjali
 */
class Config
{
    public static function getDeclaredType($config)
    {
        if (!isset($config['type_auth'])) {
            throw new \LogicException('Paramètre type_auth manquant');
        }

        $type = $config['type_auth'];

        self::typeIsSupported($type);

        return self::formatType($type);
    }

    public static function typeIsSupported($type)
    {
        $type_auth = self::formatType($type);
        if (!in_array($type_auth, ['Rsa', 'Cas'])) {
            throw new \LogicException('Seuls Cas et Rsa sont supportés pour le moment');
        }
        return true;
    }

    public static function formatType($type)
    {
        return ucfirst(strtolower($type));
    }
}
