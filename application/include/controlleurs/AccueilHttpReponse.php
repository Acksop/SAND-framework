<?php

use MVC\Classe\Dumper;
use MVC\Classe\Implement\RestReponse;
use MVC\Classe\Logger;

class AccueilHttpReponse extends RestReponse
{

    public function put()
    {
        ob_start();
        Dumper::dump($this->params);
        Dumper::dump($this->data);
        $text = ob_get_clean();
        Logger::addLog('http11.put', '____Hello Put____' . PHP_EOL . $text);
    }
    public function delete()
    {
        ob_start();
        Dumper::dump($this->params);
        Dumper::dump($this->data);
        $text = ob_get_clean();
        Logger::addLog('http11.delete', '____Hello Delete:____' . PHP_EOL . $text);

    }

    public function get()
    {
        ob_start();
        Dumper::dump($this->params);
        Dumper::dump($this->data);
        $text = ob_get_clean();
        Logger::addLog('http11.get', '____Hello GET____' . PHP_EOL . $text);
    }

    public function post()
    {
        ob_start();
        Dumper::dump($this->params);
        Dumper::dump($this->data);
        $text = ob_get_clean();
        Logger::addLog('http11.post', '____Hello POST____' . PHP_EOL . $text);
    }
}