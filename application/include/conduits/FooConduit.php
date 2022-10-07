<?php


use SAND\Classe\Implement\Conduit;

class FooConduit extends Conduit
{
    // Route('/foo')
    public function index()
    {
        \SAND\Object\Session::createAndTestSession();
        echo "blob of foo";
        return $this->render('foo', array('page_title' => 'Foo', 'description' => 'FooConduit'));
    }

    // Route('/foo/{id}')
    public function load()
    {
        \SAND\Object\Session::createAndTestSession();
        echo "load of foo";
        return $this->render('foo', array('page_title' => 'Foo', 'description' => 'FooConduit', 'id' => $this->id));
    }
}
