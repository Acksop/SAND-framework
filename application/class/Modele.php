<?php

/**
 * Package SAND\Classe
 * @author Emmanuel ROY
 * @license  MIT-licence (open source)
 * @version 3.5
 */

namespace SAND\Classe;

class Modele
{
    public $page;
    
    public function __construct($base_param)
    {
        if (file_exists(MODELS_PATH.DIRECTORY_SEPARATOR.$base_param['name'].'.model')) {
            $fichier = file(MODELS_PATH.DIRECTORY_SEPARATOR.$base_param['name'].'.model');
            foreach ($fichier as $ligne_num => $ligne) {
                //on recherche le pattern des parametres
                if (preg_match("#[ ]*([a-zA-Z_+]*)[ ]*[:][ ]*([a-zA-Z0-9ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ_+\-'\{\,\ \}\(\)]*[ ]*)#", $ligne, $matches)) {
                    //on recherche le pattern des tableau dans la valeur du paramètre
                    // dans le cas ou la déclaration se fait sur une seule ligne
                    if (preg_match("#{.*}#", $matches[2])) {
                        if (preg_match_all("#(?<capture>[0-9a-zA-ZÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ_+\- ']*)#", $matches[2], $arrayMatches)) {
                            $array = array();
                            foreach ($arrayMatches['capture'] as $val) {
                                if ($val != '') {
                                    $array[] = $val;
                                }
                            }
                            $this->page[$matches[1]] = $array;
                            continue;
                        }
                    }
                    $this->page[$matches[1]] = $matches[2];
                }
            }

            $this->page['all_params'] = $base_param['params'];
            //export nom a nom les variable dans la superglobale $_GET
            foreach($base_param['params'] as $key => $value){
                $_GET[$key] = $value;
            }
        } else {
            $this->page['name'] = $base_param['name'];
            $this->page['description'] = $base_param['description'];
            $this->page['all_params'] = $base_param['params'];
            //export nom a nom les variable dans la superglobale $_GET
            foreach($base_param['params'] as $key => $value){
                $_GET[$key] = $value;
            }
        }
        return $this;
    }
}
