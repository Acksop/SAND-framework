<?php

namespace MVC\Classe;

require APPLICATION_PATH . DIRECTORY_SEPARATOR . "parameters.php";

class Application
{
    public $http;
    public $url;
    public $browser;
    public $route;


    public function __construct(){
        $this->http = new HttpMethod();
        $this->browser = new Browser();

        $this->url = new Url($this->http->method, $this->browser->isAppRequest());

        $dispacher = new Dispacher();
        $this->route = $dispacher->route;
    }

    public function launch(){
        //print_r($this->route);
        $controlleur = new Controlleur($this);
        //si la page n'est un controlleur d'action alors on affiche l'écran
        if(!$this->url->page['control']) {
            print($controlleur->vue->ecran);
        }
    }

}
