<?php


use MVC\Classe\Implement\Conduit;

class FooConduit extends Conduit
{
    // Route('/foo')
    public function index()
    {
        \MVC\Object\Session::createAndTestSession();
        echo "blob of foo";
        return $this->render('foo', array('page_title' => 'Foo', 'description' => 'FooConduit'));
    }

    // Route('/foo/{id}')
    public function load()
    {
        \MVC\Object\Session::createAndTestSession();
        echo "load of foo";
        return $this->render('foo', array('page_title' => 'Foo', 'description' => 'FooConduit', 'id' => $this->id));
    }
}
