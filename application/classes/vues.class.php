<?php

define( "TEMPLATE_PATH" , APPLICATION_PATH . DIRECTORY_SEPARATOR . "template");
define( "INCLUDE_PATH" , APPLICATION_PATH . DIRECTORY_SEPARATOR . "include");


class Vues{
	
	public ecran;
	public block_body;
	
	public fonction __construct($baseControlleur){
		
		extract( $baseControlleur->$modele->$page );
		
		ob_start();
		require VUE_PATH.DIRECTORY_SEPARATOR.$baseControlleur->$modele->$page['name'].php;
		$this->block_body = ob_end_clean();
		
		ob_start();
		require TEMPLATE_PATH.DIRECTORY_SEPARATOR."standart.phtml";
		$this->ecran = ob_end_clean();
		
	}
	
}