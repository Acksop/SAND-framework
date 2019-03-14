<?php

define( "CONTROLLERS_PATH" , APPLICATION_PATH . DIRECTORY_SEPARATOR . "include" . DIRECTORY_SEPARATOR . "controlleurs");
define( "OBJETS_PATH" , APPLICATION_PATH . DIRECTORY_SEPARATOR . "objets");

require CLASSES_PATH.DIRECTORY_SEPARATOR."url.class.php";
require CLASSES_PATH.DIRECTORY_SEPARATOR."controlleur.class.php";
require CLASSES_PATH.DIRECTORY_SEPARATOR."bdd.class.php";
require CLASSES_PATH.DIRECTORY_SEPARATOR."dumper.class.php";

require APPLICATION_PATH . DIRECTORY_SEPARATOR . "parameters.php";

class Application
{
	public $url;
	
	public function __construct(){
		$this->url = new Url();
	}

	public function launch(){

		$controlleur = new Controlleur($this);
		if(!$this->url->page['control']) {
            print($controlleur->vue->ecran);
        }
	}
	
}
