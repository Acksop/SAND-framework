<?php

namespace MVC\Classe;

class ModularRegister{

    public $registry = array();
    public $index = array();

    public function __construct(){

        $fichier = file(MODULES_PATH . DIRECTORY_SEPARATOR . "setup" . DIRECTORY_SEPARATOR ."registre.model");
        foreach ($fichier as $ligne_num => $ligne) {
            if (preg_match("#([ ]*[a-zA-Z0-9-_+éèàùïîç]*)[ ]*[:][ ]*([0-9a-zA-Z-_+ 'éèàùïîç.]*[ ]*)#", $ligne, $matches)) {

                $this->registry[$matches[1]] = $matches[2];
                $this->index[] = $matches[1];
            }
        }
    }

    public function getRegistre(){
        return $this->registry;
    }

    public function getIndex(){
        return $this->index;
    }
}