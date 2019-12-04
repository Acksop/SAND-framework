<?php

namespace MVC\Classe;

use Symfony\Component\Config\FileLocator as FileLocator;
use Symfony\Component\Routing\Matcher\UrlMatcher as UrlMatcher;
use Symfony\Component\Routing\RequestContext as RequestContext;
use Symfony\Component\Routing\Loader\YamlFileLoader as YamlFileLoader;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

require APPLICATION_PATH . DIRECTORY_SEPARATOR . "parameters.php";

class Application
{
    public $http;
    public $url;
    public $browser;


    public function __construct(){
        $this->http = new HttpMethod();
        $this->browser = new Browser();
        $this->url = new Url($this->http->method, $this->browser->isAppRequest());
    }

    public function launch(){
        try {
            //load config file
            $fileLocator = new FileLocator(array(CONFIG_PATH . DIRECTORY_SEPARATOR . 'files'));
            $loader = new YamlFileLoader($fileLocator);
            $routes = $loader->load('routing.yml');

            //create context
            $context = new RequestContext('/');
            $matcher = new UrlMatcher($routes, $context);

            // Find the current route
            $parameters = $matcher->match($_SERVER['REQUEST_URI']);

            echo '<pre>';
            print_r($parameters);
            die();
        } catch (ResourceNotFoundException $e) {
            echo $e->getMessage();
        }

        $controlleur = new Controlleur($this);
        //si la page n'est un controlleur d'action alors on affiche l'écran
        if(!$this->url->page['control']) {
            print($controlleur->vue->ecran);
        }
    }

}
