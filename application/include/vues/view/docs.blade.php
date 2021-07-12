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
        <div style="display:block;position:relative;width:450px;margin:auto;">
            @if (isset($previous))<a href="{{ \MVC\Classe\Url::link_rewrite( false, 'docs', ['file'=>$previous]) }}" class="btn btn-outline-info"> &laquo; Précedent</a>@endif
                <a href="{{ \MVC\Classe\Url::link_rewrite( false, 'docs', []) }}" class="btn btn-info">Sommaire</a>
            @if (isset($next))<a href="{{ \MVC\Classe\Url::link_rewrite( false, 'docs', ['file'=>$next]) }}" class="btn btn-outline-info">Suivant &raquo; </a>@endif
        </div>
        {{$data}}
        <div style="display:block;position:relative;width:450px;margin:auto;">
            @if (isset($previous))<a href="{{ \MVC\Classe\Url::link_rewrite( false, 'docs', ['file'=>$previous]) }}" class="btn btn-outline-info"> &laquo; Précedent</a>@endif
                <a href="{{ \MVC\Classe\Url::link_rewrite( false, 'docs', []) }}" class="btn btn-info">Sommaire</a>
            @if (isset($next))<a href="{{ \MVC\Classe\Url::link_rewrite( false, 'docs', ['file'=>$next]) }}" class="btn btn-outline-info">Suivant &raquo; </a>@endif
        </div>
    @endif
@endsection