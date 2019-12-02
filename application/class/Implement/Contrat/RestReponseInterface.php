<?php

namespace MVC\Classe\Implement\Contrat;

interface RestReponseInterface
{
    public function get();

    public function post();

    public function put();

    public function delete();
}