<?php


use MVC\Classe\Implement\Conduit;

class FooConduit extends Conduit
{
    // Route('/foo')
    public function index()
    {
        $this->render('foo', ['page_title' => 'Foo', 'description' => 'FooConduit']);
    }

    // Route('/foo/{id}')
    public function load()
    {
        $this->render('foo', array('page_title' => 'Foo', 'description' => 'FooConduit', 'id' => $this->id));

    }
}