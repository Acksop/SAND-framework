# Classement des différences B25 → SAND-framework (sans report)

Date : 31/10/2026. Chantier `2026-10-31-sand-framework-convention-php83`, tâche 4. **Aucun report n'a été fait** : ce tableau attend la validation d'Emmanuel.

## Méthode

- B25 : `B25_FR/ACTUAL/application/__sand/application/{class,objets}` (lecture seule).
- Framework : `SAND-framework/application/{class,objets}`, branche `feature/convention-php83`, HEAD `43906f9` (lecture seule).
- Les deux arborescences ont été copiées dans un scratchpad. Les `\r` ont été retirés et les espaces de noms normalisés (`B25\SAND_Module\Classe` → `MVC\Classe`, `B25\SAND_Module\Object` → `MVC\Object`, avec un ou deux antislashs). Une seconde passe a ramené les constantes `SAND_MODULE_*`, `SAND_MODULES_PATH`, `SAND_LOG_PATH` et `SAND_OUTPUT_PATH` à leur nom dans le framework, pour isoler les différences de fond. Comparaison faite avec `diff -w`, fichier par fichier.
- Les 25 fichiers du brief diffèrent tous (aucun n'est identique). `objets/Guard.php` n'existe que dans B25, `class/Rendu.php` que dans le framework.
- Pour les fichiers réécrits par la tâche 2 (`Vue`, `Implement/Action`, `TwigControlleurAction`, `Implement/Conduit`, `Rendu`), la comparaison porte sur la version **actuelle** du framework. Quand le framework est déjà en avance, la ligne est classée « Propre à B25 » avec la mention **dépassé** : rien à reporter, B25 profitera de la version du framework le jour où il s'alignera.

## Tableau principal

| # | Fichier | Différence (résumé) | Classement | Justification | Groupe de commit proposé |
|---|---------|---------------------|------------|---------------|--------------------------|
| 1 | objets/Session.php | `sessionStart()` n'appelle `session_start()` que si la session n'est pas active (le framework l'appelle toujours) | À reporter | Évite l'avertissement « session already active » quand l'hôte a déjà ouvert la session. Correction générique | Session/Guard |
| 2 | objets/Session.php | `authentification()` lit `id_utilisateur`, `type_compte` (→ `acl_admin`) et pose `token_xss`. Le framework pose `user_login = 'anonymous'` et `acl_admin = 0` | Propre à B25 | Clés de session et types de compte de B25 | Session/Guard |
| 3 | objets/Session.php | `user_login` se replie sur `id_utilisateur` si `identifiant` manque | Propre à B25 | Correction du 25/10 liée aux voies de connexion de B25 (clés `identifiant`/`id_utilisateur`), sans équivalent dans le framework | Session/Guard |
| 4 | objets/Session.php | `getUser()` et `getIdUser()` ajoutés | À discuter | `getUser()` est générique. `getIdUser()` suppose la clé B25 `id_utilisateur` | Session/Guard |
| 5 | objets/Session.php | `getType()`, `getIntType()` et 27 méthodes `is_*()` (journaliste, artiste, bar, commerce…) | Propre à B25 | Types de compte B25 et B25_PRO (0 à 23) | Session/Guard |
| 6 | objets/Session.php | `checkSession()` délègue le refus à `refuserAcces()`. Dans le framework, `checkSession()` appelle `Url::link_rewrite()` sans qualification, ce qui vise la classe inexistante `MVC\Object\Url` (fatal) | À reporter | Centraliser le refus et qualifier `\MVC\Classe\Url` corrige un fatal du framework. Correction du 25/10 | Session/Guard |
| 7 | objets/Session.php | `refuserAcces()` : un visiteur non connecté ou une session expirée vont vers `identification` par `\B25\Classe\Url`, sinon vers `error-access-denied` | À discuter | Le principe (connexion avant refus) est générique, mais l'implémentation appelle une classe de B25 | Session/Guard |
| 8 | objets/Session.php | `checkACL_admin()` ne vérifie plus `acl_admin != 1` | Propre à B25 | Dans B25, `acl_admin` est le type de compte, pas un drapeau administrateur | Session/Guard |
| 9 | objets/Session.php | `casAuthentification()` (phpCAS) absente de B25 | Propre à B25 | B25 n'utilise pas CAS. Le framework la garde | Session/Guard |
| 10 | objets/Guard.php | Classe absente du framework : `admin_only()`, `journaliste_only()`… (une méthode par type 0 à 23), refus par `Session::refuserAcces()` | Propre à B25 | Rôles et types de compte de B25. Voir le point à discuter D2 pour une version générique | Session/Guard |
| 11 | objets/XssToken.php | `getNew(): string` sans paramètre, renvoie le jeton. Le framework a `getNew($title,$message,$type)`, des paramètres inutilisés et aucun retour | À reporter | Corrige la signature et le retour. B25 s'en sert pour `token_xss` | Session/Guard |
| 12 | class/Bdd.php | Connexions nommées : `new Bdd('<nom>')` lit `DSN_BDD_<NOM>`/`USER_`/`PASS_`, puis la forme historique `DSN_<NOM>` (`bdd1`, `bdd2`). Une connexion PDO par nom est gardée pour la requête. Ajout de `connexion()` et `identifiants()`, et d'une exception explicite si la connexion est inconnue | À reporter | Générique, compatible avec `default`, `bdd1` et `bdd2` | Bdd |
| 13 | class/Bdd.php | `Bdd::pourPage(array $page)` : connexion déclarée par la clé `bdd` du `.model`, sinon `default` | À reporter | Générique. Reporter aussi la règle « définition de page issue d'un `.model` uniquement » | Bdd |
| 14 | class/Bdd.php | `faireBindRequete()` teste le retour de `execute()`, journalise `errorInfo()` et renvoie 0 | À reporter | Les erreurs SQL ne sont plus silencieuses | Bdd |
| 15 | class/Bdd.php | `exploiterResultat()` appelle la fonction globale `check_ChaineDeCaracteresDownload($row,'max')` puis `Caracter::normalise_…` | Propre à B25 | Cette fonction globale est définie dans `B25/data/BDDdataControl_download.php`. Elle n'a de toute façon aucun effet : la boucle modifie une copie de `$data` | Bdd |
| 16 | class/Bdd.php | `exploiterNbResultat($req)` (renvoie `rowCount()`) | À reporter | Petit ajout générique | Bdd |
| 17 | class/Caracter.php | `check_ChaineDeCaracteresDownload()` : `fixUTF8` puis `html_entity_decode` | À discuter | Générique (dépend de `forceutf8`, déjà présent dans le framework), mais répond à des données B25 encodées en entités | Divers |
| 18 | class/Url.php | La chaîne de requête de l'URL est relue dans `$_GET` | À discuter | Utile derrière le routage de l'hôte, mais écrase `$_GET` et provoque un avertissement si un paramètre n'a pas de `=` | Url/routage |
| 19 | class/Url.php | `isset($urlParts[0])` avant la comparaison au sous-dossier | À reporter | Supprime un avertissement « undefined offset » à la racine | Url/routage |
| 20 | class/Url.php | Requête « App » sans `*RestReponse`/`*HttpReponse` : repli sur la page MVC (`lcfirst`) si elle existe | À reporter | Correction du 25/10 : un client curl ne provoque plus de fatal | Url/routage |
| 21 | class/Controlleur.php | `estFichierReponse()` : la voie REST/HTTP n'est prise que si le fichier résolu est une réponse existante | À reporter | Complète la ligne 20 (même correction) | Url/routage |
| 22 | class/Url.php | Contrôleur 404 introuvable : erreur journalisée, code 500, chemins du serveur affichés seulement en `DEBUG` | À reporter | Ne divulgue plus les chemins en production (remplace le `TODO: Send SANDerror`) | Url/routage |
| 23 | class/Url.php | `link_rewrite_slashParam()` vérifie `is_array($params)` | À reporter | Évite un avertissement dans `foreach` sur `null` | Url/routage |
| 24 | class/Controlleur.php | `new $reponseHttp($url, $data)` remplace `new …()` puis `instanciate()` | À reporter | Va avec la ligne 25 | Url/routage |
| 25 | class/Implement/HttpReponse.php, RestReponse.php | Constructeur renommé `__construct` (le framework a la faute `__contruct`), paramètres facultatifs, `instanciate()` conservée | À reporter | Corrige la cause réelle du FIXME du framework, sans casser les appels existants | Url/routage |
| 26 | Controlleur, ControlleurAction, Dispacher, Logger, Modele, Modular, Url, Vue | Constantes `SAND_MODULE_*_PATH`, `SAND_MODULES_PATH`, `SAND_LOG_PATH`, `SAND_OUTPUT_PATH` au lieu de `*_PATH` | Propre à B25 | Préfixes propres à l'intégration dans B25 (seule différence de `Dispacher` et `Logger`) | Divers |
| 27 | class/Modele.php | `trim()` des clés et valeurs lues dans le `.model` | À reporter | Évite les espaces parasites dans `$page` | Divers |
| 28 | class/Modele.php | Analyse de sous-tableaux `[...]` dans une valeur | À discuter | Code bogué : motif `#[.*]#` (classe de caractères), `trim(arraysubVal)` sans `$`, confusion `$sub_array`/`$subarray`. À réécrire ou à abandonner | Divers |
| 29 | class/Browser.php | `detection_mobile()` (en-têtes WAP, UA `Mobile`/`Opera Mini`, `get_browser()`) | À discuter | Générique, mais `get_browser()` exige browscap dans php.ini | Divers |
| 30 | class/Vue.php | `Vue::$titre_page` et `$description_page` retenus à la première vue, lus par `B25\Classe\Application` pour le `<title>` | À discuter | Mécanisme d'hôte qui embarque un module. Utile à tout hôte, mais seul B25 s'en sert | Vue/Action |
| 31 | class/Vue.php | Rendu windwalker 3 en ligne (`SplPriorityQueue`, `TwigRenderer`, `BladeRenderer`), sans les 3 paramètres facultatifs | Propre à B25 (dépassé) | Remplacé par `Rendu::rendre()` et les paramètres facultatifs de la tâche 2 | Vue/Action |
| 32 | class/Implement/Action.php | `renderTwig()`/`renderBlade()` acceptent un 3ᵉ paramètre `$dir` (racine des vues) | À discuter | Utilisé par `lite-SAND-module-skel` (actions de menu). Le framework a `racineVues()` surchargeable et un 3ᵉ paramètre `$engine` sur `render()` | Vue/Action |
| 33 | class/Implement/Action.php | Rendu windwalker 3 en ligne | Propre à B25 (dépassé) | Remplacé par `Rendu` (tâche 2) | Vue/Action |
| 34 | class/ControlleurAction.php | Fichier d'action absent : journalisation et `null`. Action qui lève une exception : `try/catch`, journalisation, relance en `DEBUG` | À reporter | Remplit le `TODO` du framework. À fusionner avec le test `class_exists` du framework | Vue/Action |
| 35 | class/TwigControlleurAction.php | Copie de la logique d'`inserer()`, et `action` sans `is_safe` | Propre à B25 (dépassé) | Le framework délègue à `ControlleurAction::inserer()` (tâche 2). La journalisation arrivera par la ligne 34 | Vue/Action |
| 36 | class/Implement/Conduit.php | Pas de `#[\AllowDynamicProperties]`, `$templateData` sans valeur par défaut | Propre à B25 (dépassé) | Le framework (tâche 2) est conforme à PHP 8.2+ | Vue/Action |
| 37 | class/Asynchonous.php | API `addJs()`/`printJs()`. Le framework a `addBodyCss`, `addHeaderJs`, `addFooterJs` et leurs `print*` | À discuter | B25 garde l'ancienne API, que seul `objets/Asynchronous.php` de B25 appelle | Divers |
| 38 | class/Dumper.php | `error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED)` au lieu de `E_ALL` | À discuter | Masquer les dépréciations va à l'encontre de la migration vers PHP 8.3 | Divers |
| 39 | class/Dumper.php | `show_globals()`, `display_errors()` et `needMoreTime()` absentes de B25 | Propre à B25 (dépassé) | Ajouts du framework | Divers |
| 40 | class/HttpMethod.php | `Logger::addLog('http.method', …)` commenté | À discuter | Une ligne de journal par requête : choix d'exploitation | Divers |
| 41 | class/Modular.php | Nom de classe en chaîne `'\\B25\\SAND_Module\\Classe\\Modular'` pour `ob_start()`, et `<br>` au lieu de `<br/>` | Propre à B25 | Espace de noms de B25 dans une chaîne, non ramené par la normalisation. Le reste est du style | Divers |
| 42 | objets/Environment.php, objets/Ldap.php | `<strong>` au lieu de `<b>`, `<br>` au lieu de `<br />` | Propre à B25 | Cosmétique, sans effet fonctionnel | Divers |

## Points à discuter

- **D1 (l. 4, `getUser`/`getIdUser`)** : faut-il ajouter `Session::getUser()` au framework, et `getIdUser()` avec une clé de session configurable (constante `SESSION_CLE_ID_UTILISATEUR` ?), ou laisser les deux à B25 ?
- **D2 (l. 7 et 10, `refuserAcces` et `Guard`)** : peut-on reporter `refuserAcces()` sous une forme générique, avec une page de connexion configurable (constante `PAGE_IDENTIFICATION` ?) au lieu d'appeler `\B25\Classe\Url` ? Faut-il un `Guard` générique (`Guard::exiger(int ...$types)`) en laissant les `*_only()` à B25 ?
- **D3 (l. 17, `Caracter::check_ChaineDeCaracteresDownload`)** : le framework doit-il proposer ce décodage des entités HTML pour les données lues en base, ou est-ce un correctif des données de B25 ?
- **D4 (l. 18, chaîne de requête → `$_GET`)** : le framework doit-il relire la chaîne de requête de l'URL dans `$_GET`, ou est-ce un contournement propre au routage de B25 ? Si on reporte : sans écraser les clés déjà présentes et en tolérant un paramètre sans `=` ?
- **D5 (l. 28, sous-tableaux du `.model`)** : la syntaxe `[...]` dans une valeur de `.model` est-elle voulue ? Si oui, on la réécrit et on la teste. Si non, on la retire de B25.
- **D6 (l. 29, `Browser::detection_mobile`)** : faut-il l'ajouter au framework, et peut-on dépendre de `get_browser()` (browscap) ou faut-il un repli sans browscap ?
- **D7 (l. 30, `Vue::$titre_page`)** : le framework doit-il exposer le titre et la description de la page rendue pour un site hôte, et sous quelle forme (propriétés statiques ou accesseur) ?
- **D8 (l. 32, `$dir` de `renderTwig`/`renderBlade`)** : faut-il garder dans le framework un 3ᵉ paramètre facultatif `$dir` pour la compatibilité avec `lite-SAND-module-skel`, ou adapter le squelette à `racineVues()` surchargée ?
- **D9 (l. 37, `Asynchonous`)** : B25 doit-il adopter l'API à trois tampons du framework ? Faut-il garder `addJs()`/`printJs()` comme alias dépréciés dans le framework ?
- **D10 (l. 38, `Dumper`)** : en mode débogage, garde-t-on `E_ALL`, ce qui affiche les dépréciations utiles à la migration vers PHP 8.3 ?
- **D11 (l. 40, journal `http.method`)** : faut-il garder ce journal à chaque requête dans le framework, le commenter ou le soumettre à `DEBUG` ?

## Synthèse

| Classement | Lignes |
|------------|--------|
| À reporter | 16 (l. 1, 6, 11, 12, 13, 14, 16, 19, 20, 21, 22, 23, 24, 25, 27, 34) |
| Propre à B25 | 15, dont 6 « dépassé » (l. 2, 3, 5, 8, 9, 10, 15, 26, 31, 33, 35, 36, 39, 41, 42) |
| À discuter | 11 (l. 4, 7, 17, 18, 28, 29, 30, 32, 37, 38, 40) |
| **Total** | **42** |

Ordre de report proposé, après validation et un commit par groupe :

1. **Url/routage** (l. 19 à 25) : supprime des fatals et des fuites de chemins. Les lignes 24 et 25 vont ensemble.
2. **Session/Guard** (l. 1, 6, 11), puis D1 et D2 une fois tranchés. La ligne 6 corrige un fatal du framework.
3. **Bdd** (l. 12, 13, 14, 16), avec la documentation de `DSN_BDD_<NOM>` et de `pourPage()`.
4. **Vue/Action** (l. 34), fusionnée avec le `class_exists` du framework, puis D7 et D8.
5. **Divers** (l. 27), puis les points D3, D4, D5, D6, D9, D10 et D11 selon les réponses.

## Décisions d'Emmanuel (31/10/2026)

Validation : « pour le reste continue tes modifications » — les 16 reports et les recommandations de Claude sont retenus :

| Point | Décision |
|---|---|
| 16 lignes « À reporter » | Reporter, un commit par groupe, dans l'ordre Url/routage → Session/Guard → Bdd → Vue/Action → Divers |
| D1 | `Session::getUser()` et `getIdUser()` dans le framework, clé d'identifiant configurable (constante `SESSION_CLE_ID_UTILISATEUR`, défaut `id_user`) |
| D2 | `Session::refuserAcces()` générique (page de connexion configurable `PAGE_IDENTIFICATION`, défaut `index`) ; `Guard::exiger(...)` générique ; les `*_only()` restent à B25 |
| D3 | Non reporté (correctif des données de B25) |
| D4 | Reporter : chaîne de requête relue dans `$_GET` sans écraser les clés présentes, paramètre sans `=` toléré ; corriger aussi `Dispacher` pour qu'il passe au routeur le chemin sans chaîne de requête |
| D5 | **Syntaxe voulue (Emmanuel, 31/10)** : réécrire et tester, puis reporter (groupe Divers). Sémantique : dans une valeur `{…}`, chaque élément `[…]` devient un sous-tableau ; `[]` → `[]` ; `[cle=valeur, cle2=valeur2]` → tableau associatif ; `[a, b]` → liste. Usage : `arianeparams : {[], [id=4]}`, aligné sur `ariane`/`arianelink`, chaque sous-tableau étant passé à `Url::link_rewrite()`. Le code B25 actuel est bogué (motif `#[.*]#`, `trim(arraysubVal)`, `$sub_array`/`$subarray`) : signaler l'anomalie pour B25 |
| D6 | Reporter `detection_mobile()` avec repli sans browscap |
| D7 | Titre et description de la page exposés par accesseur |
| D8 | 3ᵉ paramètre facultatif `$dir` conservé sur `renderTwig()`/`renderBlade()` |
| D9 | `addJs()`/`printJs()` gardés comme alias dépréciés |
| D10 | `E_ALL` en mode débogage |
| D11 | Journal `http.method` seulement en `DEBUG` |
| Conduits | Norme applicative : conduits réservés aux appels AJAX (annexe 20) |
