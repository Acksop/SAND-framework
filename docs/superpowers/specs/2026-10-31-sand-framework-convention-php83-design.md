# SAND-framework : convention de nommage, report des corrections de B25, Blade et Twig pour PHP 8.3

Date : 31/10/2026 — Statut : design validé par Emmanuel en conversation, spec en attente de relecture.
Dépôt : `G:/Professionnel/OPENSOURCE - Projets/SAND-framework` (clone de https://github.com/Acksop/SAND-framework, branche `main`, dernier commit `8b50d36`).
À lancer **après** le sous-projet 1 des tests B25 (`2026-10-31-tests-behat-phpunit-build-design.md`).

## Contraintes

- Travail sur la branche `feature/convention-php83` ; commits locaux ; **le push est fait par Emmanuel**.
- `B25_FR/ACTUAL/application/__sand` et `B25_PRO/…/__sand` ne sont **jamais modifiés** par ce chantier.
- Aucun secret : les `*-parameters.php` restent non versionnés (modèles `.skel`).
- Documentation en français ; fichiers de code < 300 lignes ; PSR-1/PSR-12.
- Vérifications dans le conteneur `dev-b25-lamp-php83` (PHP 8.3) : le clone n'y est pas monté ; on le copie dans un dossier de travail du conteneur ou on monte le dossier à la demande (décision à l'exécution, sans modifier `docker-compose.yml` sans accord).

## A. Documentation

1. Nouvelle annexe `data/docs/20. Annexe - Convention de nommage et méthodes HTTP.md`, illustrée par SIHAM-HEBERGES-APP (version à peine plus ancienne du framework) :
   - **Pages** : même nom en kebab-case pour `include/modeles/<page>.model`, `include/controlleurs/<page>.php` et `include/vues/view/<page>.blade.php` ; **premier segment = Guard** qui protège la page (`admin-…` → `checkACL_admin`) ; puis objet et opération (`admin-user-list`, `heberge-create-from-individu`) ; pages publiques sans préfixe (`index`, `cgu`, `error-access-denied`).
   - **Traitements** (POST + PRG) : `traitements/<guard>-<verbe>-<objet>.php`, ou `<verbe>-<objet>.php` hors Guard.
   - **Actions** : gabarits appelés par une fonction depuis un gabarit mère, avec accès dynamique possible à la base ; classe `include/actions/<Objet>Action.php` (`Implement\Action`), méthodes `render…()`, gabarits `view/action-<…>.blade.php`.
   - **Conduits** (AJAX) : `include/conduits/<Type>Conduit.php`, gabarits `view/ajax-conduit-<…>.blade.php`.
   - **Vues** : `include/vues/{view,layout,system,cache}`, priorités view 300 > layout 200 > system 100 ; une arborescence de vues **par interface** quand l'application en a plusieurs (WEB, MOBILE…).
   - **Méthodes HTTP** : GET pour lire, POST (+PRG) pour écrire ; pas de PUT ni DELETE hors API REST (`*RestReponse`), parce que HTTP/1.1 provoque trop d'erreurs avec ces verbes sur le Web (proxys, pare-feu, formulaires HTML) ; GET/POST sont plus sûrs, au prix d'un peu de performance.
2. Mise à jour des documents touchés par la montée de versions : `00` (installation : PHP 8.3), `03.a` (Twig 3), `03.b` (Blade), `04` (actions), et lien vers l'annexe depuis `README.md`.

## C. Blade et Twig pour PHP 8.3 (fait avant B)

- Montée de `twig/twig` 1.* → 3.x, `illuminate/view` 4.* → version supportée en PHP 8.3, `windwalker/renderer` → version compatible (ou remplacement par un adaptateur minimal maison si aucune version ne supporte les nouveaux moteurs) ; autres paquets (`symfony/*`, `neitanod/forceutf8`) montés seulement si Composer l'exige.
- Adaptation du code du framework qui utilise les anciennes API (`Twig_Environment`, `Twig_Extension`, `TwigControlleurAction`, `Vue`, `Implement\Action`).
- **Mêmes fonctionnalités** vérifiées par des tests PHPUnit de rendu (`tests/`) : vue Blade avec `@extends` d'un layout et du system, vue Twig équivalente, appel d'une action depuis un gabarit mère (Blade et Twig), écriture et réutilisation du cache.
- Critère : tests verts sous PHP 8.3, aucun `Deprecated` émis par le rendu.

## B. Report des corrections de B25 vers le framework (au cas par cas)

1. Comparaison, espaces de noms normalisés (`B25\SAND_Module\…` ↔ `MVC\…`), des 25 fichiers qui diffèrent (`class/` et `objets/`, dont `Session.php`, `Bdd`, `Url`, `Asynchonous`, `Modular`, `Vue`, `Browser`, `Implement/Action`, `TwigControlleurAction`, et `objets/Guard.php` présent seulement dans B25).
2. **Tableau de classement soumis à Emmanuel avant tout report** : pour chaque différence, « à reporter » (correction générique), « propre à B25 » (constantes `SAND_MODULE_*`, chemins, B25) ou « à discuter ». Corrections connues à examiner : `Session::refuserAcces()` (refus du Guard ou de la session), repli de `user_login` sur `id_utilisateur`, requête « App » sans `*RestReponse`/`*HttpReponse` servie comme page MVC (`Url`, `Controlleur::estFichierReponse()`), registre des connexions BDD nommées (`Bdd::identifiants`, `Bdd::pourPage`), `Guard`.
3. **Actions** : vérifier que `Implement\Action` et `TwigControlleurAction` reportés respectent la définition (gabarit appelé depuis un gabarit mère, accès BDD dynamique possible) et la convention `action-…`.
4. Un commit par groupe cohérent (Session/Guard, Bdd, Url/routage, Vue/Action…), chacun couvert par un test quand le comportement est testable.

## Livraison

- Branche `feature/convention-php83` prête, résumé des commits et du tableau de B, instructions de push pour Emmanuel.
- Hors périmètre : modifications de B25 ; montée de Symfony au-delà de ce qu'exige Composer ; publication.
