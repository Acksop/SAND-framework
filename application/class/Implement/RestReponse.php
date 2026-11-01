<?php


namespace SAND\Classe\Implement;

use SAND\Classe\Dumper;
use SAND\Classe\Url;
use SAND\Classe\HttpMethod;
use SAND\Classe\Implement\Contract\RestReponseInterface;

class RestReponse implements RestReponseInterface
{
    public $url;
    public $params;
    public $data;

    /**
     * L'instanciation dynamique (new $classe($url, $data)) passe par le constructeur :
     * l'ancien échec venait du nom mal orthographié (__contruct).
     * @param \SAND\Classe\Url|null $url
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
