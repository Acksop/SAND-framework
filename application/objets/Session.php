<?php

namespace SAND\Object;

/**
 * Class Session
 *  Classe de domaine portant sur les Sessions lors des accès à l'application
 * @package SAND\Object
 */
class Session
{
    /**
     * Méthode appelée dans toutes les page nécessitant une authentification
     */
    public static function createAndTestSession()
    {
        self::authentification();
        self::checkSession();
        \SAND\Object\History::setPagePrecedente();
        \SAND\Object\Asynchronous::declare();
    }

    /**
     * Méthode permettant de lancer la session, obsolète depuis phpCAS 1.3.9.
     * Une session déjà ouverte (par l'hôte, par exemple) n'est pas rouverte.
     */
    public static function sessionStart()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    /**
     * Identifiant de connexion de l'utilisateur ($_SESSION['user_login']).
     */
    public static function getUser(): mixed
    {
        return $_SESSION['user_login'] ?? null;
    }

    /**
     * Identifiant de l'utilisateur, lu sous la clé SESSION_CLE_ID_UTILISATEUR
     * (par défaut 'id_user'). Vaut null si aucun utilisateur n'est connecté.
     */
    public static function getIdUser(): mixed
    {
        return $_SESSION[SESSION_CLE_ID_UTILISATEUR] ?? null;
    }

    /**
     * Méthode permettant de lancer l'authentification par le CAS de l'université
     * @throws \Exception
     */
    public static function authentification()
    {
        self::sessionStart();
        $_SESSION['user_login'] = 'anonymous';
        $_SESSION[SESSION_CLE_ACL] = 0;
    }

    public static function casAuthentification()
    {
        if (ENV == "DEV") {
            // Load the settings from the central config file
            require_once CONFIG_PATH . DIRECTORY_SEPARATOR . 'cas-authentification-config.php';

            // Enable debugging
            \phpCAS::setDebug(LOG_PATH.'/cas.log');
            // Enable verbose error messages. Disable in production!
            \phpCAS::setVerbose(true);

            // Initialize phpCAS
            \phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_context);

            // For production use set the CA certificate that is the issuer of the cert
            // on the CAS server and uncomment the line below
            // phpCAS::setCasServerCACert($cas_server_ca_cert_path);

            // For quick testing you can disable SSL validation of the CAS server.
            // THIS SETTING IS NOT RECOMMENDED FOR PRODUCTION.
            // VALIDATING THE CAS SERVER IS CRUCIAL TO THE SECURITY OF THE CAS PROTOCOL!
            \phpCAS::setNoCasServerValidation();

            // force CAS authentication
            \phpCAS::forceAuthentication();

            $_SESSION['user_login'] = \phpCAS::getAttribute('uid');

        } elseif (ENV == "PROD" || ENV == "PREPROD") {
            // Load the settings from the central config file
            require_once CONFIG_PATH . DIRECTORY_SEPARATOR . 'cas-authentification-config.php';

            \phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_context);

            // For production use set the CA certificate that is the issuer of the cert
            // on the CAS server and uncomment the line below
            \phpCAS::setCasServerCACert($cas_server_ca_cert_path);

            // force CAS authentication
            \phpCAS::forceAuthentication();

            $_SESSION['user_login'] = \phpCAS::getAttribute('uid');
        }
    }

    /**
     * Méthode permettant de rédiriger le visiteur s'il n'est pas authentifier.
     * Vérifie que la session est initialisée (user_login posé, 'anonymous'
     * compris) : les pages « authentification: yes » du squelette restent
     * ouvertes au visiteur anonyme. Une page réservée aux utilisateurs
     * connectés teste Session::estConnecte() ou passe par un Guard.
     */
    public static function checkSession()
    {
        if (!isset($_SESSION['user_login'])) {
            self::refuserAcces();
        }
    }

    /**
     * Méthode permettant de rediriger le visiteur si son utilisateur n'as pas les droits administrateur
     * ($_SESSION[SESSION_CLE_ACL] = 1). Le refus passe par refuserAcces().
     */
    public static function checkACL_admin()
    {
        Guard::exiger(1);
    }

    /**
     * Critère unique de connexion : user_login présent, non vide et différent
     * de 'anonymous' (CAS, authentification de l'application), ou identifiant
     * présent et non nul sous SESSION_CLE_ID_UTILISATEUR (hôte qui pose un
     * identifiant numérique). Un identifiant 0 compte comme connecté.
     */
    public static function estConnecte(): bool
    {
        $login = $_SESSION['user_login'] ?? null;
        if (is_scalar($login) && (string) $login !== '' && $login !== 'anonymous') {
            return true;
        }

        return isset($_SESSION[SESSION_CLE_ID_UTILISATEUR]);
    }

    /**
     * Refuse l'accès et termine la requête : un visiteur non connecté (ou dont
     * la session a expiré) est redirigé vers PAGE_IDENTIFICATION, un
     * utilisateur connecté vers la page error-access-denied. Si la cible est
     * la page courante, aucune redirection (pas de boucle) : réponse 403.
     */
    public static function refuserAcces(): never
    {
        $cible = self::cibleRefus();
        if ($cible === null) {
            http_response_code(403);
        } else {
            header('location:' . $cible);
        }
        die();
    }

    /**
     * Page de redirection d'un refus d'accès, ou null si toutes les cibles
     * possibles sont la page courante.
     */
    private static function cibleRefus(): ?string
    {
        $refus = \SAND\Classe\Url::link_rewrite(false, 'error-access-denied', []);
        $cibles = self::estConnecte()
            ? [$refus]
            : [\SAND\Classe\Url::link_rewrite(false, PAGE_IDENTIFICATION), $refus];
        foreach ($cibles as $cible) {
            if (!self::estPageCourante($cible)) {
                return $cible;
            }
        }

        return null;
    }

    private static function estPageCourante(string $cible): bool
    {
        $courante = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);

        return is_string($courante) && $courante === parse_url($cible, PHP_URL_PATH);
    }
}
