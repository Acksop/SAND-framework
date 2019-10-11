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

@endsection

