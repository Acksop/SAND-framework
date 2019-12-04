<?php


namespace MVC\Classe\Implement;

use MVC\Classe\Implement\Contrat\HttpReponseInterface;

class HttpReponse extends Action implements HttpReponseInterface
{

    public $url;
    public $params;
    public $data;

    /**
     * Le passage par le contructeur dans le cas d'une instanciation dynamique ne fonctionne pas
     * http://www.thedarksideofthewebblog.com/appel-dynamique-de-constructeur-en-php/
     * il faut passer par une fonction personnelle permettant l'instanciation des variables
     * @param $url
     * @param $requestData
     */
    public function __contruct($url, $requestData)
    {
        $this->url = $url;
        $this->params = $url['params'];
        $this->data = $requestData;
    }

    public function instanciate($url, $requestData)
    {
        $this->url = $url;
        $this->params = $url->page['params'];
        $this->data = $requestData;
    }

    public function put()
    {

    }

    public function delete()
    {

    }

}