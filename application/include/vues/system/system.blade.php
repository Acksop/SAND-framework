<html>

<head>
    <title>{{$page_title}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" lang="fr" content="{{$description}}"/>

    @section('top-css')
        <link rel="stylesheet" href="{{ \MVC\Classe\Url::asset_rewrite('assets/bootstrap-5.0.0-beta1-dist/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{ \MVC\Classe\Url::asset_rewrite('assets/css/custom.css')}}">
    @show

</head>

<body>

@section('top-javascript')
@show

@yield('body')

@section('bottom-javascript')
    <script src="{{ \MVC\Classe\Url::asset_rewrite('assets/bootstrap-5.0.0-beta1-dist/js/bootstrap.min.js')}}"></script>
    <script src="{{ \MVC\Classe\Url::asset_rewrite('assets/js/custom.js')}}"></script>
@show

</body>

</html>