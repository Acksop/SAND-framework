<?php

/**
 * Package SAND\Classe
 * @author Emmanuel ROY
 * @license  MIT-licence (open source)
 * @version 3.5
 */

namespace SAND\Classe;

class Url
{
    public $page;
    public $pageFile;
    public $registre;


    public function __construct($method, $appRequest)
    {

        //on créé le registre des modules d'applications tierces
        $this->registre = new \SAND\Classe\ModularRegister();

        //définition des parametres de base
        $page = array();
        $page['name'] = 'index';
        $page['description'] = "";
        $page['params'] = array();
        $page['control'] = false;


        $url = parse_url($_SERVER['REQUEST_URI']);
        $urlTrim = trim($url['path'], '/');
        $urlParts = explode('/', $urlTrim);

        //suppression des sous repertoires du BASE_SERVER_DIRECTORY
        $basePath = explode( '/', BASE_SERVER_DIRECTORY);
        foreach($basePath as $subDir) {
            if ($urlParts[0] == $subDir) {
                array_shift($urlParts);
            }
        }

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
                if(\SAND\Classe\Application::is_under_update()) {
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
        $pageFile = CONTROLLERS_PATH . DIRECTORY_SEPARATOR . $page['name'] . '.php';
        //verification de l'existence de la page dans les controlleurs
        if ($page['control']) {
            $pageFile = TRAITEMENT_PATH . DIRECTORY_SEPARATOR . $page['name'] . '.php';
        } else {
            //recherche du fichier controlleur correpondant HTTP1.1 ou HTTP1.0
            switch ($method) {
                //cas des requètes HTTP1.1
                case 'GET':
                case 'POST':
                    if ($appRequest) {
                        $page['name'] = ucfirst($page['name']);
                        $pageFile = CONTROLLER_PATH . DIRECTORY_SEPARATOR . $page['name'] . 'RestReponse.php';
                        if (!file_exists($pageFile)) {
                            $pageFile = CONTROLLER_PATH . DIRECTORY_SEPARATOR . $page['name'] . 'HttpReponse.php';
                        }
                    } else {
                        $pageFile = CONTROLLERS_PATH . DIRECTORY_SEPARATOR . $page['name'] . '.php';
                        if (!file_exists($pageFile)) {
                            $page['name'] = ucfirst($page['name']);
                            $pageFile = CONTROLLER_PATH . DIRECTORY_SEPARATOR . $page['name'] . 'RestReponse.php';
                            if (!file_exists($pageFile)) {
                                $pageFile = CONTROLLER_PATH . DIRECTORY_SEPARATOR . $page['name'] . 'HttpReponse.php';
                            }
                        }
                    }
                    break;
                case 'PUT':
                case 'DELETE':
                    $page['name'] = ucfirst($page['name']);
                    $pageFile = CONTROLLER_PATH . DIRECTORY_SEPARATOR . $page['name'] . 'RestReponse.php';
            }
        }

        //Test sur l'existence de la page actuelle et renvoi sur le controlleur 404 d'erreur si besoin
        if (!file_exists($pageFile)) {
            if ($appRequest) {
                $page['name'] = 'Error';
                $pageFile = CONTROLLER_PATH . DIRECTORY_SEPARATOR . $page['name'] . 'RestReponse.php';
                if (!file_exists($pageFile)) {
                    $pageFile = CONTROLLER_PATH . DIRECTORY_SEPARATOR . $page['name'] . 'HttpReponse.php';
                }
            } else {
                $page['name'] = 'error';
                $pageFile = CONTROLLERS_PATH . DIRECTORY_SEPARATOR . $page['name'] . '.php';
                if (!file_exists($pageFile)) {
                    $page['name'] = ucfirst($page['name']);
                    $pageFile = CONTROLLER_PATH . DIRECTORY_SEPARATOR . $page['name'] . 'RestReponse.php';
                    if (!file_exists($pageFile)) {
                        $pageFile = CONTROLLER_PATH . DIRECTORY_SEPARATOR . $page['name'] . 'HttpReponse.php';
                        if (!file_exists($pageFile)) {
                            //FrameWork : expose undefined controlleur error
                            // TODO: Send SANDerror
                            die("le controlleur 404 d'erreur n'existe pas : \n"
                                . CONTROLLERS_PATH . DIRECTORY_SEPARATOR . $page['name'] . '.php' . "\n"
                                . "ou l'un des controlleurs 404 (REST ou HTTP) n'existe pas : \n"
                                . CONTROLLER_PATH . DIRECTORY_SEPARATOR . $page['name'] . 'RestReponse.php \n'
                                . " ou \n"
                                . CONTROLLER_PATH . DIRECTORY_SEPARATOR . $page['name'] . 'HttpReponse.php');
                        }
                    }
                }
            }
        }
        $this->page = $page;
        $this->pageFile = $pageFile;
    }

    public static function asset_rewrite($urlAsset)
    {
        return '/' . BASE_SERVER_DIRECTORY . $urlAsset;
    }

    public static function link_rewrite($isControlPatern, $page, $params = array())
    {
        if ($isControlPatern) {
            return self::controlLink_rewrite($page, $params);
        } else {
            return self::link_rewrite_slashParam($page, $params);
        }
    }

    private static function link_rewrite_slashParam($page, $params = array())
    {
        $stringParams = '';
        foreach ($params as $key => $values) {
            $stringParams .= "/" . $key . "/" . $values;
        }
        return '/' . BASE_SERVER_DIRECTORY . $page . $stringParams;
    }

    private static function controlLink_rewrite($page, $params = array())
    {
        $stringParams = '';
        foreach ($params as $key => $values) {
            $stringParams .= "/" . $key . "/" . $values;
        }
        return '/' . BASE_SERVER_DIRECTORY . 'control' . '/' . $page . $stringParams;
    }

    public static function absolute_link_rewrite($isControlPatern, $page, $params = array())
    {
        if(isset($_SERVER['HTTP_HOST'])) {
            $url = $_SERVER['HTTP_HOST'];
            if (isset($_SERVER['REQUEST_SCHEME'])) {
                $scheme = $_SERVER['REQUEST_SCHEME'];
            } else {
                $scheme = 'http';
            }
            $scheme_tab = explode("://",PATH_URL);
            if( $scheme !== $scheme_tab[0]){
                $scheme = $scheme_tab[0];
            }
            $url = $scheme . "://" . $url;
        }else{
            $url = PATH_URL;
        }
        if( substr($url , -1) == "/"){
            $url = substr($url, 0, -1);
        }
        if ($isControlPatern) {
            $uri = self::controlLink_rewrite($page, $params);
        } else {
            $uri = self::link_rewrite_slashParam($page, $params);
        }


        return (  $url . $uri);
    }

    public static function getBaseDirectory(){
        return '/' . BASE_SERVER_DIRECTORY;
    }

    public static function getRootDirectoryUrl(){
        if(isset($_SERVER['HTTP_HOST'])) {
            $url = $_SERVER['HTTP_HOST'];
            if (isset($_SERVER['REQUEST_SCHEME'])) {
                $scheme = $_SERVER['REQUEST_SCHEME'];
            } else {
                $scheme = 'http';
            }
            $scheme_tab = explode("://",PATH_URL);
            if( $scheme !== $scheme_tab[0]){
                $scheme = $scheme_tab[0];
            }
            $url = $scheme . "://" . $url;
        }else{
            $url = PATH_URL;
        }
        if( substr($url , -1) == "/"){
            $url = substr($url, 0, -1);
        }
        if( substr($url . "/" . BASE_SERVER_DIRECTORY, -1) == "/"){
            return substr($url . "/" . BASE_SERVER_DIRECTORY, 0, -1);
        }else {
            return $url . "/" . BASE_SERVER_DIRECTORY;
        }
    }

    public static function getPageName(){

        $url = parse_url($_SERVER['REQUEST_URI']);
        $urlTrim = trim($url['path'], '/');
        $urlParts = explode('/', $urlTrim);

        //suppression des sous repertoires du BASE_SERVER_DIRECTORY
        $basePath = explode( '/', BASE_SERVER_DIRECTORY);
        foreach($basePath as $subDir) {
            if ($urlParts[0] == $subDir) {
                array_shift($urlParts);
            }
        }

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
