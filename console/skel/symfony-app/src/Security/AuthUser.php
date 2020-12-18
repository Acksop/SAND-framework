<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class AuthUser implements UserInterface
{
    private $id;
    private $username;
    private $status;
    private $type;
    private $salt;
    private $credentials;
    private $roles = [];

    public function __construct($id, $username, $credentials, array $roles = [])
    {
        $this->username = $username;
        $this->id = $id;
        $this->credentials = $credentials;
        $this->roles = $roles;
        $this->salt = sha1(microtime(true));
    }

    public function getRoles()
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        if ($this->getId() == 1587184) {
            $roles[] = 'ROLE_ADMIN';
        }
        return array_unique($roles);
    }

    public function setRoles($roles)
    {
        return $this->roles = $roles;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getUser()
    {
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCredentials()
    {
        return $this->credentials;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getPassword()
    {
        // TODO: Implement getPassword() method.
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof AuthUser) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }
        if ($this->id !== $user->getId()) {
            return false;
        }
        if ($this->type !== $user->getType()) {
            return false;
        }
        if ($this->status !== $user->getStatus()) {
            return false;
        }

        return true;
    }
}
