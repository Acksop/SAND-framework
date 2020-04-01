<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AuthUser
 *
 * @author belhadjali
 */

namespace App\Session\AuthBundle\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

class AuthUser implements UserInterface, EquatableInterface {

    private $username;
    private $salt;
    private $roles = [];

    public function __construct($username, $salt, array $roles = []) {
        $this->username = $username;
        $this->salt = $salt;
        $this->roles = $roles;
    }

    public function getRoles() {
        return $this->roles;
    }

    public function setRoles($roles) {
        return $this->roles = $roles;
    }
    
    public function addRole($role) {
        return $this->roles[] = $role;
    }

    public function getPassword() {
        return;
    }

    public function getSalt() {
        return $this->salt;
    }

    public function getUsername() {
        return $this->username;
    }

    public function eraseCredentials() {
        
    }

    public function isEqualTo(UserInterface $user) {
        if (!$user instanceof AuthUser) {
            return false;
        }

        if ($this->salt !== $user->getSalt()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }

}
