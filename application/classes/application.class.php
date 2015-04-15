<?php

define( "CONTROLLERS_PATH" , APPLICATION_PATH . DIRECTORY_SEPARATOR . "controlleurs");

require CLASSES_PATH.DIRECTORY_SEPARATOR."url.class.php";

class Application
{
	public $url;
	
	public function __construct(){
		$this->$url = new Url();
	}
	
	public function launch(){
		$controlleur = new Controlleur($this);
		print( $controlleur->$vue->$ecran );
	}
	
}
