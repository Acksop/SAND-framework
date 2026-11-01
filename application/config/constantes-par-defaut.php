<?php

/**
 * Valeurs par défaut, surchargeables dans app-parameters.php.
 *
 * Chargé en dernier par l'autoload Composer : une constante déjà définie
 * par les fichiers de paramètres n'est pas redéfinie.
 */

// Mode débogage : détail des erreurs (chemins du serveur compris)
defined('DEBUG') or define('DEBUG', false);

// Clé de $_SESSION qui porte l'identifiant de l'utilisateur connecté
// (Session::getIdUser(), Session::refuserAcces())
defined('SESSION_CLE_ID_UTILISATEUR') or define('SESSION_CLE_ID_UTILISATEUR', 'id_user');

// Clé de $_SESSION qui porte le profil (ACL) lu par Guard::exiger()
defined('SESSION_CLE_ACL') or define('SESSION_CLE_ACL', 'acl_admin');

// Page de connexion vers laquelle Session::refuserAcces() redirige un
// visiteur non connecté ou dont la session a expiré
defined('PAGE_IDENTIFICATION') or define('PAGE_IDENTIFICATION', 'index');

// Noms de connexion acceptés par Bdd (new Bdd('<nom>'), Bdd::pourPage()) :
// tout autre nom est refusé, même si ses constantes DSN_BDD_<NOM> existent
defined('BDD_CONNEXIONS_AUTORISEES') or define('BDD_CONNEXIONS_AUTORISEES', ['default', 'bdd1', 'bdd2']);

// API wp-json (api/) : fichier des clés (empreintes SHA-256, jamais versionné,
// modèle api/config/api_keys.php.skel) et fichier des routes explicites
defined('API_FICHIER_CLES') or define('API_FICHIER_CLES', dirname(__DIR__, 2) . '/api/config/api_keys.php');
defined('API_FICHIER_ROUTES') or define('API_FICHIER_ROUTES', dirname(__DIR__, 2) . '/api/config/routes.php');

// Classe de l'hôte qui implémente MVC\Api\Contract\DepotSoumissions (route Ereferer) ;
// null : la route /wp-json/v1/ereferer répond 501
defined('API_DEPOT_SOUMISSIONS') or define('API_DEPOT_SOUMISSIONS', null);

// Taille maximale (octets) du corps d'une requête d'API ; au-delà : 413
defined('API_TAILLE_MAX_CORPS') or define('API_TAILLE_MAX_CORPS', 1048576);

// L'API (/wp-json/, /api/) reste joignable pendant une maintenance du site ;
// false : elle répond 503 en JSON tant que dure la maintenance
defined('API_PENDANT_MAINTENANCE') or define('API_PENDANT_MAINTENANCE', true);
