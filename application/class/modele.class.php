<?php

class Modele{
	
	public $page;
	
	public function __construct($base_param){
		
		if(file_exists(MODELS_PATH.DIRECTORY_SEPARATOR.$base_param['name'].'.model')){
			$fichier = file(MODELS_PATH.DIRECTORY_SEPARATOR.$base_param['name'].'.model');
			foreach ($fichier as $ligne_num => $ligne) {
			if(preg_match("#[ ]*([a-zA-Z_]*)[ ]*[:][ ]*([a-zA-Z ]*[ ]*)#",$ligne,$matches)){
				$this->page[$matches[1]] = $matches[2];
			}
		}else{
			$this->page['name'] = $base_param['name'];
			$this->page['description'] = $base_param['description'];
			$this->page['params'] = $base_param['params'];
		}
	}
	
}
