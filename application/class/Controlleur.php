<?php

namespace MVC\Classe;

class Controlleur{
	
	public $modele;
	public $vue;
	
	public function __construct($application){


        $requete = new MVC\Classe\Request();

        switch ($requete->method) {
            //cas des requètes PUT et DELETE
            case 'PUT':
            case 'DELETE':
                require CONTROLLER_PATH . DIRECTORY_SEPARATOR . $application->url->page['name'] . 'HttpReponse.php';
                $reponseHttp = lcfirst($application->url->page['name']) . 'HttpReponse';
                $response = new $reponseHttp($application->url, $requete->getData());
                if ($requete->method == 'DELETE') {
                    $reponseHttp->delete();
                } else {
                    $reponseHttp->put();
                }
                break;
            //cas des requètes POST et GET
            case 'POST':
            case 'GET':
                if (!file_exists(CONTROLLER_PATH . DIRECTORY_SEPARATOR . $application->url->page['name'] . '')) {
                    require CONTROLLER_PATH . DIRECTORY_SEPARATOR . $application->url->page['name'] . 'HttpReponse.php';
                    $reponseHttp = lcfirst($application->url->page['name']) . 'HttpReponse';
                    $response = new $reponseHttp($application->url, $requete->getData());
                    if ($requete->method == 'POST') {
                        $reponseHttp->post();
                    } else {
                        $reponseHttp->get();
                    }
                    break;
                }


            default:

                if ($application->url->page['control']) {
                    $url_params = $application->url->page['params'];
                    require TRAITEMENT_PATH . DIRECTORY_SEPARATOR . $application->url->page['name'] . '.php';
                } else {
                    $this->modele = new Modele($application->url->page);
                    $this->vue = new Vue($this);
                }
        }


	}
	
}
