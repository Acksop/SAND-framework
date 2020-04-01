<?php

namespace App\Session\AuthBundle\Events;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;

class CheckCredentialsEvent extends Event {

    const NAME = "besancon_auth.event.check_credentials";
    private $access = true;
    
    public function __construct($credentials, UserInterface $user_interface) {
        $this->credentials = $credentials;
        $this->user_interface = $user_interface;
    }

    public function getCredentials() {
        return $this->credentials;
    }

    public function getUserInterface() {
        return $this->user_interface;
    }

    public function getAccess() {
        return $this->access;
    }
    public function setAccess($access) {
        $this->access = $access;
        return $this;
    }

}
