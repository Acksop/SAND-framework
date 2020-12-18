<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Session\AuthBundle\Security;

use App\Session\AuthBundle\Security\Interfaces\AuthInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Session\AuthBundle\Utils\Config;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AuthenticatorFactory
{
    public static function getAuthenticator(AuthInterface $authService, array $config, UrlGeneratorInterface $urlGenerator, EventDispatcherInterface $dispatcher)
    {
        $type_auth = Config::getDeclaredType($config);

        $authenticator_class = "App\Session\AuthBundle\Security\\" . $type_auth . "Authenticator";
        $authenticator = new $authenticator_class($authService, $config, $urlGenerator, $dispatcher);

        return $authenticator;
    }
}
