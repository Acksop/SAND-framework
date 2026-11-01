<?php

use SAND\Classe\Implement\RestReponse;

/**
 * Réponse applicative dont le constructeur n'appelle pas parent::__construct() :
 * Controlleur::callHttpResponse() doit se replier sur instanciate().
 */
class SansParentHttpReponse extends RestReponse
{
    /** @var bool */
    public $construit = false;

    public function __construct()
    {
        $this->construit = true;
    }

    public function get()
    {
        return 'get:' . json_encode($this->params) . ':' . json_encode($this->data);
    }
}
