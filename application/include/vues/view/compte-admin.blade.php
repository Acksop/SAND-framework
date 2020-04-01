@extends('body')

@section('sidebar')
    @parent
@endsection

@section('content')
    @if (isset($id))
        {{$id}}
    @else
        id not exist
    @endif
@endsection