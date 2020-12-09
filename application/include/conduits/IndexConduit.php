<?php


use MVC\Classe\Implement\Conduit;

class IndexConduit extends Conduit
{
    // Route('/')
    public function homepage()
    {
        echo "IndexControlleur";
        return $this->render('index', array("templating_a"=>'blade',"templating_b"=>'twig',"templating_c"=>'edge'));
    }

}