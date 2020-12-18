<html>

<head>
    <title>{{$page_title}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="author" content=""/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" lang="fr" content="{{$description}}"/>

    @section('top-css')
        <link rel="stylesheet" href="/assets/css/main.css">
        <link rel="stylesheet" href="/assets/css/plugins.css">
        <link rel="stylesheet" href="/assets/css/style.css">
    @show

</head>

<body>

@section('top-javascript')
@show

@yield('body')

@section('bottom-javascript')
    <script src="/assets/js/script.js" defer="defer"></script>
    <script src="/assets/js/jquery.js"></script>
    <script src="/assets/js/plugins.js"></script>
    <!--Template functions-->
    <script src="/assets/js/functions.js"></script>
@show

</body>

</html>