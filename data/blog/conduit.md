# Le conduit
Cette fonctionnalité permet de choisir la route indépendamment du moteur.
il faut instancier le fichier `application/config/files/routing.yml`
avec la route de base et les routes que vous voulez conduire, par exemple:
```yml
home_route:  
  path:     /  
  defaults: { controller: 'FooController::indexAction' }  
  
foo_route:  
  path:     /foo  
  defaults: { controller: 'FooConduit::index' }  
  
foo_placeholder_route:  
  path:     /foo/{id}  
  defaults: { controller: 'FooConduit::load' }  
  requirements:  
    id: '[0-9]+'
```

et définir le Conduit correspondant avec les méthodes correspondantes dans le dossier `application/include/conduits`, ici:
```php
<?php


use MVC\Classe\Implement\Conduit;

class FooConduit extends Conduit
{
    // Route('/foo')
    public function index()
    {
        echo "blob of foo";
        return $this->render('foo', array('page_title' => 'Foo', 'description' => 'FooConduit'));
    }

    // Route('/foo/{id}')
    public function load()
    {
        echo "load of foo";
        return $this->render('foo', array('page_title' => 'Foo', 'description' => 'FooConduit', 'id' => $this->id));

    }
}
```
