<?php


namespace MVC\Classe\Implement;

use MVC\Classe\Implement\Contrat\RestReponseInterface;

class HttpReponse implements RestReponseInterface
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

    public function get()
    {

    }

    public function post()
    {

    }

    public function put()
    {

    }

    public function delete()
    {

    }
}