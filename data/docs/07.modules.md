# Comment Ajouter un module Symfony ou tout autre application php

il vous faut instancier trois fichiers:
le modèle (.model) contenant le nom de la page qui porte le model
ici : `application/include/modeles/syf51.model`
```yaml
name : syf51
page_title : Accueil de l'application modulaire
description : zatou stra bracadabla
params : params
```
le controlleur (.php) contenant ce code qui doit être automatisé
ici:  `application/include/controlleurs/syf51.php`
```php
<?php
\MVC\Classe\Session::start();
$app = new MVC\Classe\Modular($name);
$templateData = array('app' => $app);
```
et déclarer le module dans `\application\modules\setup\registre.model`
par une ligne suplémentaire:
```yaml
syf51 : Application permettant de tester l'intégration d'un module avec symfony5.0.99
```

si besoin et que le module n'existe pas il vous faudras coder et modifier
le fichier `/application/class/Modular.php` voir peut-être `/application/class/ModularRegister.php`

Good Luck !