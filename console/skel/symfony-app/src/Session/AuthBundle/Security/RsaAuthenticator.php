<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CasAuthenticator
 *
 * @author belhadjali
 */

namespace App\Session\AuthBundle\Security;

use App\Session\AuthBundle\Security\Interfaces\AuthInterface;
use App\Session\AuthBundle\Events\OnAuthenticationFailureEvent;
use App\Session\AuthBundle\Events\OnAuthenticationSuccessEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Guard\AuthenticatorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RsaAuthenticator extends AbstractFormLoginAuthenticator implements LogoutSuccessHandlerInterface, AuthenticatorInterface {

    private $authService;
    private $urlGenerator;
    private $dispatcher;

    public function __construct(AuthInterface $authService, Array $config, UrlGeneratorInterface $urlGenerator, EventDispatcherInterface $dispatcher) {
        $this->urlGenerator = $urlGenerator;
        //Récupérer le service déaclaré authService
        $this->authService = $authService;
        $this->config = $config;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Called on every request. Return whatever credentials you want,
     * or null to stop authentication.
     */
    public function getCredentials(Request $request) {
        if (!isset($_SERVER['HTTP_CT_REMOTE_USER']) || empty($_SERVER['HTTP_CT_REMOTE_USER'])) {
            $this->returnRequest = $request->getUri();
            throw new \LogicException("Impossible de continuer sous RSA : L'entête HTTP_CT_REMOTE_USER est vide ou manquante");
        }
        return true;
    }

    public function getUser($credentials, UserProviderInterface $userProvider) {
        $username = $_SERVER['HTTP_CT_REMOTE_USER'];
        $user = $userProvider->loadUserByUsername($username);
        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user) {
        $this->authService->ctrlAccess($user); 
        // check credentials - e.g. make sure the password is valid
        // no credential check is needed in this case
        // return true to cause authentication success
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey) {
        
        $event = new OnAuthenticationSuccessEvent($request, $token, $providerKey);
        $this->dispatcher->dispatch(OnAuthenticationSuccessEvent::NAME, $event);
        
        $this->authService->onSuccess($token);
        // on success, let the request continue
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {
         
        $event = new OnAuthenticationFailureEvent($request, $exception);
        $this->dispatcher->dispatch(OnAuthenticationFailureEvent::NAME, $event);

        return $this->authService->onAuthenticationFailure($exception);
    }

    /**
     * Called when authentication is needed, but it's not sent
     */
//    public function start(Request $request, AuthenticationException $authException = null) {
//        $url = $this->router->generate('login');
//        return new RedirectResponse($url);
//    }

    public function supportsRememberMe() {
        return false;
    }

    //implementation LogoutSuccessHandlerInterface
    public function onLogoutSuccess(Request $request) {
        $redirect = (isset($_SERVER['HTTP_FREDUURLRETOUR'])) ? $_SERVER['HTTP_FREDUURLRETOUR'] : $this->config['rsa']['logout_url'];
        return new RedirectResponse($redirect);
    }

    protected function getLoginUrl() {
        $return_request = urlencode($this->returnRequest);
        $params = "?CT_ORIG_URL=" . $return_request;
        return $this->config['rsa']['login_url'] . $params;
    }

    public function supports(Request $request) {
        if (isset($this->config['environment']) && $this->config['environment'] == "test") {
            return false;
        }
        return true;
    }
}