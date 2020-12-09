<?php

namespace MVC\Classe;

//require_once dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR."define-constantes.php";

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

        //print_r($urlParts);
        if (isset($urlParts[0])) {
            //Récupération du nom de la page
            ($urlParts[0] == 'index' || $urlParts[0] == '') ? $page['name'] = 'index' : $page['name'] = $urlParts[0];
            unset($urlParts[0]);
        } else {
            $page['name'] = 'index';
        }

        //il se peut que l'on ait des variable avec ? dans l'url
        $urlQuery = explode('?', $page['name']);
        $page['name'] = $urlQuery[0];

        $page['name'] = strtolower($page['name']);

        if ($page['name'] == 'control') {
            $page['control'] = true;
            ($urlParts[1] == 'index' || $urlParts[1] == '') ? $page['name']='index' : $page['name']=$urlParts[1];
            unset($urlParts[1]);
        }

        //vérification du nombre de parametres:
        $numParts = count($urlParts);
        //s'il n'existe pas autant de clé que de valeurs, ce peut ^etre un module symfony
        if ($numParts%2 != 0) {
            //si un module symfony n'est pas reférencé avec le nom de la page, on renvoi un erreur
            if (!in_array($page['name'], $this->registre->getIndex())) {
                $page['name'] = 'error';
                $page['params'] = array();
                $this->page = $page;
                return;
            } else {
                foreach ($urlParts as $key => $value) {
                    $values[] = $value;
                    $keys[] = $key;
                }
                $page['params'] = $values;
            }

            //cas d'utilisation normal : il existe autant de clé que de valeurs
        } elseif ($numParts != 0) {
            // si c'est un module alors on charge un a un les parametres
            if (in_array($page['name'], $this->registre->getIndex())) {
                foreach ($urlParts as $key => $value) {
                    $values[] = $value;
                    $keys[] = $key;
                }
                $page['params'] = $values;
            // sinon c'est une page normal du framework
            } else {
                $values = array();
                $keys = array();
                foreach ($urlParts as $key => $value) {
                    if ($key % 2 == 0) {
                        $values[] = $value;
                    } else {
                        $keys[] = $value;
                    }
                }
                if ($page['control']) {
                    $page['params'] = array_combine($values, $keys);
                } else {
                    $page['params'] = array_combine($keys, $values);
                }
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
                case 'PUT':
                case 'DELETE':
                case 'GET':
                case 'POST':
                    if ($appRequest) {
                        $page['name'] = ucfirst($page['name']);
                        $pageFile = CONTROLLER_PATH . DIRECTORY_SEPARATOR . $page['name'] . 'HttpReponse.php';
                    } else {
                        $page['name'] = lcfirst($page['name']);
                        $pageFile = CONTROLLERS_PATH . DIRECTORY_SEPARATOR . $page['name'] . '.php';
                        if (!file_exists($pageFile)) {
                            $page['name'] = ucfirst($page['name']);
                            $pageFile = CONTROLLER_PATH . DIRECTORY_SEPARATOR . $page['name'] . 'HttpReponse.php';
                        }
                    }
            }
        }

        if (!file_exists($pageFile)) {
            if ($appRequest) {
                $page['name'] = 'Error';
            } else {
                $page['name'] = 'error';
            }
        }
        $this->page = $page;
        $this->pageFile = $pageFile;
    }

    public static function link_rewrite($isControlPatern, $page, $params = array())
    {
        if ($isControlPatern) {
            return self::controlLink_rewrite($page, $params);
        } else {
            return self::link_rewrite_slashParam($page, $params);
        }
    }

    public static function module_link_rewrite($page, $params = array())
    {
        $stringParams = '';
        foreach ($params as $values) {
            $stringParams .= "/" . $values;
        }
        return '/' . $page . $stringParams;
    }

    private static function link_rewrite_slashParam($page, $params = array())
    {
        $stringParams = '';
        foreach ($params as $key => $values) {
            $stringParams .= "/" . $key . "/" . $values;
        }
        return '/' . $page . $stringParams;
    }

    private static function controlLink_rewrite($page, $params = array())
    {
        $stringParams = '';
        foreach ($params as $key => $values) {
            $stringParams .= "/" . $key . "/" . $values;
        }
        return '/' . 'control' . '/' . $page . $stringParams;
    }

    public static function absolute_link_rewrite($isControlPatern, $page, $params = array())
    {
        $url = $_SERVER['HTTP_HOST'];
        if ($isControlPatern) {
            $uri = self::controlLink_rewrite($page, $params);
        } else {
            $uri = self::link_rewrite_slashParam($page, $params);
        }
        if (isset($_SERVER['REQUEST_SCHEME'])) {
            $scheme = $_SERVER['REQUEST_SCHEME'];
        } else {
            $scheme = 'http';
        }

        return ($scheme . "://" . $url . $uri);
    }
}
