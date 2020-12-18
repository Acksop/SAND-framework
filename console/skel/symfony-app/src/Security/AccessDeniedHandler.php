<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Twig\Environment;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public $twig;
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        $content = $this->twig->render(
            'default/unauthorized.html.twig',
            array()
        );
        $response = new Response($content, Response::HTTP_FORBIDDEN);
        return $response;
    }
}
