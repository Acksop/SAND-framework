@extends('body')

@section('sidebar')
    @parent
@endsection

@section('content')
    <h1>Admin de l'url</h1>
    <h2> {{$_SERVER['SERVER_NAME']}}</h2>
    <br /><br /><br />
    <a href="{{ \SAND\Classe\Url::link_rewrite(false, 'authentification', []) }}">S'authentifier ?</a>

@endsection

