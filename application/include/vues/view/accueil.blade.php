@extends('body')

@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
    <h1>Accueil</h1>
    <br /><br /><br />
    <a href="{{ \MVC\Classe\Url::link_rewrite(false, 'authentification', []) }}">S'authentifier ?</a>
    <hr/>
    {{$templating_a}}::{{$templating_b}}::{{$templating_c}}

    {{\MVC\Classe\ControlleurAction::inserer('default',[])}}
    {{\MVC\Classe\ControlleurAction::inserer('default.default',[4,5,6])}}
    {{\MVC\Classe\ControlleurAction::inserer('default.variableSlug',['var1','var2','var3'])}}

    {{--\MVC\Classe\ControlleurAction::inserer('default.makeHttp11',[])--}}

@endsection

