<?php

namespace MVC\Classe;

define( "LAYOUT_PATH" , APPLICATION_PATH . DIRECTORY_SEPARATOR . "layout");


class Vue{
	
	public $ecran;
	public $block_body;
	
	public function __construct($baseControlleur){

        $templateData = array();
		extract( $baseControlleur->modele->page );

		print_r($name);

		ob_start();
        require CONTROLLER_PATH.DIRECTORY_SEPARATOR.$name.'.php';

        $paths = new \SplPriorityQueue;

        $paths->insert(VIEW_PATH.DIRECTORY_SEPARATOR."system", 100);
        $paths->insert(VIEW_PATH.DIRECTORY_SEPARATOR."templating", 200);
        //$paths->insert('path/to/theme', 300);

        $renderer = new \Windwalker\Renderer\BladeRenderer($paths, array('cache_path' => VIEW_PATH.DIRECTORY_SEPARATOR."cache"));

        $renderer->render( $name , $templateData);

        /*require VIEW_PATH.DIRECTORY_SEPARATOR.$name.'.phtml';
		$this->block_body = ob_get_clean();

		ob_start();
		require LAYOUT_PATH.DIRECTORY_SEPARATOR."standard.phtml";*/
		$this->ecran = ob_get_clean();

	}
	
}
