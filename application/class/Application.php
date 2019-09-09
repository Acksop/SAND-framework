<?php

namespace MVC\Classe;

define( "CONTROLLERS_PATH" , APPLICATION_PATH . DIRECTORY_SEPARATOR . "include" . DIRECTORY_SEPARATOR . "controlleurs");

require APPLICATION_PATH . DIRECTORY_SEPARATOR . "parameters.php";

class Application
{
	public $url;
	
	public function __construct(){
		$this->url = new Url();
	}

	public function launch(){

		$controlleur = new Controlleur($this);
		//si la page n'est un controlleur d'action alors on affiche l'écran
		if(!$this->url->page['control']) {
            print($controlleur->vue->ecran);
        }
	}
	
}
