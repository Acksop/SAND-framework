<?php

namespace MVC\Classe;

define( "LAYOUT_PATH" , APPLICATION_PATH . DIRECTORY_SEPARATOR . "layout");


class Vue{
	
	public $ecran;
	public $block_body;
	
	public function __construct($baseControlleur){

        $templateData = array();
		extract( $baseControlleur->modele->page );

		ob_start();

		//l'inclusion du controlleur doit renvoyer le tableau $templateData
        require CONTROLLER_PATH.DIRECTORY_SEPARATOR.$name.'.php';

        $paths = new \SplPriorityQueue;

        $paths->insert(VIEW_PATH.DIRECTORY_SEPARATOR."system", 100);
        $paths->insert(VIEW_PATH.DIRECTORY_SEPARATOR."layout", 200);
        $paths->insert(VIEW_PATH.DIRECTORY_SEPARATOR."view", 300);

        $renderer = new \Windwalker\Renderer\BladeRenderer($paths, array('cache_path' => VIEW_PATH.DIRECTORY_SEPARATOR."cache"));

        //de base on ajoute les parametres du .model et ceux provenant de l'url
        foreach($baseControlleur->modele->page as $key => $value){
            $templateData[$key] = $value;
        }
        echo $renderer->render( $name , $templateData);

        $this->ecran = ob_get_clean();

	}
	
}
