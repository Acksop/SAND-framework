<?php

namespace MVC\Classe;

class Controlleur{
	
	public $modele;
	public $vue;
	
	public function __construct($application){

        switch ($application->http->method) {
            //cas des requètes PUT et DELETE
            case 'PUT':
            case 'DELETE':
            case 'POST':
            case 'GET':
                if ($application->browser->isAppRequest()) {
                    require CONTROLLER_PATH . DIRECTORY_SEPARATOR . $application->url->page['name'] . 'HttpReponse.php';
                    $this->callHttpResponse($application);
                    break;
                }

            default:
                if ($application->route != NULL) {
                    $conduit = explode('::', $application->route['controller']);
                    require CONDUIT_PATH . DIRECTORY_SEPARATOR . $conduit[0] . '.php';
                    $conduitRoute = "\\" . $conduit[0];
                    $method = strtolower($conduit[1]);
                    $class = new $conduitRoute();
                    $class = $class->initialize($application->route);
                    $this->vue = new VueVide();
                    $this->vue->ecran = $class->$method();

                } else if ($application->url->page['control']) {
                    $url_params = $application->url->page['params'];
                    require TRAITEMENT_PATH . DIRECTORY_SEPARATOR . $application->url->page['name'] . '.php';
                } else {
                    $this->modele = new Modele($application->url->page);
                    $this->vue = new Vue($this);
                }
        }


	}

    public function callHttpResponse($application)
    {
        $reponseHttp = "\\" . $application->url->page['name'] . 'HttpReponse';

        //FIXME:
        //Le passage par le contructeur dans le cas d'une instanciation dynamique ne fonctionne pas
        //$reponse = new $reponseHttp($application->url, $application->http->getData());
        //il faut passer par une fonction personnelle permettant l'instanciation des variables

        $reponse = new $reponseHttp();
        $reponse->instanciate($application->url, $application->http->getData());
        $method = strtolower($application->http->method);

        $this->vue = new VueVide();
        $this->vue->ecran = $reponse->$method();
        return;
    }
	
}
