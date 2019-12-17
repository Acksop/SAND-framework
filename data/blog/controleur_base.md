# Le contrôleur de base

il vous faut instancier trois fichiers sous cette forme:

* application/include/modeles/name.model
```
name : name
page_title : Page de l'application
description : Description de la page
params1 : {val1,val2}
params2 : val
```
il faut absolument renseigner le name avec le nom générique de la page, page_title permet de modifier le contenu de la balise html title et description permet de modifier le contenu de la balise meta description. Enfin les parametres suivant sont optionnels et permet de passer des valeur dans le controlleur ou dans la vue.

* application/include/controlleurs/name.php
```php
<?php
use MVC\Classe\Logger;
$templateData = array(
	"templating_a"=>'blade',
	"templating_b"=>'twig',
	"templating_c"=>'edge'
	);
//recuperation des paramètres contenus dans le .model
$var[] = $params2;
foreach( $params1 as $key => $value ){
	$var[] = $value;
}
Logger::addLog('ok', 'Hello world');
```
tout en sachant que la variable `$templateData` est envoyé à la vue Blade

* application/include/vues/view/name.blade.php
```php
@extends('body')

@section('sidebar')
 @parent
  <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
    <a href="{{ \MVC\Classe\Url::link_rewrite(false, 'accueil', []) }}">Revenir a l'acceuil ?</a>
    <hr/>
    {{$templating_a}}::{{$templating_b}}::{{$templating_c}}
    {{-- récupération des paramètres contenus dans le model --}}
    {{$params2}}
    @foreach ($param1 as $key => $value)
	    {{$key}} -> {{$value}}
    @endforeach

@endsection
```
par exemple...