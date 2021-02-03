<?php

namespace MVC\Object;

/**
 * Class Session
 *  Classe de domaine portant sur les Sessions lors des accès à l'application
 * @package MVC\Object
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
        \MVC\Object\History::setPagePrecedente();
        \MVC\Object\Asynchronous::declare();
    }

    /**
     * Méthode permettant de lancer la session, obsolète depuis phpCAS 1.3.9
     */
    public static function sessionStart()
    {
        session_start();
    }

    /**
     * Méthode permettant de lancer l'authentification par le CAS de l'université
     * @throws \Exception
     */
    public static function authentification()
    {
        self::sessionStart();
        $_SESSION['user_login'] = 'root';
        $_SESSION['acl_admin'] = 0;
    }

    public static function casAuthentification()
    {
        if (ENV == "DEV") {
            // Load the settings from the central config file
            require_once CONFIG_PATH . DIRECTORY_SEPARATOR . 'cas-authentification-config.php';

            // Enable debugging
            \phpCAS::setLogger();
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
     * Méthode permettant de rédiriger le visiteur s'il n'est pas authentifier
     */
    public static function checkSession()
    {
        if (!isset($_SESSION['user_login'])) {
            header('location:' . Url::link_rewrite(false, 'index'));
            die();
        }
    }
    /**
     * Méthode permettant de rediriger le visiteur si son utilisateur n'as pas les droits administrateur
     */
    public static function checkACL_admin()
    {
        if (!isset($_SESSION['acl_admin'])) {
            header('location:'.\MVC\Classe\Url::link_rewrite(false, "error-access-denied", []));
            die();
        } elseif ($_SESSION['acl_admin'] != 1) {
            header('location:'.\MVC\Classe\Url::link_rewrite(false, "error-access-denied", []));
            die();
        }
    }

}