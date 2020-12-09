**AuthBundle**
========================

# Configuration minimale requise

Le bundle est compatible à partir de la version 3.4 de Symfony.

# Installation

## Installation via composer (recommandé)

Dans un premier temps renseigner le "repository" via la commande :

```bash
composer config repositories.authbundle git "ssh://git@gitlab1.in.ac-besancon.fr:1232/abelhadjali/authbundle.git"
```

Ceci va ajouter dans votre fichier composer.json les lignes suivantes

```json
   ... 
   "repositories": {
        "authbundle": {
            "type": "git",
            "url": "ssh://git@gitlab1.in.ac-besancon.fr:1232/abelhadjali/authbundle.git"
        }
    }
  ...
```

Puis ajouter la dépendance au bundle en précisant le tag de la version souhaitée ici à partir de la v0.1

```bash
composer require ac-besancon/authbundle:^0.1
```

Enfin activer le bundle en suivant les instructions de la section [[AuthBundle#Activation du bundle|Activation du bundle]]

## Installation sans composer

### Récupérer les sources

*Copier et coller* le dossier Besancon du Bundle dans le repertoire _*src/*_ de votre projet *Symfony*.

### Déclaration du namespace

Dans le fichier `composer.json` et dans la section "autoload" de votre projet ajouter: 

```json
       "autoload": {
        "psr-4": {
            ...
            "Besancon\\AuthBundle\\": "src/Besancon/AuthBundle",
            ...
        }
```

Puis executer la commande composer suivante :

```bash
composer dump-autoload
```


# Activation du bundle

Pour activer le Bundle, ouvrir le fichier app/AppKernel.php et y ajouter:

```php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Besancon\AuthBundle\BesanconAuthBundle(),
        );

        // ...
    }

    // ...
}
```


# Configuration
======================

## Liste complète des options de configuration

La configuration est à déclaré dans le fichier *app/config/config.yml* du projet Symfony.

```yaml
besancon_auth:
    #Activation du user_provider interne
    #par défaut TRUE
    use_default_provider : true
    #Namespace de l'entité utilisateur
    #L'entité doit implémenter Symfony\Component\Security\Core\User\UserInterface
    #par défaut est utilise Besancon\AuthBundle\Security\User\AuthUser 
    user_entity: Mon\Entite\User
    #nom de la route correspondant à la page d'accueil de l'application
    #par défaut est à NULL
    homepage: "homepage"
    #tag du service personnalisé permettant de gérer l'authentification
    #par défaut est à bes_auth.authentification (service par défaut)
    authentication_service: mon_service.authentification
    #Mode d'authentification Cas ou Rsa
    #obligatoire pas de valeur par défaut
    type_auth: Cas
    #Configuration pour le mode Cas
    #obligatoire si mode Cas choisi
    cas:
        #Serveur Cas
        hostname: "seshat23.ac-besancon.fr"
        #Port Cas
        port: 8443
        #Uri Cas
        uri: ""
    #Configuration pour le mode Rsa
    #obligatoire si mode Rsa choisi
    rsa :
        #Url de déconnexion Rsa
        logout_url: http://url.deconnexion.fr/login/ct_logout.jsp
```

## Configuration dans le firewall

Ouvrir le fichier app/config/security.yml du projet Symfony.

Si utilisation du _user provider_ interne *bes_auth.user_provider* , alors le déclarer dans la section _*providers*_ :

```yaml
...
providers:
        app:
           id: bes_auth.user_provider
...
```

Sinon  préciser votre propre user provider

Toujours dans le même fichier, dans la section des _*firewalls*_, déclarer le _guard_ *bes_auth.authenticator* dans la zone à sécurisée :

```yaml
    firewalls:
     ...
        secured_area:
            logout_on_user_change: true
            ...
            guard:
                authenticators:
                    - bes_auth.authenticator 
            logout:
                path:   auth_cas_logout #nom de la route de déconnexion
                target: /
                success_handler: bes_auth.authenticator
            ...
```

Plus d'infos sur le user provider :
* https://symfony.com/doc/current/security/entity_provider.html#using-a-custom-query-to-load-the-user

Il est donc important de définir la route de déconnexion dans le fichier *app/config/route.yml* 

```yaml
...

auth_cas_logout:
   path: /logout

...
```

## Configuration avancée

### Création d'un service d'authentification

Pour cela, créer un service qui hérite de *AuthAbstract* et implémente *AuthInterface*

```php

<?php

namespace AppBundle\Security\Auth;

use Besancon\AuthBundle\Security\Interfaces\AuthInterface;
use Doctrine\ORM\EntityManager;
use Besancon\AuthBundle\Security\Abstracts\AuthAbstract;

class MonService extends AuthAbstract implements AuthInterface {
...
}

```

Il faut ensuite implémenter les méthodes suivantes :

```php

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

```

Enfin lorsque le service est prêt, le déclarer, en le reliant à la classe parent Besancon\AuthBundle\Security\Abstracts\AuthAbstract:

```yaml

    mon_service.authentification:
       class: AppBundle\Security\Auth\MonService 
       parent: Besancon\AuthBundle\Security\Abstracts\AuthAbstract
       public: false  

#OU si version Symfony >=3.4

    AppBundle\Security\Auth\MonService:
        autowire: true
        parent: Besancon\AuthBundle\Security\Abstracts\AuthAbstract
        public: false  
        autoconfigure: false

```

Puis déclarer dans la configuration ([[AuthBundle#Liste complète des options de configuration|Liste complète des options de configuration]]) du bundle le nom du service personnalisé :

```yaml
besancon_auth:
...
    authentication_service:  mon_service.authentification

#OU si version Symfony >=3.4

    authentication_service:  AppBundle\Security\Auth\MonService

 ...
```

# Personnaliser la page en cas d'échec d'authentification

En cas d'échec lors de l'authentification (exemple ctrlAccess() retourne false) , par défaut, le bundle renvoie une page blanche avec le message renvoyé par l'exception qui a généré l'erreur.
Afin de personnaliser cette page, il faut passer par la création d'un service comme indiqué dans le paragraphe [[AuthBundle#Création d'un service d'authentification|Création d'un service d'authentification]] et de redéfinir la méthode *onAuthenticationFailure*.

Voici un exemple : 

```php

class MonService extends AuthAbstract implements AuthInterface
{

    
    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    ...

    public function onAuthenticationFailure(\Symfony\Component\Security\Core\Exception\AuthenticationException $exception)
    {

        $content = $this->twig->render(
            '@App/Test/forbiden.html.twig', array()
        );
        $response = new Response($content, Response::HTTP_FORBIDDEN);
        return $response;
    }
}
```

Nous pouvons remarquer que dans cet exemple, le service prend en paramètre dans le constructeur $twig qui est l'instance de Twig de notre applciation.
Pour que cela fonctionne, il faut auparavant avoir passer le tag twig à notre service :

```php
 ...
 AppBundle\Security\Auth\MonService:
        autowire: true
        parent: Besancon\AuthBundle\Security\Abstracts\AuthAbstract
        public: false  
        autoconfigure: false
        arguments: ['@twig']
 ...
```

Ainsi lorsqu'une personne tentera de se connecter et qu'il n'aura, par exemple, pas les droits nécessaires le template @App/Test/forbiden.html.twig sera chargé.