# Migration vers SAND-framework pour PHP 8.3

Ce guide liste les ruptures introduites par la branche `feature/convention-php83` et ce qu'il faut faire dans une application ou un module existant. Les espaces de noms du framework sont désormais `SAND\…` (voir le § 17) ; les exemples ci-dessous les emploient.

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

Le rendu passe par `SAND\Classe\Rendu`. Blade garde le comportement de la v4 :

- `{{ $x }}` affiche **sans** échappement ;
- `{{{ $x }}}` échappe.

Les gabarits existants gardent donc leur rendu.

**À faire :** rien pour les gabarits. Le code qui instanciait lui-même `Illuminate\View\Environment` (v4) doit passer par `Rendu::blade()` ou `Illuminate\View\Factory`.

**windwalker/renderer 3 vers 4 :** le constructeur de `BladeRenderer` et de `TwigRenderer` prend désormais un tableau d'options, et non plus une `SplPriorityQueue` de chemins ; `addExtension()` devient `extend()`. **À faire :** un hôte ou un module qui instancie lui-même `BladeRenderer` ou `TwigRenderer` doit passer par `SAND\Classe\Rendu`, qui construit les moteurs avec la bonne signature : `Rendu::blade($racine, $cache)`, `Rendu::twig($racine)` (tous deux renvoient un `RendererInterface`), `Rendu::rendre(...)` pour rendre une vue et `Rendu::chemins($racine)` pour la liste des dossiers de vues.

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
- **Interfaces des réponses :** le dossier `Implement/Contrat/` devient `Implement/Contract/` ; l'espace de noms `SAND\Classe\Implement\Contrat` devient `SAND\Classe\Implement\Contract` (`HttpReponseInterface`, `RestReponseInterface`). **À faire :** dans une classe qui implémente ces interfaces, remplacer `use SAND\Classe\Implement\Contrat\…` par `use SAND\Classe\Implement\Contract\…`.
- **Constante `DEBUG` :** sa valeur par défaut (`false`) est définie dans `application/config/constantes-par-defaut.php`, chargé en dernier par l'autoload Composer. **À faire :** après la mise à jour, lancer une fois `composer dump-autoload` (nouveau fichier dans `autoload.files`) ; pour activer le débogage, ajouter `define('DEBUG', true);` dans `app-parameters.php`, jamais en production. Seul le booléen `true` active le mode (`SAND\Object\Environment::debug()`).
- **Requête « App » sans réponse dédiée :** une requête reconnue comme « App » (User-Agent `curl`, par exemple) sans classe `*RestReponse` ni `*HttpReponse` pour la page n'est plus servie comme une page MVC : elle va au contrôleur `Error*Reponse`. **À faire :** pour qu'un client curl ou une application obtienne la page, ajouter la réponse `*RestReponse` (ou `*HttpReponse`) correspondante.
- **Chemins du serveur :** si aucun contrôleur d'erreur n'existe, l'erreur est journalisée (`app.error.log`) avec les chemins et la réponse vaut 500. Les chemins ne sont affichés qu'en mode `DEBUG`.
- **Chaîne de requête :** `Url` la relit dans `$_GET` sans écraser les clés déjà présentes. Un paramètre sans `=` vaut une chaîne vide.

## 12. Session, Guard et jeton XSS

- **Nouvelles constantes** (valeurs par défaut dans `application/config/constantes-par-defaut.php`, surchargeables dans `app-parameters.php`) :
  - `SESSION_CLE_ID_UTILISATEUR` (défaut `'id_user'`) : clé de `$_SESSION` qui porte l'identifiant de l'utilisateur connecté ;
  - `SESSION_CLE_ACL` (défaut `'acl_admin'`) : clé de `$_SESSION` qui porte le profil lu par `Guard::exiger()` ;
  - `PAGE_IDENTIFICATION` (défaut `'index'`) : page de connexion.

  **À faire :** si l'application range l'identifiant sous une autre clé (par exemple `id_utilisateur`), ajouter `define('SESSION_CLE_ID_UTILISATEUR', 'id_utilisateur');` dans `app-parameters.php`. Faire de même pour `SESSION_CLE_ACL` et pour `PAGE_IDENTIFICATION` si la page de connexion n'est pas `index`.
- **`Session::estConnecte()`** est le critère unique de connexion. Elle renvoie vrai si `user_login` est présent, non vide et différent de `'anonymous'` (CAS, authentification de l'application), ou si `$_SESSION[SESSION_CLE_ID_UTILISATEUR]` est présent et non nul (cas d'un hôte qui pose un identifiant numérique). Un identifiant `0` compte comme connecté.
- **`Session::refuserAcces()`** centralise le refus : un visiteur non connecté selon `estConnecte()` (ou dont la session a expiré) va vers `PAGE_IDENTIFICATION`, un utilisateur connecté (un utilisateur CAS sans droits, par exemple) vers `error-access-denied`. **Pas de boucle :** si la cible est la page courante (chemin de `REQUEST_URI`), la cible suivante est prise (`error-access-denied`). Si elle l'est aussi, la réponse est un 403 sans redirection.
- **`checkSession()`** passe par `refuserAcces()`. L'ancien `checkSession()` appelait `Url::link_rewrite()` sans qualification, ce qui provoquait une erreur fatale (`SAND\Object\Url` n'existe pas). Son critère reste « session initialisée » (`user_login` posé, `'anonymous'` compris) : les pages `authentification: yes` du squelette (`index`, `cgu`…) restent ouvertes au visiteur anonyme. Une page réservée aux utilisateurs connectés teste `Session::estConnecte()` ou passe par un Guard.
- **`SAND\Object\Guard::exiger(...$acl)`** refuse par `refuserAcces()` si `$_SESSION[SESSION_CLE_ACL]` ne vaut aucune des valeurs données. Seuls un entier ou une chaîne d'entier sont acceptés (`1` et `'1'` sont égaux ; `true`, `1.0` ou `'1.0'` sont refusés). `Session::checkACL_admin()` vaut `Guard::exiger(1)`. **Changement de comportement :** un visiteur non connecté qui atteint une page `admin-*` est désormais redirigé vers `PAGE_IDENTIFICATION`, et non plus vers `error-access-denied`. Un utilisateur connecté sans droits va toujours vers `error-access-denied`.
- **`Session::getUser()`** (`$_SESSION['user_login']`) et **`Session::getIdUser()`** (`$_SESSION[SESSION_CLE_ID_UTILISATEUR]`) renvoient `null` si la valeur manque.
- **`Session::sessionStart()`** n'appelle plus `session_start()` si une session est déjà active.
- **`XssToken::getNew()`** ne prend plus de paramètre et renvoie le jeton (toujours rangé dans `$_SESSION['xss_token']`). PHP accepte encore les anciens appels à trois arguments. **À faire :** retirer ces arguments inutiles.

## 13. Connexions aux bases de données (`Bdd`)

- **Connexions nommées :** `new Bdd('<nom>')` lit `DSN_BDD_<NOM>`, `USER_BDD_<NOM>` et `PASS_BDD_<NOM>` ; pour un nom `bdd<chiffres>` seulement, la forme historique `DSN_<NOM>` reste lue : `default`, `bdd1` et `bdd2` fonctionnent sans changement. Un nom inconnu lève une exception (auparavant, tout nom inconnu ouvrait silencieusement `default`). Voir `data/docs/01.j. Les connexions aux bases de données.md`.
- **Format et liste blanche :** un nom de connexion respecte `^[a-z][a-z0-9_]{0,31}$` et doit figurer dans la nouvelle constante `BDD_CONNEXIONS_AUTORISEES` (défaut `['default', 'bdd1', 'bdd2']`, dans `constantes-par-defaut.php`) ; sinon `InvalidArgumentException`. **À faire :** pour toute autre connexion, redéfinir `BDD_CONNEXIONS_AUTORISEES` dans `app-parameters.php` (par exemple `['default', 'bdd1', 'bdd2', 'annuaire']`) et déclarer `DSN_BDD_ANNUAIRE`, `USER_BDD_ANNUAIRE`, `PASS_BDD_ANNUAIRE` dans `bdd-parameters.php`. Écrire en minuscules un nom passé avec des majuscules ou des tirets (`new Bdd('BDD1')` → `new Bdd('bdd1')`).
- **Ouverture impossible :** journalisée (nom et SQLSTATE seulement) et signalée par une `RuntimeException` générique, sans l'exception PDO d'origine (qui porte les identifiants). **À faire :** une connexion impossible ne lève plus `PDOException` mais `RuntimeException` ; adapter les `catch (\PDOException ...)` existants autour de `new Bdd()`.
- **`faireSQLRequete()`** n'affiche plus rien : l'ancien `print_r` de `errorInfo()` et du SQL (en `ENV` `TEST` ou `DEVEL`) est remplacé par une ligne dans `app.error.log` (SQLSTATE, code et message tronqué du pilote, sans le SQL ni les identifiants). La méthode renvoie toujours `false` en cas d'échec.
- **Une connexion PDO par nom :** toutes les instances d'un même nom partagent la transaction en cours (pas de `beginTransaction()` imbriqué : `PDOException`), `lastInsertId()` et les attributs PDO. **À faire :** revoir le code qui ouvrait deux `new Bdd()` pour isoler deux transactions.
- **`Bdd::pourPage(array $page)`** ouvre la connexion déclarée par la clé `bdd` du `.model` (sinon `default`). À n'utiliser qu'avec une définition de page issue d'un `.model`, jamais avec des paramètres d'URL.
- **`faireBindRequete()`** renvoie `0` si la préparation, l'exécution ou PDO échoue, et journalise dans `app.error.log` le SQLSTATE, le code du pilote et le message du pilote sur une ligne, tronqué à 120 caractères (sans DSN ni mot de passe). Les anciens blocs `catch` visaient `SAND\Classe\PDOException` (classe inexistante) : une `PDOException` remontait à l'appelant ; elle est maintenant interceptée, journalisée et la méthode renvoie `0`. **À faire :** tester `=== 0` avant `exploiterResultat()`.
- **`exploiterResultat($req)`** normalise réellement les chaînes des lignes renvoyées (`Caracter::corrigerUtf8()`) : l'ancienne boucle travaillait sur une copie et rendait les lignes brutes. Une valeur ISO-8859-1 ou doublement encodée en base revient donc désormais en UTF-8 corrigé.
- **`exploiterNbResultat($req)`** renvoie `rowCount()`.

## 14. Actions et vues

- **`ControlleurAction::inserer()`** renvoie `''` au lieu de `null` quand l'action est introuvable, et journalise l'absence dans `app.error.log`. Un nom d'action qui n'est pas un identifiant PHP est traité comme introuvable. **À faire :** remplacer les tests `=== null` sur le retour d'`inserer()` par `=== ''`.
- **Méthodes appelables depuis un gabarit :** `inserer('objet.methode')` n'appelle plus que les méthodes dont le nom commence par une minuscule (`^[a-z][A-Za-z0-9_]*$`) et qui ne sont pas déclarées par `Implement\Action` (`render`, `renderTwig`, `renderBlade`, `renderJSON`, `racineVues`, `cheminCache`). Les autres sont refusées, journalisées, et l'appel renvoie `''`. **À faire :** renommer une méthode d'action appelée depuis un gabarit qui commence par une majuscule ou par `_`, et remplacer un appel direct `objet.render` par une méthode propre à l'action.
- **Action en erreur :** une exception levée par une action est journalisée. Hors débogage, l'appel renvoie alors `''` au lieu de laisser l'exception remonter jusqu'à la page ; en débogage (`DEBUG === true`), elle est relancée. **À faire :** une page qui comptait sur l'exception d'une action pour s'interrompre doit tester le retour ou faire la vérification hors de l'action.
- **`Implement\Action::renderTwig($vue, $donnees, $dir = null)`** et **`renderBlade($vue, $donnees, $dir = null)`** acceptent une racine de vues facultative (avec Blade, cache dans `$dir/cache`). Sans `$dir`, rien ne change. **À faire :** une sous-classe qui redéfinit `renderTwig()` ou `renderBlade()` doit déclarer ce troisième paramètre, sinon PHP refuse la déclaration.
- **`Implement\Action::render($vue, $donnees, $moteur = 'blade')`** prend un troisième paramètre. **À faire :** une sous-classe qui redéfinit `render($vue, $donnees)` provoque une **erreur fatale** de signature ; ajouter `$moteur = 'blade'` à sa déclaration (comme pour `renderTwig()` et `renderBlade()` ci-dessus), ou renommer la méthode.
- **Analyse des `.model` :** la valeur n'est plus tronquée au premier `.`, `/`, `:` ou `%`, la clé est ancrée en début de ligne, et les espaces autour des valeurs sont retirés. Un `page_title` (ou une `description`) peut donc s'afficher différemment : `page_title : Accueil : bienvenue` donne maintenant `Accueil : bienvenue`, et non `Accueil`. **À faire :** relire les `.model` dont une valeur contient un de ces caractères. Voir `data/docs/01.f. Comment modifier proprement une page.md`.
- **`Vue::titrePage()`** et **`Vue::descriptionPage()`** (nouveaux) renvoient `page_title` et `description` du `.model` de la première vue rendue qui a un titre ; `Vue::reinitialiserPage()` les oublie. Voir `data/docs/07.b. modules - comment bien les utiliser.md`.

## 15. Divers

- **`Browser::detection_mobile()`** (nouveau) renvoie vrai pour un appareil mobile : en-têtes WAP (`X-Wap-Profile`, `Profile`, `Accept` contenant `wap`), puis User-Agent contenant `Mobile` ou `Opera Mini`, puis `get_browser()` **seulement si** la directive `browscap` de `php.ini` est renseignée. Sans browscap, seuls les en-têtes et le User-Agent comptent : un mobile reconnu uniquement par browscap (vieux téléphone MIDP, par exemple) est alors vu comme non mobile. Aucun service externe n'est appelé.
- **`Browser::detectionMobile()`** (nom PSR-1) est l'alias camelCase de `detection_mobile()`, qui délègue à lui. L'ancien nom `detection_mobile()` est conservé pour la compatibilité avec B25 ; l'utiliser tant que le code appelant n'est pas migré, puis passer à `detectionMobile()`.
- **`Asynchonous::addJs()` et `printJs()`** (ancienne API à un seul tampon JavaScript) reviennent comme **alias dépréciés** de `addFooterJs()` et `printFooterJs()` : ils fonctionnent, mais émettent un `E_USER_DEPRECATED`. Le tampon de pied de page est retenu parce qu'un script ajouté après coup s'y exécute en fin de page sans bloquer le rendu, et parce que `addJs()` puis `printJs()` lisent ainsi toujours le même tampon. **À faire :** remplacer `addJs()`/`printJs()` par `addFooterJs()`/`printFooterJs()`, ou par `addHeaderJs()`/`printHeaderJs()` pour un script qui doit être chargé dans l'en-tête. La classe `SAND\Object\Asynchronous` (stockage en session) n'est pas concernée.
- **`Dumper` et `E_ALL`** : `Dumper::setPHPvalues()` (appelée par `public/index.php`) règlait déjà `error_reporting(E_ALL)`. `Dumper::display_errors()`, l'aide au débogage, masquait les notices (`E_ALL & ~E_NOTICE`) ; elle règle désormais aussi `E_ALL`. Les dépréciations restent donc visibles, ce qui sert la migration vers PHP 8.3.
- **`Dumper::setPHPvalues()` n'affiche plus les erreurs hors débogage** : sans `DEBUG === true`, elle règle `display_errors=0`, `display_startup_errors=0`, `log_errors=1` et `error_reporting(E_ALL)`. Les erreurs vont dans le journal de PHP au lieu de révéler les chemins du serveur dans la page. En débogage, l'affichage reste actif comme avant. **À faire :** pour voir les erreurs à l'écran en développement, définir `DEBUG` à `true` ; en production, vérifier que `error_log` de `php.ini` pointe vers un fichier lisible.
- **Journal `app.http.method.log`** : `HttpMethod` n'y écrit plus qu'en mode débogage (`SAND\Object\Environment::debug()`), au lieu d'une ligne à chaque requête. **À faire :** activer `DEBUG` pour retrouver ce journal.
- **`neitanod/forceutf8` retiré** : `Caracter::normalise_ChaineDeCaracteres()` et `normalise_ChaineDeCaracteresUpload()` (appelées par `Bdd::faireBindRequete()` pour chaque paramètre et par `exploiterResultat()`) passent par la nouvelle `Caracter::corrigerUtf8()`, écrite avec `mb_check_encoding()` et `mb_convert_encoding()`. `ForceUTF8\Encoding::fixUTF8()` appelait `utf8_decode()`, dépréciée depuis PHP 8.2 : toute requête avec paramètres émettait une dépréciation. Même résultat pour l'UTF-8 et l'ISO-8859-1/Windows-1252 (converti en UTF-8), et le double encodage (« Ã© ») est toujours corrigé, même répété. **Différence voulue :** sous PHP 8, `fixUTF8()` remplaçait par `?` tout caractère hors Latin-1 (`œ`, `€`, `—`, idéogrammes, emoji) ; ils sont désormais conservés. **À faire :** une application qui utilise elle-même `ForceUTF8\Encoding` doit ajouter `neitanod/forceutf8` à son propre `composer.json`, ou passer par `Caracter::corrigerUtf8()`.
- **`Logger::addLog()` en ligne de commande** : sans `$_SERVER['HTTP_USER_AGENT']`, le User-Agent journalisé vaut `inconnu`, sans avertissement (le constructeur de `Browser` applique le même repli).

## 16. API REST `api/` (wp-json et Ereferer)

- **Nouveau dossier `api/`** à la racine (`SAND\Api\…`, déclaré en PSR-4 dans `composer.json`). **À faire :** lancer `composer dump-autoload`.
- **Ordre dans `Application` :** une requête dont le chemin commence par `/wp-json` ou `/api` (après `BASE_SERVER_DIRECTORY`) est confiée à `SAND\Api\Component\Router`, **avant** la maintenance, `Url`, `Dispacher` et `Controlleur`. Elle reçoit toujours une réponse JSON. `Application::$api` vaut alors `true` et `$controlleur` reste `null`. **À faire :** si l'application a une page MVC nommée `api` (ou `wp-json`), la renommer, car elle n'est plus atteignable. L'API reste joignable pendant une maintenance.
- **`public/.htaccess`** recopie l'en-tête `Authorization` dans `HTTP_AUTHORIZATION` (règle `E=HTTP_AUTHORIZATION`), pour les seuls chemins `^(wp-json|api)/`. **À faire :** reporter ces deux lignes (`RewriteCond` et `RewriteRule`) dans un `.htaccess` ou un vhost personnalisé, ou utiliser `CGIPassAuth On`.
- **Clés :** copier `api/config/api_keys.php.skel` en `api/config/api_keys.php` (ignoré par git) et y mettre une clé générée par `bin2hex(random_bytes(32))`, ou son empreinte SHA-256. Sans ce fichier, toute écriture (`POST`, `PUT`, `PATCH`, `DELETE`) répond 401.
- **Nouvelles constantes** (dans `constantes-par-defaut.php`) : `API_FICHIER_CLES`, `API_FICHIER_ROUTES` et `API_DEPOT_SOUMISSIONS` (défaut `null`). **À faire :** pour recevoir les articles d'ereferer.com, implémenter `SAND\Api\Contract\DepotSoumissions` et déclarer la classe dans `API_DEPOT_SOUMISSIONS`. Sans cela, `/wp-json/v1/ereferer` répond 501.
- **Reprise depuis l'ancienne API de B25 (`application/__api`) :** espaces de noms `B25\Api\…` → `SAND\Api\…` ; actions `get_items`, `get_item`, `create_item`, `update_item`, `delete_item` → `getItems`, `getItem`, `createItem`, `updateItem`, `deleteItem` (PSR-1). Un contrôleur renvoie un tableau ou une `Response`, sans `echo` ni `die()`. Une clé invalide donne 401 (et non plus 403). Il n'y a plus de sortie HTML (`?format=html`). Les routes explicites s'écrivent `$registre->register(...)`.
- **Limites et validations de l'API :**
  - le corps n'est lu qu'après l'authentification ;
  - **`API_TAILLE_MAX_CORPS`** (défaut 1 Mo) : au-delà, 413 ;
  - JSON limité à 32 niveaux ; JSON invalide : 400 ;
  - limites Ereferer : titre 255, contenu 200 000, 20 catégories, `meta_title` 255, `meta_description` 500, 50 entrées ; au-delà : 422 ;
  - `HEAD` traité comme `GET`, sans corps ;
  - empreintes normalisées en minuscules ;
  - message 405 sans la méthode, `OPTIONS` sans nom de classe, journal limité à la classe et au message tronqué.
- **Maintenance :** la nouvelle constante **`API_PENDANT_MAINTENANCE`** vaut `true` par défaut, et l'API reste joignable pendant une maintenance. **À faire :** pour la fermer, la mettre à `false` dans `app-parameters.php` ; elle répond alors 503 en JSON.
- **Routes explicites :** une route `GET` de `api/config/routes.php` est publique, sans clé.
- **Piste d'évolution :** une aide commune `Chemin::segments()` (découpage du chemin de la requête, aujourd'hui répété dans `Url` et dans le routeur de l'API) n'a pas été faite ; c'est un refactoring plus large, à envisager dans une version ultérieure.
- Voir `data/docs/18. API wp-json et Ereferer.md`.

## 17. Espaces de noms `MVC\…` → `SAND\…`

La branche `devel` avait commencé, en 2022, à renommer les espaces de noms du framework ; la fusion avec le travail PHP 8.3 termine ce renommage. Depuis le 01/11/2026, `main`, `devel` et `4.x-dev` partent de cet espace de noms ; le dernier état de `main` en `MVC\…` est le commit `29b12d3`.

| Avant | Après |
|---|---|
| `MVC\Classe\…` | `SAND\Classe\…` |
| `MVC\Object\…` | `SAND\Object\…` |
| `MVC\Command\…` | `SAND\Command\…` |
| `MVC\Component\…` | `SAND\Component\…` (la console de `devel` déclarait encore `MVC\Component` : corrigé) |
| `MVC\Api\…` | `SAND\Api\…` (dont `SAND\Api\Contract\…`) |
| `MVC\Domain\…` | `APP\Domain\…` (code métier de l'application, dans `domain/`) |

- **À faire :** dans le code de l'application (contrôleurs, actions, conduits, traitements, réponses `*HttpReponse`/`*RestReponse`, vues Blade et Twig qui appellent `\MVC\Classe\…`, classe déclarée dans `API_DEPOT_SOUMISSIONS`), remplacer `MVC\` par `SAND\` et `MVC\Domain\` par `APP\Domain\`, puis lancer `composer dump-autoload` et vider `application/include/vues/cache/`.
- Les gabarits de la console (`console/skel/*.skel`) génèrent déjà `SAND\…`.
- Les sites qui embarquent le framework sous un espace de noms à eux (B25 : `B25\SAND_Module\…`) ne sont pas concernés tant qu'ils ne reprennent pas cette version.

## Vérification

```bash
composer install
cd tests/phpunit && composer install && php bin/phpunit -c phpunit.xml
```
