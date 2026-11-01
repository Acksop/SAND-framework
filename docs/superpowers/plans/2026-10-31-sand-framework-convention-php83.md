# SAND-framework : convention, PHP 8.3, report B25 — plan d'implémentation

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal :** documenter la convention de nommage SAND, faire tourner Blade et Twig sous PHP 8.3 avec les mêmes fonctionnalités, puis reporter au cas par cas les corrections génériques du `__sand` de B25.

**Architecture :** dépôt `G:/Professionnel/OPENSOURCE - Projets/SAND-framework`, branche `feature/convention-php83`. Le rendu passe par `MVC\Classe\Vue` et `MVC\Classe\Implement\Action`, qui utilisent aujourd'hui `Windwalker\Renderer\{Blade,Twig}Renderer` avec trois chemins de vues (view 300, layout 200, system 100) et un cache. Les tests vivent dans `tests/phpunit` (PHPUnit) et s'exécutent sous PHP 8.3 dans le conteneur `dev-b25-lamp-php83`.

**Tech Stack :** PHP 8.3, Composer 2, twig/twig 3, illuminate/view (version PHP 8.3), windwalker/renderer (ou adaptateur), PHPUnit 9.6.

Spec : `docs/superpowers/specs/2026-10-31-sand-framework-convention-php83-design.md` (copie dans le dépôt SAND, ajoutée par Emmanuel le 01/11/2026).

## Global Constraints

- Branche `feature/convention-php83` ; commits locaux avec le message terminé par `Co-Authored-By: Claude Opus 5.5 <noreply@anthropic.com>` ; **jamais de push** (Emmanuel pousse).
- `application/__sand` de B25_FR et B25_PRO : **lecture seule**.
- Aucun secret ; `application/config/*-parameters.php` jamais commités (modèles `.skel`).
- Documentation en français ; code < 300 lignes par fichier ; PSR-1/PSR-12.
- Exécution PHP : le clone n'est pas monté dans le conteneur. Copier le dépôt (sans `.git`, `vendor`) dans `/tmp/sand-framework` du conteneur avec `docker cp`, y lancer Composer et PHPUnit, puis recopier `composer.json` et `composer.lock` modifiés vers le clone. Ne pas modifier `docker-compose.yml`.
- `git -C <clone>` sous Git Bash ; `MSYS_NO_PATHCONV=1` pour `docker exec`/`docker cp`.
- Si git refuse le dépôt (« dubious ownership »), ne **pas** modifier la configuration git globale : passer `-c safe.directory=<chemin>` à la commande.

---

### Task 1 : branche et annexe de convention (A1)

**Files :**
- Create : `data/docs/20. Annexe - Convention de nommage et méthodes HTTP.md`
- Modify : `README.md` (lien vers l'annexe dans la liste des documents, s'il y en a une ; sinon une ligne « Documentation »)

**Interfaces :** Produces : la convention de référence citée par les tasks 3 et 5.

- [ ] **Step 1** : `git -C "<clone>" checkout -b feature/convention-php83`.
- [ ] **Step 2** : rédiger l'annexe (< 250 lignes), sections :
  1. Pourquoi une convention : le nom du fichier dit qui y a accès (Guard) et ce qu'il fait.
  2. Pages : `include/modeles/<page>.model`, `include/controlleurs/<page>.php`, `include/vues/view/<page>.blade.php` (ou `.html.twig`) portent le même nom kebab-case ; premier segment = Guard (`admin-` → `Session::checkACL_admin()`) ; puis objet, puis opération ; pages publiques sans préfixe. Tableau d'exemples tirés de SIHAM-HEBERGES-APP : `admin-user-list`, `admin-organisme-add`, `heberge-create-from-individu`, `heberge-search-individu-name-results`, `vpn-create`, `wifi-individu-etat-civil`, `index`, `cgu`, `error-access-denied`.
  3. Traitements (POST + PRG) : `traitements/<guard>-<verbe>-<objet>.php` (`admin-user-add`, `admin-freeze-individu`), ou `<verbe>-<objet>.php` hors Guard (`create-heberge`, `send-feedback`, `print-…`) ; chaque traitement commence par `Session::createAndTestSession()` puis le contrôle du Guard, et se termine par `header('location: …'); die();`.
  4. Actions : gabarits appelés par une fonction depuis un gabarit mère, avec accès dynamique possible à la base ; `include/actions/<Objet>Action.php` étend `Implement\Action`, méthodes `render<Quoi>()`, gabarit `view/action-<objet>-<quoi>.blade.php` (`OrganismeAction::renderSelect()` → `action-organisme-select`).
  5. Conduits AJAX : `include/conduits/<Type>Conduit.php` (`AjaxConduit`), gabarits `view/ajax-conduit-<…>.blade.php` ; le conduit contrôle session et Guard lui-même.
  6. Vues : `include/vues/{view,layout,system,cache}`, priorités 300/200/100, layouts `body`, `body-nomenu`, `system` ; une arborescence par interface (WEB, MOBILE…) quand l'application en a plusieurs ; le cache est généré, jamais versionné.
  7. Méthodes HTTP : GET pour lire, POST + PRG pour écrire ; pas de PUT/DELETE hors API REST (`*RestReponse`) : HTTP/1.1 provoque trop d'erreurs avec ces verbes sur le Web (formulaires HTML limités à GET/POST, proxys et pare-feu qui les filtrent) ; GET/POST sont plus sûrs, au prix d'un peu de performance.
  8. Récapitulatif en une table : type de fichier → dossier → motif de nom → exemple.
- [ ] **Step 3** : ajouter le lien dans `README.md`.
- [ ] **Step 4** : vérifier qu'aucun exemple ne contient de donnée réelle de SIHAM (noms de personnes, identifiants, adresses) ; seulement des noms de fichiers et de fonctions.
- [ ] **Step 5** : commit `docs: annexe sur la convention de nommage et les méthodes HTTP`.

---

### Task 2 : Blade et Twig sous PHP 8.3, mêmes fonctionnalités (C)

**Files :**
- Modify : `composer.json`, `composer.lock` (si versionné ; sinon non)
- Modify selon besoin : `application/class/Vue.php`, `application/class/Implement/Action.php`, `application/class/TwigControlleurAction.php`, et toute classe utilisant `Twig_*`
- Create (si windwalker ne suit pas) : `application/class/Rendu/MoteurBlade.php`, `application/class/Rendu/MoteurTwig.php` (adaptateurs, < 120 lignes chacun)
- Create : `tests/phpunit/tests/RenduTest.php` et gabarits de test sous `tests/phpunit/tests/gabarits/{view,layout,system}/`
- Modify : `tests/phpunit/composer.json`, `tests/phpunit/phpunit.xml`

**Interfaces :**
- Produces : `Vue` et `Implement\Action` rendent Blade et Twig sous PHP 8.3 avec l'ordre view > layout > system et un cache ; la fonction d'appel d'action dans Twig (`TwigControlleurAction`) et dans Blade reste disponible sous le même nom qu'aujourd'hui.

- [ ] **Step 1 : relever l'existant** : noter dans le rapport la signature des appels actuels (`new BladeRenderer($paths, ['cache_path' => …])`, `new TwigRenderer($paths)`, `addExtension(new TwigControlleurAction)`, `render($name, $data)`), le nom de la fonction d'action exposée aux gabarits, et les gabarits `view/action.blade.php`, `view/action.html.twig`, `view/index.html.twig`, `layout/body.html.twig`.
- [ ] **Step 2 : écrire les tests de rendu (RED sous PHP 8.3 avec les dépendances actuelles)**, avec des gabarits de test minimaux : (a) Blade : une vue qui `@extends('body')` (layout) qui `@extends('system')` rend un texte venant des données ; (b) Twig : même chose avec `{% extends %}` ; (c) une vue appelle une action de test (`TestAction::renderSalut()` qui renvoie un gabarit `action-test-salut`) et le fragment apparaît dans le rendu, en Blade et en Twig ; (d) le cache Blade contient au moins un fichier après rendu et un second rendu réussit ; (e) aucun `E_DEPRECATED` pendant les rendus (gestionnaire d'erreurs qui échoue sur `E_DEPRECATED`/`E_USER_DEPRECATED`).
  Exécuter dans le conteneur (voir Global Constraints) : attendu FAIL (installation impossible ou dépréciations).
- [ ] **Step 3 : monter les dépendances** : `twig/twig` `^3`, `illuminate/view` dernière version compatible PHP 8.3, `windwalker/renderer` dernière version ; si aucune version de windwalker ne supporte ces moteurs, écrire les adaptateurs `Rendu/MoteurBlade` et `Rendu/MoteurTwig` (même interface `render(string $nom, array $donnees): string`, chemins avec priorités, cache Blade) et les utiliser dans `Vue` et `Implement\Action`. Monter les autres paquets seulement si Composer l'exige ; noter chaque montée forcée dans le rapport.
- [ ] **Step 4 : adapter le code** aux nouvelles API (Twig 3 : `Twig\Environment`, `Twig\Extension\AbstractExtension`, `Twig\TwigFunction`).
- [ ] **Step 5 : GREEN** : tests de rendu verts, sans dépréciation ; `php -l` sur chaque fichier modifié.
- [ ] **Step 6** : commits `build: Blade et Twig compatibles PHP 8.3` et `test: rendu Blade et Twig, actions et cache`.

---

### Task 2b : montée de Symfony 6.4 LTS et d'illuminate/view 12 (décision d'Emmanuel du 31/10/2026)

**Pourquoi :** Composer 2.9+ bloque Symfony 2.8/3.4/4.4 (avis de sécurité) : un `composer install` normal échoue ; illuminate/view 11 n'a plus de correctifs et la 12 exige Symfony ≥ 3.4.

**Files :** `composer.json` ; code du framework qui utilise `symfony/routing`, `http-foundation`, `yaml`, `config`, `expression-language` (routage, `Url`, `Dispacher`, `Modele`/lecture des `.model`, conduits, console) ; `tests/phpunit/…`.

- [ ] **Step 1** : relever tous les usages des composants Symfony dans `application/`, `console/`, `domain/`, `public/` (classes, méthodes, options), et les fichiers `routing.yml`/`.model` concernés.
- [ ] **Step 2** : tests de non-régression RED/GREEN avant la montée : résolution d'URL (`UrlTest` existant + cas du routage Symfony : route avec paramètre, route inconnue), lecture d'un `.model`, appel d'un conduit, rendu (suite de la task 2).
- [ ] **Step 3** : monter `symfony/routing`, `http-foundation`, `yaml`, `config`, `expression-language` en `^6.4`, puis `illuminate/view` en `^12` ; adapter le code aux API 6.4 ; contraintes explicites contre les régressions de résolution (pas de redescente de `symfony/cache`, `psr/container`).
- [ ] **Step 4** : `composer install` **sans** `COMPOSER_NO_BLOCKING` réussit ; `composer audit` sans avis bloquant (lister les avis restants dans le rapport) ; suite PHPUnit verte, sans dépréciation ; `php -l`.
- [ ] **Step 5** : commit `build: Symfony 6.4 LTS et illuminate/view 12`.

---

### Task 3 : documentation des vues et de l'installation (A2)

**Files :** `data/docs/00. Comment installer l'environnement du framework.md`, `03.a. Les vues Twig.md`, `03.b. Les vues Blade.md`, `04. Les Actions dans les vues.md`.

- [ ] **Step 1** : 00 — prérequis PHP 8.3 et versions installées par les tasks 2 et 2b (Twig 3, illuminate/view 12, Symfony 6.4) ; vider `application/include/vues/cache/` au déploiement.
- [ ] **Step 2** : 03.a / 03.b — syntaxe et classes à jour (Twig 3), ordre view > layout > system, cache ; renvoi vers l'annexe 20 pour les noms.
- [ ] **Step 3** : 04 — corriger `include/action/` en `include/actions/` ; signaler le changement de comportement : `Action::render(..., 'twig')` rend désormais vraiment en Twig (avant : argument ignoré) — exige le gabarit `.html.twig`, échappement automatique de Twig ; Blade : `{{ }}` brut, `{{{ }}}` échappé (comportement Blade 4 conservé) ; définition des actions (gabarit appelé par une fonction depuis un gabarit mère, accès dynamique possible à la base), convention `<Objet>Action::render…()` → `action-…`, exemple aligné sur le test de la task 2.
- [ ] **Step 4** : commit `docs: vues, actions et installation pour PHP 8.3`.

---

### Task 4 : tableau de classement des différences B25 → framework (B1, sans report)

**Files :** Create `B25_FR/ACTUAL/docs/superpowers/specs/2026-10-31-sand-report-b25-classement.md` (copie dans `docs/superpowers/specs/` du dépôt SAND depuis le 01/11/2026).

- [ ] **Step 1** : copier `B25_FR/ACTUAL/application/__sand/application/{class,objets}` et `<clone>/application/{class,objets}` dans le scratchpad, supprimer les `\r`, normaliser `B25\SAND_Module\Classe` → `MVC\Classe` et `B25\SAND_Module\Object` → `MVC\Object`, comparer fichier par fichier (`diff -w`).
- [ ] **Step 2** : pour chaque différence (25 fichiers : `class/{Asynchonous,Bdd,Browser,Caracter,Controlleur,ControlleurAction,Dispacher,Dumper,HttpMethod,Logger,Modele,Modular,ModularRegister,TwigControlleurAction,Url,Vue}.php`, `class/Implement/{Action,Conduit,HttpReponse,RestReponse}.php`, `objets/{Environment,Ldap,Session,XssToken}.php`, `objets/Guard.php` absent du framework), une ligne : fichier, extrait résumé, classement **à reporter / propre à B25 / à discuter**, justification. Tenir compte de la task 2 (fichiers déjà réécrits).
- [ ] **Step 3** : **STOP** : soumettre le tableau à Emmanuel. Aucun report avant sa validation.

---

### Task 5 : report des corrections validées (B2)

Exécutée seulement après validation du tableau de la task 4 ; un brief propre sera produit à partir du tableau validé (groupes : Session/Guard, Bdd, Url/routage, Vue/Action, divers), un commit par groupe, test PHPUnit quand le comportement est testable, `php -l` sur chaque fichier.

---

## Vérification finale

`git -C <clone> log --oneline main..feature/convention-php83` : commits des tasks 1 à 3 (et 5) ; tests de rendu verts sous PHP 8.3 ; instructions de push remises à Emmanuel.

---

### Task 6 : intégration de l'API B25 (`application/__api`) dans le framework — routes wp-json et Ereferer, sans secret

Ajoutée le 31/10/2026 à la demande d'Emmanuel ; à exécuter après la task 6 du sous-projet 1 des tests B25 (build) et après les tasks 2 et 3 de ce plan.

**Source (lecture seule) :** `B25_FR/ACTUAL/application/__api/` — `component/{Auth,Error,Response,Router,RouteRegistry}.php`, `config/routes.php`, `controller/AbstractController.php`, `controller/v1/Ereferer.php`, `controller/wp/v2/{Categories,Comments,Pages,Posts,Tags}.php`, `parameters.php`, `README.md`.

**Périmètre :** seulement le socle de l'API (composants, contrôleur abstrait), la résolution des chemins `/wp-json/{namespace}/{resource}/{id?}` (et `/api/…`), les contrôleurs `wp/v2/*` et `v1/Ereferer`. Les contrôleurs propres à B25 (`v1/Artistes`, `Associations`, `Breves`…) ne sont pas repris. `output/logs/` n'est pas repris.

**Secrets :** `config/api_keys.php` n'est **jamais** copié. Le framework reçoit `config/api_keys.php.skel` (structure `define('API_KEYS', [hash('sha256', '<clé générée par bin2hex(random_bytes(32))>') => 'ereferer'])`, valeur factice), ajouté au `.gitignore` pour la version réelle ; `parameters.php` est passé au même régime s'il contient des valeurs d'environnement. Vérification avant commit : `git diff --cached` ne contient aucune chaîne de plus de 20 caractères hexadécimaux hors du `.skel`.

- [ ] **Step 1** : lire la source, lister ses dépendances à B25 (constantes, `B25\…`, BDD, tables) et décider pour chacune : abstraite par configuration, ou retirée.
- [ ] **Step 2** : test PHPUnit de résolution des routes (`/wp-json/wp/v2/posts` → `Posts`, `/wp-json/v1/ereferer` → `Ereferer`, `/api/v1/ereferer` idem, chemin inconnu → 404 JSON) et du refus sans clé (401), RED.
- [ ] **Step 3** : intégrer dans le framework dans le dossier **`api/` à la racine du dépôt** (décision d'Emmanuel du 31/10/2026 ; renommé `Api/` → `api/` et `Contrat/` → `contract/` le 01/11/2026 : `api/component/`, `api/contract/`, `api/config/`, `api/controller/{v1,wp/v2}/`, `api/README.md`), espace de noms `MVC\Api\…` (`MVC\Api\Contract\…`) déclaré en PSR-4 dans `composer.json` (`"MVC\\Api\\" : "./api"`), conformément à l'annexe 20 (l'API REST est le seul endroit où d'autres verbes que GET/POST sont admis).
- [ ] **Step 3b** : **mettre à jour `application/class/Application.php` pour que la route de l'API s'exécute en premier** (décision d'Emmanuel) : au tout début de `launch()` (avant `new Controlleur($this)`, et avant toute logique de maintenance ou de page), si le chemin de la requête commence par `/wp-json/` ou `/api/`, déléguer au routeur de l'API (`MVC\Api\Component\Router`), qui envoie la réponse JSON et termine ; sinon, comportement actuel inchangé. Ajouter au test du step 2 : une requête `/wp-json/wp/v2/posts` n'instancie jamais `Controlleur`, et une requête `/index` suit toujours le chemin MVC. Vérifier aussi `public/.htaccess` (les chemins `/wp-json/…` doivent arriver à `index.php`).
- [ ] **Step 4** : GREEN ; `php -l` ; contrôle des secrets.
- [ ] **Step 5** : doc `data/docs/` (compléter `17.` ou nouvelle page « API wp-json et Ereferer ») et commit `feat: API wp-json et Ereferer (sans secret)`.
