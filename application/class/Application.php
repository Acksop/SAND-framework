<?php

namespace MVC\Classe;

class Application
{
    public $http;
    public $url;
    public $browser;
    public $route;


    public function __construct()
    {
        $this->http = new HttpMethod();
        $this->browser = new Browser();

        $this->url = new Url($this->http->method, $this->browser->isAppRequest());

        $dispacher = new Dispacher();
        $this->route = $dispacher->route;
    }

    public function launch()
    {
        //on declare la session lors du chargement du controlleur,
        // ainsi on instancie la page précédente et le javascript et le css asynchrone
        \MVC\Object\Session::createAndTestSession();

        $controlleur = new Controlleur($this);
        //si la page n'est un controlleur d'action alors on affiche l'écran
        if (!$this->url->page['control']) {
            print($controlleur->vue->ecran);
            //si on affiche l'écran alors on vide les alertes de la session
            \MVC\Object\Alert::remove();
        }
    }
}
