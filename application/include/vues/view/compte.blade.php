@extends('body')

@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
    <a href="{{ \MVC\Classe\Url::link_rewrite( false, 'authentification', []) }}">Authentification</a>
@endsection

@section('content')

    <h1>Compte utilisateur</h1>

    @if ($adapters)
        <h1>You are logged in:</h1>
        <ul>
            @foreach ($adapters as $name => $adapter)
                <li>
                    from <i>{{ $name }}</i>
                {{ \MVC\Classe\Dumper::dump($adapter->getUserProfile()) }}
                <!--<span>(<a href="{{ \MVC\Classe\Url::link_rewrite( true, 'authentification-callback-example', ['logout'=>$name ]) }}"
                              ">Log Out</a>)</span>-->
                    <span>(<a href="{{ \MVC\Classe\Url::link_rewrite( false, 'logout', ['logout'=>$name ]) }}">Log Out</a>)</span>
                </li>
            @endforeach
        </ul>
    @endif


@endsection

