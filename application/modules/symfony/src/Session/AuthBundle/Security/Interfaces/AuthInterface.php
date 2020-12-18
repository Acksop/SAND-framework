<?php

/**
 * Interface AuthInterface
 * 
 * Interface permettant de déclarer les méthodes incontournables pour l'authentification
 * 
 * 
 * @package Besancon\AuthBundle\Security\Interfaces
 * @author  Amine BEL HADJ ALI <amine.belhadjali@ac-besancon.fr>
 * 
 */
namespace App\Session\AuthBundle\Security\Interfaces;


use Symfony\Component\Security\Core\User\UserInterface;

interface AuthInterface {

    /**
     * Contrôle de l'accès à partir des attributs CAS ou RSA
     * 
     * Vérifier les droits d'accès à l'application à partir des attributs récupérées des getters :
     *      - CasAttributes
     *      - RsaAttributes 
     * 
     * @param UserInterface $user
     *      L'entité user récupéré par le provider
     * 
     * @return bool
     *      - true si accès autorisé
     *      - false si accès refusé
     */
    public function ctrlAccess(UserInterface $user);

    /**
     * Calcule et retoune le(s) rôle(s) à partir des attributs CAS ou RSA
     * 
     * Calculer le(s) rôle(s) à partir des attributs récupérées des getters :
     *      - CasAttributes
     *      - RsaAttributes 
     * Doit retourner un tableau même vide 
     * 
     * @return array
     */
    public function getRoles();

    /**
     * Retourne un utilisateur pour la génération du token, si l'utilisateur n'existe pas en base de donnée
     * 
     * ATTENTION :  CETTE METHODE DOIT ÊTRE REDEFINIE SI UTILISATION D'UNE ENTITE UTILISTEUR 
     * DIFFERENTE DE CELLE UTILISEE PAR DEFAUT
     * 
     * @param String $username
     *      uid de l'utilisateur récupéré de Cas ou Rsa
     * 
     * @return UserInterface
     */
    public function getUser($username);

    /**
     * Traitement personnalisé après récupération du token
     * 
     * Il est possible d'enrichir le token (attributs...) ou d'effectuer des contrôles supplémentaire
     * 
     * @param $token 
     *      Token d'authification généré
     * 
     * @return null
     */
    public function onSuccess($token);

    /**
     * Traitement personnalisé lorsque la connexion n'a pas abouti
     * 
     * Vérifié l'exception généré et adapter l'action (redirection, déconnexion...)
     * 
     * Doit retourner un objet de type Response 
     * 
     * Exemple :
     * 
    * ```  
    *   public function onAuthenticationFailure(\Symfony\Component\Security\Core\Exception\AuthenticationException $exception) 
    *   {
    *        $content = $this->twig->render(
    *           '@App/Test/forbiden.html.twig', array()
    *        );
    *        $response = new Response($content, Response::HTTP_FORBIDDEN);
    *        return $response;
    *   }
    * ```
    *
    * @param AuthenticationException $exception 
    *      Exception générée par le provider
    * 
    * @return Symfony\Component\HttpFoundation\Response
    *
    */
    public function onAuthenticationFailure(\Symfony\Component\Security\Core\Exception\AuthenticationException $exception);
}
