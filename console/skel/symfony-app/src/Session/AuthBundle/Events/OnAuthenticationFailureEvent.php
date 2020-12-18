<?php

namespace App\Session\AuthBundle\Events;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class OnAuthenticationFailureEvent extends Event
{
    const NAME = "session_auth.event.on_authentication_failure";

    public function __construct(Request $request, AuthenticationException $exception)
    {
        $this->request = $request;
        $this->exception = $exception;
        $this->response = new Response($exception->getMessage(), Response::HTTP_FORBIDDEN);
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getException()
    {
        return $this->exception;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }
}
