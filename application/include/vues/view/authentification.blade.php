@extends('body')

@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
    <h1>Sign in</h1>

    <ul>
        @foreach ($hybridauth->getProviders() as $name)
            @if (!isset($adapters[$name]))
                <li>
                    <a href="{{ \MVC\Classe\Url::link_rewrite(false, 'authentificate', ['provider' => $name]) }}">
                        Sign in with {{ $name }}
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
@endsection

@section('top-javascript')
@endsection

