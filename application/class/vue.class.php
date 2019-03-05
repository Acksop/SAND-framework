<?php

define( "LAYOUT_PATH" , APPLICATION_PATH . DIRECTORY_SEPARATOR . "layout");


class Vue{
	
	public $ecran;
	public $block_body;
	
	public function __construct($baseControlleur){
		
		extract( $baseControlleur->modele->page );

		print_r($baseControlleur->modele->page);

		ob_start();
        require VIEW_PATH.DIRECTORY_SEPARATOR.$name.'.phtml';
		$this->block_body = ob_get_clean();

		ob_start();
		require LAYOUT_PATH.DIRECTORY_SEPARATOR."standard.phtml";
		$this->ecran = ob_get_clean();

	}
	
}
