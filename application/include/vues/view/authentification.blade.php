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
                    <a href="#" onclick="javascript:auth_popup('{{ $name }}');">
                        Sign in with {{ $name }}
                    </a>
                </li>
            @endif
        @endforeach
        <ul>
            @endsection

            @section('top-javascript')
                <script>
                    function auth_popup(provider) {
                        // replace 'path/to/hybridauth' with the real path to this script
                        var authWindow = window.open('/control/authentification-callback-example/provider/' + provider, 'authWindow', 'width=600,height=400,scrollbars=yes');
                        return false;
                    }
                </script>
@endsection
