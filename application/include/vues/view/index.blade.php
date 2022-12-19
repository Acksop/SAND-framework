@extends('body')

@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
    <h1>Accueil de l'url</h1>
    <h2> {{$_SERVER['SERVER_NAME']}}</h2>
    <br /><br /><br />
    <hr/>
    {{$templating_a}}::{{$templating_b}}::{{$templating_c}}

    {{\SAND\Classe\ControlleurAction::inserer('default',[])}}
    {{\SAND\Classe\ControlleurAction::inserer('default.defaultBlade',[4,5,6])}}
    {{\SAND\Classe\ControlleurAction::inserer('default.variableSlug',['var1','var2','var3'])}}

@endsection

