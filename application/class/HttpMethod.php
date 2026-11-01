<?php


namespace SAND\Classe;

class HttpMethod
{
    public $method;
    protected $data;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        // Une ligne par requête : seulement en mode débogage
        if (\SAND\Object\Environment::debug()) {
            Logger::addLog('http.method', $this->method);
        }
        $this->acceptResponse();
    }

    private function acceptResponse()
    {
        switch ($this->method) {
            case 'GET':
                break;
            case 'POST':
                break;
            case 'PUT':
            case 'DELETE':
                //on décode les données depuis l'input afin de les traiter
                $this->data = json_decode(file_get_contents("php://input"), true);
                break;
            default:
                // Requête invalide
                header("HTTP/1.0 405 Method Not Allowed");
                break;
        }
    }

    public function getData()
    {
        return $this->data;
    }
}
