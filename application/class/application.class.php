<?php

define( "CONTROLLERS_PATH" , APPLICATION_PATH . DIRECTORY_SEPARATOR . "include" . DIRECTORY_SEPARATOR . "controlleurs");

require CLASSES_PATH.DIRECTORY_SEPARATOR."url.class.php";
require CLASSES_PATH.DIRECTORY_SEPARATOR."controlleur.class.php";
require CLASSES_PATH.DIRECTORY_SEPARATOR."bdd.class.php";

require APPLICATION_PATH . DIRECTORY_SEPARATOR . "parameters.php";

class Application
{
	public $url;
	
	public function __construct(){
		$this->url = new Url();
	}

	public function launch(){

		$controlleur = new Controlleur($this);
		print( $controlleur->vue->ecran );
	}
	
}
