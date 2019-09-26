<html>

<head>
    <title>{{$page_title}}</title>
    <meta name="description" lang="fr" content="{{$description}}"/>

    @section('top-css')
    @endsection

    @section('top-javascript')
    @endsection

</head>

<body>

@yield('body')

@section('bottom-javascript')
@endsection

</body>

</html>