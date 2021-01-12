@extends('body')

@section('sidebar')
    @parent
    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
    <h1>%PAGE% Controlleur</h1>
	<br/><br/><br/>
    @if (isset($id))
        {{$id}}
    @else
        id not exist
    @endif
@endsection

@section('top-css')
    @parent
@endsection

@section('top-javascript')
    @parent
@endsection

@section('bottom-javascript')
    @parent
@endsection