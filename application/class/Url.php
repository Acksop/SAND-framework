<?php

/**
 * Package MVC\Classe
 * @author Emmanuel ROY
 * @license  MIT-licence (open source)
 * @version 3.5
 */

namespace MVC\Classe;

class Url
{
    public $page;
    public $pageFile;
    public $registre;


    public function __construct($method, $appRequest)
    {

        //on créé le registre des modules d'applications tierces
        $this->registre = new \MVC\Classe\ModularRegister();

        //définition des parametres de base
        $page = array();
        $page['name'] = 'index';
        $page['description'] = "";
        $page['params'] = array();
        $page['control'] = false;


        $url = parse_url($_SERVER['REQUEST_URI']);
        $urlTrim = trim($url['path'], '/');
        $urlParts = explode('/', $urlTrim);
        //la chaîne de requête est relue dans $_GET (sans écraser les clés présentes)
        self::lireChaineRequete($url['query'] ?? '');

        //suppression des sous repertoires du BASE_SERVER_DIRECTORY
        $urlParts = self::retirerSousRepertoires($urlParts);

        //Récupération du nom de la page
        if (isset($urlParts[0])) {
            //il se peut que l'on ait des variable avec ? dans l'url
            $urlQuery = explode('?', $urlParts[0]);
            $urlQueryPageName = $urlQuery[0];
            ($urlQueryPageName == 'index' || $urlQueryPageName == '') ? $page['name'] = 'index' : $page['name'] = $urlQueryPageName;
            unset($urlParts[0]);
        } else {
            $page['name'] = 'index';
        }

        $page['name'] = strtolower($page['name']);

        //si c'est une page de controle de formulaire : on renomme la page
        if ($page['name'] == 'control') {
            $page['control'] = true;
            ($urlParts[1] == 'index' || $urlParts[1] == '') ? $page['name']='index' : $page['name']=$urlParts[1];
            unset($urlParts[1]);
        }

        //Mise en place du statut de l'application
        switch(APP_STATE){
            case "CLOSED":
                $page['name'] = "error-application-closed";
                break;
            case "MAINTAINED":
                $page['name'] = "error-application-maintained";
                break;
            case "OPEN":
                if(\MVC\Classe\Application::is_under_update()) {
                    $page['name'] = "error-application-maintained";
                    break;
                }
            default:
        }

        //vérification du nombre de parametres:
        $numParts = count($urlParts);
        //s'il n'existe pas autant de clé que de valeurs, ce peut ^etre un module symfony ou tout autre module
        if ($numParts%2 != 0) {
            //si un module symfony n'est pas reférencé avec le nom de la page, on renvoi une erreur
            if (!in_array($page['name'], $this->registre->getIndex())) {
                $page['name'] = 'error';
                $page['params'] = array();
                $this->page = $page;
                return;
            } else {
                foreach ($urlParts as $key => $value) {
                        $values[] = $value;
                        //$keys[] = $key;
                }
                //$page['params'] = array_combine($keys, $values);
                $page['params'] = $values;
            }

            //cas d'utilisation normal : il existe autant de clé que de valeurs
        } elseif ($numParts != 0) {
            // si c'est un module alors on charge un a un les parametres
            if (in_array($page['name'], $this->registre->getIndex())) {
                foreach ($urlParts as $key => $value) {
                    if ($key % 2 == 0) {
                        $values[] = $value;
                    } else {
                        $keys[] = $value;
                    }
                }
                $page['params'] = array_combine($keys, $values);
            // sinon c'est une page normal du framework
            } else {
                $values = array();
                $keys = array();
                if($page['control']){
                    foreach ($urlParts as $key => $value) {
                        if ($key % 2 == 1) {
                            $values[] = $value;
                        } else {
                            $keys[] = $value;
                        }
                    }
                }else {
                    foreach ($urlParts as $key => $value) {
                        if ($key % 2 == 0) {
                            $values[] = $value;
                        } else {
                            $keys[] = $value;
                        }
                    }
                }
                $page['params'] = array_combine($keys, $values);
            }
        }
        $page['name'] = lcfirst($page['name']);
        //fichier controlleur (MVC, traitement, REST ou HTTP), sinon controlleur d'erreur
        [$page, $pageFile] = UrlResolveur::resoudre($page, $method, $appRequest);
        $this->page = $page;
        $this->pageFile = $pageFile;
    }

    /**
     * Relit la chaîne de requête de l'URL dans $_GET (utile quand le routage de
     * l'hôte ne la transmet pas) : les clés déjà présentes ne sont pas écrasées
     * et un paramètre sans « = » vaut une chaîne vide.
     */
    private static function lireChaineRequete(string $query): void
    {
        if ($query === '') {
            return;
        }
        parse_str($query, $params);
        $_GET += $params;
    }

    /**
     * Retire du début du chemin les sous-répertoires de BASE_SERVER_DIRECTORY.
     */
    private static function retirerSousRepertoires(array $urlParts, string $base = BASE_SERVER_DIRECTORY): array
    {
        foreach (explode('/', $base) as $subDir) {
            if (isset($urlParts[0]) && $urlParts[0] == $subDir) {
                array_shift($urlParts);
            }
        }
        return $urlParts;
    }

    public static function asset_rewrite($urlAsset)
    {
        return UrlLiens::asset_rewrite($urlAsset);
    }

    public static function link_rewrite($isControlPatern, $page, $params = array())
    {
        return UrlLiens::link_rewrite($isControlPatern, $page, $params);
    }

    public static function absolute_link_rewrite($isControlPatern, $page, $params = array())
    {
        return UrlLiens::absolute_link_rewrite($isControlPatern, $page, $params);
    }

    public static function getBaseDirectory(){
        return '/' . BASE_SERVER_DIRECTORY;
    }

    public static function getRootDirectoryUrl()
    {
        return UrlLiens::getRootDirectoryUrl();
    }

    public static function getPageName(){

        $url = parse_url($_SERVER['REQUEST_URI']);
        $urlTrim = trim($url['path'], '/');
        $urlParts = explode('/', $urlTrim);

        //suppression des sous repertoires du BASE_SERVER_DIRECTORY
        $urlParts = self::retirerSousRepertoires($urlParts);

        //Récupération du nom de la page
        if (isset($urlParts[0])) {
            //il se peut que l'on ait des variable avec ? dans l'url
            $urlQuery = explode('?', $urlParts[0]);
            $urlQueryPageName = $urlQuery[0];
            ($urlQueryPageName == 'index' || $urlQueryPageName == '') ? $page['name'] = 'index' : $page['name'] = $urlQueryPageName;
            unset($urlParts[0]);
        } else {
            $page['name'] = 'index';
        }

        $page['name'] = strtolower($page['name']);

        //si c'est une page de controle de formulaire : on renomme la page
        if ($page['name'] == 'control') {
            $page['control'] = true;
            ($urlParts[1] == 'index' || $urlParts[1] == '') ? $page['name']='index' : $page['name']=$urlParts[1];
            unset($urlParts[1]);
        }
        return $page;
    }

    /**
     * Obtiens le fragment depuis une variable serveur,
     * ce qui est selon moi possible avec une bonne configuration serveur
     * sauf si le fragment est bloqué au niveau du navigateur (rétention d'informations)
     *
     * Selon QASTACK
     * => https://qastack.fr/programming/2317508/get-fragment-value-after-hash-from-a-url-in-php
     * ce n'est pas possible avec HTTP "standard" car cette valeur n'est jamais envoyée au serveur
     * (par conséquent, elle ne sera pas disponible dans $_SERVER["REQUEST_URI"]ou similaire variables prédéfinies)
     */
    public static function getFragment(){
        $fragment = parse_url($_SERVER['REQUEST_URI'],PHP_URL_FRAGMENT);
        return $fragment;
    }
}
