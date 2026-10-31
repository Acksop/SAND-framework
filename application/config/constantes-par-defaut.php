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
