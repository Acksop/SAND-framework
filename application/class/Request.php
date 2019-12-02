<?php


namespace MVC\Classe;


class Request
{

    public $method;
    public $data;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
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
                $this->data = json_decode(file_get_contents("php://input"), true);
            case 'DELETE':
                break;
            default:
                // Requête invalide
                header("HTTP/1.0 405 Method Not Allowed");
                break;
        }
    }

}