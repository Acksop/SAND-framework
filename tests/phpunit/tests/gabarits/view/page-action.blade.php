@extends('body')

@section('content')
{{ \MVC\Classe\ControlleurAction::inserer('test.renderSalut', [$qui, 'blade']) }}
@endsection
