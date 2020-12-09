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
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Guard\AuthenticatorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CasAuthenticator extends AbstractFormLoginAuthenticator implements LogoutSuccessHandlerInterface, AuthenticatorInterface {

    private $authService;
    private $urlGenerator;

    public function __construct(AuthInterface $authService, Array $config, UrlGeneratorInterface $urlGenerator, EventDispatcherInterface $dispatcher) {
        $this->urlGenerator = $urlGenerator;
        //Récupérer le service déaclaré authService
        $this->authService = $authService;
        $this->config = $config;
        $this->dispatcher = $dispatcher;

        if (php_sapi_name() !== 'cli') {
            \phpCAS::client(CAS_VERSION_2_0, $this->config['cas']["hostname"], $this->config['cas']["port"], $this->config['cas']["uri"]);
            \phpCAS::setNoCasServerValidation();
            \phpCAS::forceAuthentication();
        }
    }

    /**
     * Called on every request. Return whatever credentials you want,
     * or null to stop authentication.
     */
    public function getCredentials(Request $request) {
        return true;
    }

    public function getUser($credentials, UserProviderInterface $userProvider) {
        $username = \phpCAS::getUser();
        $user = $userProvider->loadUserByUsername($username);
        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user) {
        return $this->authService->ctrlAccess($user);
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
        $homepage = $this->config["homepage"];
        return \phpCAS::logoutWithRedirectService($this->urlGenerator->generate($homepage, array(), UrlGeneratorInterface::ABSOLUTE_URL));
    }

    protected function getLoginUrl() {
        return \phpCas::getServerLoginURL();
    }

    public function supports(Request $request) {
        if (isset($this->config['environment']) && $this->config['environment'] == "test") {
            return false;
        }
        return true;
    }

}
