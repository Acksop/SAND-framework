@extends('system')

@section('body')

    @section('sidebar')
        This is the master sidebar.
        <a href="{{ \MVC\Classe\Url::link_rewrite( false, 'accueil', []) }}">Homepage</a>
        @if (\MVC\Classe\Session::isRegistered())
            <a href="{{ \MVC\Classe\Url::module_link_rewrite( 'prestashop', ['admin-dev']) }}">E-commerce</a>
            <a href="{{ \MVC\Classe\Url::module_link_rewrite( 'wordpress', ['wp-admin']) }}">Blog</a>
            <a href="{{ \MVC\Classe\Url::module_link_rewrite( 'phplist', ['admin']) }}">Newsletter</a>
        @else
            <a href="{{ \MVC\Classe\Url::module_link_rewrite( 'prestashop', ['']) }}">E-commerce</a>
            <a href="{{ \MVC\Classe\Url::module_link_rewrite( 'wordpress', ['']) }}">Blog</a>
            <a href="{{ \MVC\Classe\Url::module_link_rewrite( 'phplist', ['']) }}">Newsletter</a>
        @endif
        <a href="{{ \MVC\Classe\Url::link_rewrite( false, 'symfony', []) }}">Symfony 5.1</a>

        <a href="{{ \MVC\Classe\Url::link_rewrite( false, 'Policy', []) }}">Policy</a>
        <a href="{{ \MVC\Classe\Url::link_rewrite( false, 'CGU', []) }}">CGU</a>
    @show

    <div class="container">
        @yield('content')
    </div>

@endsection