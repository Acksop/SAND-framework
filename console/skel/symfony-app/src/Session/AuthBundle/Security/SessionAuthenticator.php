<?php


namespace App\Session\AuthBundle\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class SessionAuthenticator extends AbstractGuardAuthenticator
{
    public $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request)
    {
        if (isset($_SESSION['id_utilisateur'])) {
            return true;
        } else {
            return true;
        }
    }

    /**
     * Called on every request. Return whatever credentials you want to
     * be passed to getUser() as $credentials.
     */
    public function getCredentials(Request $request)
    {
        return "X-AUTH-TOKEN-SESSION-API";
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (!isset($_SESSION['id_utilisateur'])) {
            $user = new \App\Classes\AuthUser('', '', '', '', '', ['ROLE_USER']);
        } else {
            $user = new \App\Classes\AuthUser($_SESSION['id_utilisateur'], $_SESSION['identifiant'], $_SESSION['status_compte'], $_SESSION['type_compte'], $credentials, ['ROLE_USER', 'ROLE_USER_CONNECTED']);
        }

        // if a User is returned, checkCredentials() is called
        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // Check credentials - e.g. make sure the password is valid.
        // In case of an API token, no credential check is needed.

        // Return `true` to cause authentication success
        if ($user->getCredentials() === $credentials) {
            return true;
        } else {
            return false;
        }
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // on success, let the request continue
        //return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

//        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        $url = $this->router->generate('unauthorized');
        return new RedirectResponse($url);
    }

    /**
     * Called when authentication is needed, but it's not sent
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            // you might translate this message
            'message' => 'Authentication Required'
        ];

        //return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);

        $url = $this->router->generate('unauthorized');
        return new RedirectResponse($url);
    }

    public function supportsRememberMe()
    {
        return false;
    }

    public function onLogoutSuccess(Request $request)
    {
        //$homepage = $this->config["homepage"];
        //return \phpCAS::logoutWithRedirectService($this->urlGenerator->generate($homepage, array(), UrlGeneratorInterface::ABSOLUTE_URL));
        header('Location: /index.php');
        return ;
    }
}
