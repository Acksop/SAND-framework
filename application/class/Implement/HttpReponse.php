<?php


namespace MVC\Classe\Implement;

use MVC\Classe\Implement\Contract\HttpReponseInterface;

class HttpReponse extends Action implements HttpReponseInterface
{
    public $url;
    public $params;
    public $data;

    /**
     * L'instanciation dynamique (new $classe($url, $data)) passe par le constructeur :
     * l'ancien échec venait du nom mal orthographié (__contruct).
     * @param \MVC\Classe\Url|null $url
     * @param mixed $requestData
     */
    public function __construct($url = null, $requestData = null)
    {
        if ($url !== null) {
            $this->instanciate($url, $requestData);
        }
    }

    // conservée pour les appels existants (new sans argument puis instanciate())
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
