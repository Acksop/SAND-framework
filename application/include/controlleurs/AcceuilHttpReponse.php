<?php

use MVC\Classe\Implement\HttpReponse;

class AcceuilHttpReponse extends HttpReponse
{
    public function put()
    {
        echo $this->params . "<br/>" . $this->data;
    }

    public function delete()
    {

    }
}