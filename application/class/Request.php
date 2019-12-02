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
                //$this->data['GET'] = ...
                //POST DATA except enctype="multipart/form-data"
                $this->data['POST'] = json_decode(file_get_contents("php://input"), true);
            case 'DELETE':
                //$this->data['GET'] = ...
                //POST DATA except enctype="multipart/form-data"
                $this->data['POST'] = json_decode(file_get_contents("php://input"), true);
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