Installation
============

1: Installation
---------------------------

Copier et coller le dossier Besancon du Bundle dans src/


2: Activer le Bundle
-------------------------

Pour activer le Bundle, ouvrir le fichier `app/AppKernel.php` et y ajouter:

```php
<?php
// app/AppKernel.php

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

Puis dans le fichier `composer.json` de votre projet ajouter: 
```json
"autoload": {
        "psr-4": {
            ...
            "Besancon\\AuthBundle\\": "src/Besancon/AuthBundle",
            ...
        }
```

3: Authentification Cas
---------------------------

Si le Bundle est utilisé pour une athentification "Cas" alors télécharger la librairie phpCas dans votre projet

```console
$ composer require jasig/phpcas
```

Ouvrir le fichier `app/config/config.yml` et configurer : 

```yml
besancon_auth:
    homepage: "homepage" #nom de la route de l'accueil de l'application
    type_auth: Cas
    cas:
        hostname: "serveurcas.ac-academy.fr" #serveur cas
        port: 8443 #port cas
        uri: "" #uri

```