<?php

use MVC\Classe\Implement\RestReponse;
use MVC\Classe\Logger;

class ErrorHttpReponse extends RestReponse
{
    public function put()
    {
        Logger::addLog('put', 'Error PUT');
    }

    public function delete()
    {
        Logger::addLog('delete', 'Error DELETE');
    }

    public function get()
    {
        Logger::addLog('get', 'Error GET');
    }

    public function post()
    {
        Logger::addLog('post', 'Error POST');
    }
}