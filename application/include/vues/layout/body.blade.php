@extends('system')

@section('body')

    @section('sidebar')
        This is the master sidebar.
        <a href="{{ \MVC\Classe\Url::link_rewrite( false, 'accueil', []) }}">Homepage</a>
        <a href="{{ \MVC\Classe\Url::link_rewrite( false, 'prestashop', []) }}">Prestashop</a>
        <a href="{{ \MVC\Classe\Url::link_rewrite( false, 'wordpress', []) }}">Wordpress</a>
        <a href="{{ \MVC\Classe\Url::link_rewrite( false, 'syf43', []) }}">Symfony 4.3</a>
        <a href="{{ \MVC\Classe\Url::link_rewrite( false, 'syf51', []) }}">Symfony 5.0.99</a>
    @show

    <div class="container">
        @yield('content')
    </div>

@endsection