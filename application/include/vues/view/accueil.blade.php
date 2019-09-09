@extends('body')

@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
    <h1>Accueil</h1>
    <br /><br /><br />
    {{$templating_a}}::{{$templating_b}}::{{$templating_c}}

@endsection

