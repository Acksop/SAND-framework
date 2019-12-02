<?php


namespace MVC\Classe\Implement;

use MVC\Classe\Implement\Contrat\HttpReponseInterface;

class HttpReponse implements HttpReponseInterface
{

    public $url;
    public $params;
    public $data;

    public function __contruct($url, $requestData)
    {
        $this->url = $url;
        $this->params = $url['params'];
        $this->data = $requestData;
    }

    public function put()
    {

    }

    public function delete()
    {

    }

}