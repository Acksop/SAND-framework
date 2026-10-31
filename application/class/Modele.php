<?php

/**
 * Package MVC\Classe
 * @author Emmanuel ROY
 * @license  MIT-licence (open source)
 * @version 3.5
 */

namespace MVC\Classe;

class Modele
{
    public $page;
    
    /**
     * @param array       $base_param nom de la page (`name`) et paramètres d'URL (`params`)
     * @param string|null $dossier    dossier des .model (défaut : MODELS_PATH)
     */
    public function __construct($base_param, ?string $dossier = null)
    {
        $modele = ($dossier ?? MODELS_PATH) . DIRECTORY_SEPARATOR . $base_param['name'] . '.model';
        if (file_exists($modele)) {
            $fichier = file($modele);
            foreach ($fichier as $ligne_num => $ligne) {
                //on recherche le pattern des parametres
                if (preg_match("#[ ]*([a-zA-Z_+]*)[ ]*[:][ ]*([a-zA-Z0-9ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ_+\-'\{\,\ \}\(\)]*[ ]*)#", $ligne, $matches)) {
                    //on recherche le pattern des tableau dans la valeur du paramètre
                    // dans le cas ou la déclaration se fait sur une seule ligne
                    if (preg_match("#{.*}#", $matches[2])) {
                        if (preg_match_all("#(?<capture>[0-9a-zA-ZÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ_+\- ']*)#", $matches[2], $arrayMatches)) {
                            $array = array();
                            foreach ($arrayMatches['capture'] as $val) {
                                if (trim($val) !== '') {
                                    $array[] = trim($val);
                                }
                            }
                            $this->page[trim($matches[1])] = $array;
                            continue;
                        }
                    }
                    $this->page[trim($matches[1])] = trim($matches[2]);
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
