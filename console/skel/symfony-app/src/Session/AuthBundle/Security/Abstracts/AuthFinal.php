<?php
/**
 * Abstract  AuthAbstract
 *
 * @package Besancon\AuthBundle\Security\Abstracts
 * @author  Amine BEL HADJ ALI <amine.belhadjali@ac-besancon.fr>
 *
 * @method setGetterAttributes()
 * @method getUser()
 * @abstract
 */

namespace App\Session\AuthBundle\Security\Abstracts;

use App\Session\AuthBundle\Utils\Config;
use Symfony\Component\HttpFoundation\Response;

class AuthFinal extends AuthAbstract
{

    /**
     * Intancie le getters en fonction de la configuration
     *
     * Si dans la config le paramètre type_auth est défini à CAS alors
     * intanciation du getter CasAttributes,
     * Si la valeur est à RSA alors instanciation du getter RsaAttributes
     *
     * Cette instance peut ensuite être utilisée dans le service d'authentification
     * qui héritera de AuthAbstract, en passant faisant appel à $this->ai
     *
     * @final
     * @param $config
     *          configuration du Bundle
     * @return void
     *
     * */
    public function setGetterAttributes($config)
    {
        $type_auth = Config::getDeclaredType($config);
        //dump('calls');
        $getters = "\App\Session\AuthBundle\Security\Getters\\" . $type_auth . "Attributes";
        $ai = new $getters();
        $this->ai = $ai;
        //dump($this->ai);
    }

    /**
     * Comportement par défaut lorsque l'authentification n'aboutie pas (accès non autorisé)
     *
     * il est possible de redéfinir cette méthode
     * mais elle doit renvoyer une réponse HTTP exemple:
     *     - Symfony\Component\HttpFoundation\Response
     *     - Symfony\Component\HttpFoundation\JsonResponse
     *
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException $exception
     *          Exception généré par le guard
     * @return Symfony\Component\HttpFoundation\Response
     *
     * */
    public function onAuthenticationFailure(\Symfony\Component\Security\Core\Exception\AuthenticationException $exception)
    {
        return new Response($exception->getMessage(), Response::HTTP_FORBIDDEN);
    }

    /**
     * Renvoie une instance de l'utilisateur
     *
     * Ceci correspond à la class Besancon\AuthBundle\Security\User\AuthUser,
     * il est possible de redéfinir cette méthode
     * mais elle doit renvoyer un objet implementant Symfony\Component\Security\Core\User\UserInterface
     *
     * Est utilisé dans le userprovider par défaut Besancon\AuthBundle\Security\User\AuthUserProvider
     *
     * @see \Symfony\Component\Security\Core\User\UserInterface
     * @see \Besancon\AuthBundle\Security\User\AuthUserProvider
     *
     * @param string $username
     *               Identifiant de l'utilisateur
     * @return \Symfony\Component\Security\Core\User\UserInterface
     *
     */
    public function getUser($username)
    {
        $roles_service = $this->getRoles();
        $roles = (!is_null($roles_service) && is_array($roles_service)) ? $roles_service : array();
        $user = new \App\Besancon\AuthBundle\Security\User\AuthUser($username, md5("8sQaz87dPPsdanYakq86f" . $username), $roles);

        return $user;
    }
}
