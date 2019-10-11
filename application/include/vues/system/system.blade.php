<html>

<head>
    <title>{{$page_title}}</title>
    <meta name="description" lang="fr" content="{{$description}}"/>

    @section('top-css')
    @endsection

</head>

<body>

@section('top-javascript')
@show

@yield('body')

@section('bottom-javascript')
@show

</body>

</html>