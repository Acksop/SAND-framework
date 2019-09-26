<?php

namespace MVC\Classe;

class Url
{
	public $page;
	public $registre;
	
	
	public function __construct(){

	    //on créé le registre des modules symfony
	    $this->registre = new \MVC\Classe\ModularRegister();

	    //définition des parametres de base
        $page = array();
        $page['name'] = 'accueil';
        $page['description'] = "";
        $page['params'] = array();
        $page['control'] = false;



        $url = parse_url($_SERVER['REQUEST_URI']);
        $urlTrim = trim( $url['path'] , '/' );
        $urlParts = explode('/' , $urlTrim );

        //print_r($urlParts);
        if(isset($urlParts[0])) {
            //Récupération du nom de la page
            ($urlParts[0] == 'index' || $urlParts[0] == '') ? $page['name'] = 'accueil' : $page['name'] = $urlParts[0];
            //array_shift($urlParts);
            unset($urlParts[0]);
        }else{
            $page['name'] = 'accueil';
        }

        if($page['name'] == 'control'){
            $page['control'] = true;
            ($urlParts[1] == 'index' || $urlParts[1] == '' ) ? $page['name']='accueil' : $page['name']=$urlParts[1];
            //array_shift($urlParts);
            unset($urlParts[1]);

        }

        //vérification du nombre de parametres:
        $numParts = count($urlParts);
        //s'il n'existe pas autant de clé que de valeurs, ce peut ^etre un module symfony
        if ( $numParts%2 != 0 ) {
            //si un module symfony n'est pas reférencé avec le nom de la page, on renvoi un erreur
            if( !in_array($page['name'], $this->registre->getIndex()) ){
                $page['name'] = 'error';
                $page['params'] = array();
                $this->page = $page;
                return;
            }
        //cas d'utilisation normal : il existe autant de clé que de valeurs
        } else if ( $numParts != 0 ) {
            $values = array();
            $keys = array();
            foreach( $urlParts as $key => $value ){
                if($key%2 == 0) {
                    $values[] = $value;
                } else {
                    $keys[] = $value;
                }
            }
            if($page['control']){
                $page['params'] = array_combine($values, $keys);
            }else {
                $page['params'] = array_combine($keys, $values);
            }
        }
        //verification de l'existence de la page dans les controlleurs
        if($page['control']){
            $pageFile = TRAITEMENT_PATH . DIRECTORY_SEPARATOR . $page['name'] . '.php';
        }else {
            $pageFile = CONTROLLERS_PATH . DIRECTORY_SEPARATOR . $page['name'] . '.php';
        }

        if(!file_exists($pageFile)){
            $page['name'] = 'error';
        }
        $this->page = $page;

    }

    static public function link_rewrite($isControlPatern, $page, $params = array())
    {
        if ($isControlPatern) {
            return self::controlLink_rewrite($page, $params);
        } else {
            return self::link_rewrite_slashParam($page, $params);
        }
    }

    static private function link_rewrite_slashParam($page, $params = array())
    {
        $stringParams = '';
        foreach ($params as $key => $values) {
            $stringParams .= "/" . $key . "/" . $values;
        }
        return '/' . $page . $stringParams;

    }

    static private function controlLink_rewrite($page, $params = array())
    {
        $stringParams = '';
        foreach ($params as $key => $values) {
            $stringParams .= "/" . $key . "/" . $values;
        }
        return '/' . 'control' . '/' . $page . $stringParams;
    }

}
