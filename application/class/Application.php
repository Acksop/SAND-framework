<?php

/**
 * Package MVC\Classe
 * @author Emmanuel ROY
 * @license  MIT-licence (open source)
 * @version 3.5
 */

namespace MVC\Classe;

class Application
{
    public $http;
    public $url;
    public $browser;
    public $route;

    public $controlleur;


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
        $this->controlleur = new Controlleur($this);
        //si la page n'est un controlleur d'action alors on affiche l'écran
        if (!$this->url->page['control']) {
            print($this->controlleur->vue->ecran);
            //si on affiche l'écran alors on vide les alertes de la session
            \MVC\Object\Alert::remove();
        }
    }
}
