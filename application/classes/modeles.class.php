<?php

class Modeles{
	
	public $page;
	
	public fonction __construct($base_param){
		
		if(file_exists(MODELS_PATH.DIRECTORY_SEPARATOR.$page_param['name'].php)){
			include MODELS_PATH.DIRECTORY_SEPARATOR.$page_param['name'].php;
		}else{
			$this->$page['name'] = $base_param['name'];
			$this->$page['descriptin'] = $base_param['descriptin'];
			$this->$page['params'] = $base_param['params'];
		}
	}
	
}