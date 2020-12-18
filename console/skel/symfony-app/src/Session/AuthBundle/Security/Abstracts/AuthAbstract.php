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

abstract class AuthAbstract
{

    /**
     * @var App\Besancon\AuthBundle\Security\Interfaces\AttributesInterface $ai Instance de CasAttributes ou RsaAttributes
     */
    protected $ai;

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
    abstract public function setGetterAttributes($config);

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
    abstract public function onAuthenticationFailure(\Symfony\Component\Security\Core\Exception\AuthenticationException $exception);

    /**
     * Renvoie une instance de l'utilisateur
     *
     * Ceci correspond à la class Besancon\AuthBundle\Security\User\AuthUser,
     * il est possible de redéfinir cette méthode
     * mais elle doit renvoyer un objet implementant Symfony\Component\Security\Core\User\UserInterface
     *
     * Est utilisé dans le userprovider par défaut Besancon\AuthBundle\Security\User\AuthUserProvider
     *
     * @param string $username
     *               Identifiant de l'utilisateur
     * @return \Symfony\Component\Security\Core\User\UserInterface
     *
     * @see \Symfony\Component\Security\Core\User\UserInterface
     * @see \Besancon\AuthBundle\Security\User\AuthUserProvider
     *
     */
    abstract public function getUser($username);
}
