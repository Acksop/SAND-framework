@extends('body')

@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
    <h1>Accueil de l'url</h1>
    <h2> {{$_SERVER['SERVER_NAME']}}</h2>
    <a href="{{ \MVC\Classe\Url::link_rewrite(false, 'authentification', []) }}">S'authentifier ?</a>
    <hr/>
    {{$templating_a}}::{{$templating_b}}::{{$templating_c}}

    {{\MVC\Classe\ControlleurAction::inserer('default',[])}}
    {{\MVC\Classe\ControlleurAction::inserer('default.default',[4,5,6])}}
    {{\MVC\Classe\ControlleurAction::inserer('default.variableSlug',['var1','var2','var3'])}}

@endsection

