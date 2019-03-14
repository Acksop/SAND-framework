<?php

define( "MODELS_PATH" , APPLICATION_PATH . DIRECTORY_SEPARATOR . "include" . DIRECTORY_SEPARATOR . "modeles");
define( "VIEW_PATH" , APPLICATION_PATH . DIRECTORY_SEPARATOR . "include" . DIRECTORY_SEPARATOR . "vues");
define( "CONTROLLER_PATH" , APPLICATION_PATH . DIRECTORY_SEPARATOR . "include" . DIRECTORY_SEPARATOR . "controlleurs");
define( "TRAITEMENT_PATH" , APPLICATION_PATH . DIRECTORY_SEPARATOR . "traitements");

require CLASSES_PATH.DIRECTORY_SEPARATOR."modele.class.php";
require CLASSES_PATH.DIRECTORY_SEPARATOR."vue.class.php";

class Controlleur{
	
	public $modele;
	public $vue;
	
	public function __construct($application){

	    if($application->url->page['control']){
            $url_params = $application->url->page['params'];
	        require TRAITEMENT_PATH . DIRECTORY_SEPARATOR . $application->url->page['name'] . '.php';
        } else {
            $this->modele = new Modele($application->url->page);
            $this->vue = new Vue($this);
        }
	}
	
}
