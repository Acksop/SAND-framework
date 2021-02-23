<?php

namespace MVC\Classe;

class Controlleur
{
    public $modele;
    public $vue;
    
    public function __construct($application)
    {
        switch ($application->http->method) {
            //cas des requètes PUT et DELETE
            case 'PUT':
            case 'DELETE':
            case 'POST':
            case 'GET':
                if ($application->browser->isAppRequest()) {
                    require $application->url->pageFile;
                    $this->callHttpResponse($application);
                    die();
                }
                // no break
            default:
                //si c'est une route symfony alors on appelle le conduit associé
                if ($application->route != null) {
                    $application->url->page['name'] = $application->route['_route'];
                    $conduit = explode('::', $application->route['controller']);
                    require CONDUIT_PATH . DIRECTORY_SEPARATOR . $conduit[0] . '.php';
                    $conduitRoute = "\\" . $conduit[0];
                    $method = strtolower($conduit[1]);
                    $class = new $conduitRoute();
                    $class->initialize($application);
                    $this->vue = new VueVide();
                    $this->vue->ecran = $class->$method();
                //si c'est une page de traitement PRG on appelle le fichier de controle de formulaire
                } elseif ($application->url->page['control']) {
                    $url_params = $application->url->page['params'];
                    require TRAITEMENT_PATH . DIRECTORY_SEPARATOR . $application->url->page['name'] . '.php';
                //sinon c'est une page MVC normale
                } else {
                    $this->modele = new Modele($application->url->page);
                    //on appelle le garde d'authentification
                    if(isset($this->modele->page['authentification']) && $this->modele->page['authentification'] == 'yes'){
                        //on declare la session lors du chargement du controlleur,
                        // ainsi on instancie la page précédente et le javascript et le css asynchrone
                        \MVC\Object\Session::createAndTestSession();
                    }
                    //fixme: doit on passer l'application entière dans la vue ou seulement $application->modele->page ?
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

        Logger::addLog('http11', " $reponseHttp app {$application->http->method} request! ( ".get_class($reponse)."->$method() )");

        $this->vue = new VueVide();
        $this->vue->ecran = $reponse->$method();
        return;
    }
}
