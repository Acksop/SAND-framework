@extends('body')

@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
    Foo Controlleur
    @if (isset($id))
        {{$id}}
    @else
        id not exist
    @endif
@endsection