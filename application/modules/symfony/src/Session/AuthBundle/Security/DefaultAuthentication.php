<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Session\AuthBundle\Security;

use App\Session\AuthBundle\Security\Auth\Authentication;
use App\Session\AuthBundle\Security\Auth\User;
use App\Session\AuthBundle\Security\Auth\UserProvider;
use App\Session\AuthBundle\Security\Interfaces\AuthInterface;
use App\Session\AuthBundle\Security\Abstracts\AuthFinal;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Provider\UserAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;
use Symfony\Component\Security\Core\User\UserChecker;

/**
 * Description of DefaultAuthentication
 *
 * @author belhadjali
 */
class DefaultAuthentication extends AuthFinal implements AuthInterface {

    /**
     * @var string Uniquely identifies the secured area
     */
    private $providerKey;

    public function authentificate($token)
    {
        $username = $this->ai->getUsername();
        $password = "";

        $unauthenticatedToken = new UsernamePasswordToken(
            $username,
            $password,
            'secured_area'
        );

        $userProvider = new UserProvider( new Authentication(),
            array('user_entity' => 'App\Session\AuthBundle\Security\Auth\User',
                'type_auth' => 'Cas'));
        $userChecker = new UserChecker();

        $defaultEncoder = new MessageDigestPasswordEncoder('sha512', true, 5000);

        $encoders = [
            User::class       => $defaultEncoder,
        ];

        $encoderFactory = new EncoderFactory($encoders);

        $provider = new DaoAuthenticationProvider(
            $userProvider,
            $userChecker,
            'secured_area',
            $encoderFactory);


        $authenticatedToken = $provider
            ->authenticate($unauthenticatedToken);

        //$tokenStorage = new TokenStorage();

        //$tokenStorage->setToken($authenticatedToken);
    }

    public function getRoles() {
        return [];
    }

    public function onSuccess($token) {

        //dump($this->ai);
        //die('success');

        //$this->authentificate($token);

        $token->setAttribute("username", $this->ai->getUsername());
        $token->setAttribute("complet_name", $this->ai->getCompletName());
        $token->setAttribute("mail", $this->ai->getMail());
        $token->setAttribute("FreDuRne", $this->ai->getFreDuRne());
        
        return;
    }

    public function ctrlAccess(\Symfony\Component\Security\Core\User\UserInterface $user) {
        //die('ctrlAccess');
        return true;
    }

    public function getUser($username) {
        return parent::getUser($username);
    }
}
