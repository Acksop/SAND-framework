<?php

namespace App\Events;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class OnAuthenticationSuccessEvent extends Event
{
    const NAME = "session_auth.event.on_authentication_success";

    public function __construct(Request $request, TokenInterface $token, $providerKey)
    {
        $this->request = $request;
        $this->token = $token;
        $this->providerKey = $providerKey;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getToken()
    {
        return $this->exception;
    }

    public function getProviderKey()
    {
        return $this->providerKey;
    }
}
