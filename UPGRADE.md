# Migration vers SAND-framework pour PHP 8.3

Ce guide liste les ruptures introduites par la branche `feature/convention-php83` et ce qu'il faut faire dans une application ou un module existant.

## 1. PHP 8.2 minimum

`composer.json` exige `"php": "^8.2"`. C'est le plancher imposé par illuminate/view 12 et Symfony 6.4. La cible testée est PHP 8.3.

**À faire :** passer le serveur (PHP-FPM et CLI) en 8.2 ou plus, puis relancer `composer install`. Le contournement `COMPOSER_NO_BLOCKING` n'est plus nécessaire.

## 2. Symfony 2.8/3.4 vers 6.4 LTS

`symfony/routing`, `config`, `yaml`, `expression-language` et `http-foundation` passent en `^6.4`. `symfony/cache` est épinglé en `^6.4`.

- Le routage du framework (`Dispacher`, `application/config/files/routing.yml`) fonctionne sans changement. La clé `controller` doit rester sous `defaults`.
- Le `Dispacher` passe au routeur le chemin sans chaîne de requête : `/foo?x=1` correspond à la route `/foo`. Un slash final non déclaré (`/foo/`) ne correspond toujours à aucune route Symfony et retombe sur le routage MVC.
- **http-foundation 2.8 vers 6.4 :** les applications et modules qui utilisent `Request`, `Response` ou `Session` doivent migrer. Les points principaux :
  - `Request::get()` est déprécié : passer par `$request->query` et `$request->request` ;
  - la session passe par `$request->getSession()` et les interfaces `SessionInterface` ;
  - le stockage de session et les en-têtes ont des signatures typées.

  **À faire :** suivre les fichiers `UPGRADE-3.0` à `UPGRADE-6.4` de Symfony pour chaque classe utilisée.

## 3. psr/container ^2.0 imposé

`psr/container` est épinglé en `^2.0`. `ContainerInterface::get()` et `has()` sont alors typés (`string $id`, retour `mixed`/`bool`).

**À faire :** typer les signatures de tout conteneur maison qui implémente `Psr\Container\ContainerInterface`.

## 4. Twig 1 vers 3

Les classes `Twig_*` n'existent plus.

**À faire :** remplacer `Twig_Environment` par `Twig\Environment`, `Twig_SimpleFunction` par `Twig\TwigFunction`, `Twig_Extension` par `Twig\Extension\AbstractExtension`, et ainsi de suite. Supprimer les balises et filtres retirés : `{% spaceless %}` devient `{% apply spaceless %}`, et `{% filter %}` devient `{% apply %}`.

## 5. illuminate/view 4 vers 12 (Blade)

Le rendu passe par `MVC\Classe\Rendu`. Blade garde le comportement de la v4 :

- `{{ $x }}` affiche **sans** échappement ;
- `{{{ $x }}}` échappe.

Les gabarits existants gardent donc leur rendu.

**À faire :** rien pour les gabarits. Le code qui instanciait lui-même `Illuminate\View\Environment` (v4) doit passer par `Rendu::blade()` ou `Illuminate\View\Factory`.

## 6. `Implement\Conduit`

`Implement\Conduit` déclare désormais `public $templateData = [];` et porte `#[\AllowDynamicProperties]`. L'attribut est hérité par les conduits.

- Un conduit qui redéclare `$templateData` en `protected`, en `private` ou avec un type provoque une **erreur fatale**.

  **À faire :** supprimer la redéclaration, ou la garder `public` et non typée.
- Les paramètres de route (ex. `{id}`) restent exposés en propriétés (`$this->id`).

  **À faire :** ne pas nommer un paramètre de route `templateData`, car il écraserait le tableau.

## 7. `Action::render(..., 'twig')`

Le troisième paramètre était ignoré : le rendu se faisait toujours en Blade. `render($vue, $donnees, 'twig')` rend désormais réellement en Twig.

**À faire :** vérifier les appels qui passent `'twig'`. Le gabarit `<vue>.html.twig` doit exister, et l'échappement automatique de Twig s'applique aux variables de ce gabarit (utiliser `|raw` pour une variable HTML de confiance). L'appel `{{ action(...) }}` lui-même n'a pas besoin de `|raw` : la fonction `action` est déclarée sûre.

**Sécurité :** la sortie d'une action est donc insérée telle quelle. L'action est responsable de l'échappement de toute donnée venant d'un utilisateur : en Blade, `{{ }}` est brut, utiliser `{{{ }}}` ; en Twig, pas de `|raw` sur une donnée utilisateur ; ne jamais renvoyer une chaîne non maîtrisée.

**Dossier des actions :** l'ancienne documentation indiquait `application/include/action/` ; le dossier réel est `application/include/actions/`.

## 8. `Vue`

Le constructeur `Vue($page_params, $racineVues = null, $dossierControleurs = null, $cacheBlade = null)` accepte trois paramètres facultatifs. Leurs valeurs par défaut sont `VIEW_PATH`, `CONTROLLER_PATH` et `VIEW_PATH/cache`.

**À faire :** rien pour les appels existants. Une sous-classe qui redéfinit le constructeur doit rester compatible avec cette signature.

## 9. Cache Blade

Les gabarits compilés par illuminate/view 4 sont incompatibles avec la v12.

**À faire :** vider `application/include/vues/cache/` à chaque déploiement de cette version, avec `find application/include/vues/cache -type f ! -name '.gitignore' -delete`.

## 10. Éléments obsolètes

Les éléments suivants ne sont plus compatibles avec PHP 8.3 et ne doivent pas être utilisés tels quels :

- `switch2SymfonyModule.sh` installe `illuminate/view 4.*` ;
- `switch2LaravelModule.sh` installe `twig/twig 1.*` ;
- `console/skel/symfony/` contient une ancienne copie de composants Symfony, inutilisée ;
- `console/skel/symfony-app/` repose sur Symfony 5.0 et PHP 7.2.

**À faire :** ne pas lancer ces scripts. Les modules embarqués (Symfony, Laravel) gèrent leurs propres dépendances avec leur `composer.json`, à monter séparément.

## 11. Routage, réponses HTTP/REST et mode débogage

- **Constructeur des réponses :** `Implement\HttpReponse` et `Implement\RestReponse` ont un vrai `__construct($url = null, $requestData = null)` (l'ancien `__contruct` mal orthographié n'était jamais appelé). Le framework instancie la réponse avec `new $classe($url, $data)`. **À faire :** dans une réponse qui définit son propre constructeur, appeler `parent::__construct($url, $requestData)`. Sans cet appel, le framework se replie sur `instanciate()`, qui reste disponible.
- **Constante `DEBUG` :** sa valeur par défaut (`false`) est définie dans `application/config/constantes-par-defaut.php`, chargé en dernier par l'autoload Composer. **À faire :** après la mise à jour, lancer une fois `composer dump-autoload` (nouveau fichier dans `autoload.files`) ; pour activer le débogage, ajouter `define('DEBUG', true);` dans `app-parameters.php`, jamais en production. Seul le booléen `true` active le mode (`MVC\Object\Environment::debug()`).
- **Chemins du serveur :** si aucun contrôleur d'erreur n'existe, l'erreur est journalisée (`app.error.log`) avec les chemins et la réponse vaut 500. Les chemins ne sont affichés qu'en mode `DEBUG`.
- **Chaîne de requête :** `Url` la relit dans `$_GET` sans écraser les clés déjà présentes. Un paramètre sans `=` vaut une chaîne vide.

## Vérification

```bash
composer install
cd tests/phpunit && composer install && php bin/phpunit -c phpunit.xml
```
