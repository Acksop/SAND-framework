<?php

/**
 * Package SAND\Classe
 * @author Emmanuel ROY
 * @license  MIT-licence (open source)
 * @version 3.5
 */

namespace SAND\Classe;

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

        switch(APP_STATE) {
            case "CLOSED":
            case "MAINTAINED":
                $this->route = null;
                break;
            case "OPEN":
                if(\SAND\Classe\Application::is_under_update()) {
                    $this->route = null;
                    break;
                }
            default:
                $dispacher = new Dispacher();
                $this->route = $dispacher->route;
        }

    }

    public function launch()
    {
        $this->controlleur = new Controlleur($this);
        //si la page n'est un controlleur d'action alors on affiche l'écran
        if (!$this->url->page['control']) {
            print($this->controlleur->vue->ecran);
            //si on affiche l'écran alors on vide les alertes de la session
            \SAND\Object\Alert::remove();
        }
    }

    public static function is_under_update(){
        $ajh = new \DateTime('NOW');
        $maintenance_begin = new \DateTime(MAINTENANCE_DATE_DEBUT);
        $maintenance_fin = new \DateTime(MAINTENANCE_DATE_FIN);
        if($maintenance_begin < $ajh && $ajh < $maintenance_fin) {
            return true;
        }else{
            return false;
        }
    }
}
