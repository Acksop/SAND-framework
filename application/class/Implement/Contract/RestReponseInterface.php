<?php

namespace MVC\Classe\Implement\Contract;

interface RestReponseInterface
{
    public function get();

    public function post();

    public function put();

    public function delete();
}
