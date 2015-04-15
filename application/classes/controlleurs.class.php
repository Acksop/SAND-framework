<?php

define( "MODELS_PATH" , APPLICATION_PATH . DIRECTORY_SEPARATOR . "modeles");
define( "VIEW_PATH" , APPLICATION_PATH . DIRECTORY_SEPARATOR . "vues");

require CLASSES_PATH.DIRECTORY_SEPARATOR."modeles.class.php";
require CLASSES_PATH.DIRECTORY_SEPARATOR."vues.class.php";

class Controlleurs{
	
	public $modele;
	public $vue;
	
	public fonction __construct($application){
		
		$this->$modele = new Modeles( $application->$url->$page );
		$this->$vue = new Vues( $this );
		
		
	}
	
}