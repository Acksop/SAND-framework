# Le contrôleur de REST / HTML

>Ici le modèle (.model) n'est pas instancié, cela ressemble à symfony.
Étendre avec RESTResponse permet d'ajouter les methode get, put, post, delete
Étendre avec HttpResponse permet de n'avoir que les méthode Http qui sont utilisées
 normalement avec tout php.
 
>Donc vous pouvez tout a fait écrire une application avec HttpResponse car l'on peut appeler des vues blade par la méthode render().
C'est un choix applicatif qu'il faut faire au moment de la contruction de l'application.
 

Vous devez instancier le fichier `application/include/controlleurs/{Name}HttpReponse.php`
qui est une classe peut implémenter `MVC\Classe\Implement\RestReponse` ou `MVC\Classe\Implement\HttpReponse` sachant que la différence se situe au niveau des méthodes qu'il doit instancier.

*Voici un exemple avec `RestResponse`
```php
<?php  
  
use MVC\Classe\Dumper;  
use MVC\Classe\Implement\RestReponse;  
use MVC\Classe\Logger;  
  
class NameHttpReponse extends RestReponse  
{  
  
  public function put()  
	 {  
		 ob_start();  
		 Dumper::dump($this->params);  
		 Dumper::dump($this->data);  
		  $text = ob_get_clean();  
		 Logger::addLog('put', '____Hello Put____' . PHP_EOL . $text);  
	 }
  public function delete()  
	 {
		 ob_start();  
		 Dumper::dump($this->params);  
		 Dumper::dump($this->data);  
		  $text = ob_get_clean();  
		 Logger::addLog('delete', '____Hello Delete:____' . PHP_EOL . $text);  
	 }  
  public function get()  
	 {  
		 ob_start();  
		 Dumper::dump($this->params);  
		 Dumper::dump($this->data);  
		  $text = ob_get_clean();  
		 Logger::addLog('get', '____Hello GET____' . PHP_EOL . $text);  
	 }  
  public function post()  
	 {  
		 ob_start();  
		 Dumper::dump($this->params);  
		 Dumper::dump($this->data);  
		  $text = ob_get_clean();  
		 Logger::addLog('post', '____Hello POST____' . PHP_EOL . $text);  
	 }
 }
```

*voici un exemple avec `HttpResponse`
```php
<?php  
  
use MVC\Classe\Dumper;  
use MVC\Classe\Implement\HttpReponse;  
use MVC\Classe\Logger;  
  
class NameHttpReponse extends HttpReponse  
{ 
  public function get()  
	 {  
		 ob_start();  
		 Dumper::dump($this->params);  
		 Dumper::dump($this->data);  
		  $text = ob_get_clean();  
		 Logger::addLog('get', '____Hello GET____' . PHP_EOL . $text);  
		 return $this->render('name', array('var' => $text));
	 }  
  public function post()  
	 {
		 ob_start();  
		 Dumper::dump($this->params);  
		 Dumper::dump($this->data);  
		  $text = ob_get_clean();  
		 Logger::addLog('post', '____Hello POST____' . PHP_EOL . $text);  
		 return $this->render('name', array('var' => $text));
	 }
}
```

l'accès se fait par l'url `http://monapp.local/{name}`