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

    /** Requête destinée à l'API (/wp-json/… ou /api/…) : traitée par MVC\Api\Component\Router. */
    public $api = false;


    public function __construct()
    {
        // L'API passe avant tout le reste (maintenance, routage MVC, Browser...).
        if (self::estRequeteApi((string) ($_SERVER['REQUEST_URI'] ?? '/'))) {
            $this->api = true;
            return;
        }

        $this->http = new HttpMethod();
        $this->browser = new Browser();

        $this->url = new Url($this->http->method, $this->browser->isAppRequest());

        switch(APP_STATE) {
            case "CLOSED":
            case "MAINTAINED":
                $this->route = null;
                break;
            case "OPEN":
                if(\MVC\Classe\Application::is_under_update()) {
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
        if ($this->api) {
            \MVC\Api\Component\Router::dispatch();
            return;
        }
        $this->controlleur = new Controlleur($this);
        //si la page n'est un controlleur d'action alors on affiche l'écran
        if (!$this->url->page['control']) {
            print($this->controlleur->vue->ecran);
            //si on affiche l'écran alors on vide les alertes de la session
            \MVC\Object\Alert::remove();
        }
    }

    /**
     * Vrai si le chemin commence par /wp-json ou /api (après BASE_SERVER_DIRECTORY).
     */
    public static function estRequeteApi(string $uri): bool
    {
        return \MVC\Api\Component\Requete::cheminApi($uri) !== null;
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
