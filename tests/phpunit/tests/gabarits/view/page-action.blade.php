@extends('body')

@section('content')
{{ \SAND\Classe\ControlleurAction::inserer('test.renderSalut', [$qui, 'blade']) }}
@endsection
