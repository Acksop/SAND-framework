<?php

class Modele{
	
	public $page;
	
	public function __construct($base_param){
		
		if(file_exists(MODELS_PATH.DIRECTORY_SEPARATOR.$base_param['name'].'.php')){
			include MODELS_PATH.DIRECTORY_SEPARATOR.$base_param['name'].'.php';
		}else{
			$this->page['name'] = $base_param['name'];
			$this->page['description'] = $base_param['description'];
			$this->page['params'] = $base_param['params'];
		}
	}
	
}
