@extends('body')

@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
    <div class="container">
        {{$cgu}}
    </div>
@endsection