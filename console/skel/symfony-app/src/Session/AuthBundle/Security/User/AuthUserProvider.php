<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Session\AuthBundle\Security\User;

use App\Besancon\AuthBundle\Security\Interfaces\AuthInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class AuthUserProvider implements UserProviderInterface
{
    public function __construct(AuthInterface $authService, array $config)
    {
        $this->config = $config;

        if (!is_null($this->config['user_entity'])) {
            $this->entity_user = "\\".$this->config['user_entity'];
        } else {
            $this->entity_user = "App\Session\AuthBundle\Security\User\AuthUser";
        }
        $this->authService = $authService;
    }

    public function loadUserByUsername($username)
    {
        $entity_user = $this->entity_user;

        return $this->authService->getUser($username);
    }

    private function _ctrlInstanceUser(UserInterface $user)
    {
        $entity_user = $this->entity_user;

        if (!$user instanceof $entity_user) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        $user = $this->_ctrlInstanceUser($user);

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        $entity_user = $this->entity_user;
        return $this->entity_class === $class;
    }
}
