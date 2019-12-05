<html>
<head>
    <title>App Name</title>
</head>
<body>

<div class="container">
    Foo Controlleur
    @if (isset($id))
        {{$id}}
    @else
        id not exist
    @endif
</div>
</body>
</html>