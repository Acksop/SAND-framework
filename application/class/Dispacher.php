<?php


namespace SAND\Classe;

use Symfony\Component\Config\FileLocator as FileLocator;
use Symfony\Component\Routing\Matcher\UrlMatcher as UrlMatcher;
use Symfony\Component\Routing\RequestContext as RequestContext;
use Symfony\Component\Routing\Loader\YamlFileLoader as YamlFileLoader;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class Dispacher
{
    public $route;

    public function __construct()
    {

        //chemin seul : la chaîne de requête n'entre pas dans le routage
        $chemin = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        //Avoid callback from empty homepage
        if (!is_string($chemin) || $chemin == '/' || $chemin == '') {
            $this->route = null;
        } else {
            //Test the route from config file
            try {
                //load config file
                $fileLocator = new FileLocator(array(CONFIG_PATH . DIRECTORY_SEPARATOR . 'files'));
                $loader = new YamlFileLoader($fileLocator);
                $routes = $loader->load('routing.yml');

                //create context
                $context = new RequestContext('/');
                $matcher = new UrlMatcher($routes, $context);

                // Find the current route
                $parameters = $matcher->match($chemin);

                $this->route = $parameters;
            } catch (ResourceNotFoundException $e) {
                $this->route = null;
            }
        }
    }
}
