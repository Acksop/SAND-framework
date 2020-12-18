@extends('body')

@section('sidebar')
    @parent
@endsection

@section('content')
    @if (isset($files))

        <h1>Sommaire:</h1>

        @foreach( $files as $file)
            <a href="{{ \MVC\Classe\Url::link_rewrite( false, 'docs', ['file'=>$file]) }}">{{ $file }}</a> <br/>
        @endforeach
    @endif
    @if (isset($data))
        {{$data}}
    @endif
@endsection