@extends('body')

@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')

    <h1>Compte utilisateur</h1>

    @foreach ($extractedData as $key => $value)
        {{ $key }} :: {{ $value }}
    @endforeach

    @if ($adapters)
        <h1>You are logged in:</h1>
        <ul>
            @foreach ($adapters as $name => $adapter)
                <li>
                    <strong>{{$adapter->getUserProfile()->displayName }}</strong> from
                    <i>{{ $name }}</i>
                    <span>(<a href="{{$config['callback'] }}?logout={{ $name }}" ; ?>">Log Out</a>)</span>
                </li>
            @endforeach
        </ul>
    @endif


@endsection

