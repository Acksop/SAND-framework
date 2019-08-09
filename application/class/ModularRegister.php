<?php

namespace MVC\Classe;

class ModularRegister{

    public $registry = array();
    public $index = array();

    public function __construct(){

        $fichier = file(MODULES_PATH.DIRECTORY_SEPARATOR."setup" . DIRECTORY_SEPARATOR ."registre.model");
        foreach ($fichier as $ligne_num => $ligne) {
            if (preg_match("#[ ]*([a-zA-Z-_+]*)[ ]*[:][ ]*([0-9a-zA-Z-_+ ']*[ ]*)#", $ligne, $matches)) {
                $this->registry[$matches[1]] = $matches[2];
                $this->index[] = $matches[1];
            }
        }
    }

    public function getRegistre(){
        return $this->index;
    }

    public function getIndex(){
        return $this->registry;
    }
}