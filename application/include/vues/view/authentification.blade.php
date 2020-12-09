@extends('body')

@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
    <h1>Authentification</h1>
    <br /><br /><br />
    <a href="{{ \MVC\Classe\Url::link_rewrite(false, 'logout', []) }}">Log out ?</a>
    <hr/>
    Bonjour, {{$login}}

@endsection

