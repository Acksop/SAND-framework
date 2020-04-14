<ul class="dropdown-menu">
    <li><a href="{{ \MVC\Classe\Url::link_rewrite( false, 'docs', []) }}">Summary</a></li>
    @if (isset($files))
        @foreach( $files as $file)
            <li><a href="{{ \MVC\Classe\Url::link_rewrite( false, 'docs', ['file'=>$file]) }}">{{ $file }}</a><li>
        @endforeach
    @endif
</ul>