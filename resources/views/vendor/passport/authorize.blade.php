@extends('default.default')

@section('title', 'Permission Denied')

@section('content')
    <div id="main_target">
        <h1>Permission Denied</h1>
        <div class="alert alert-danger">
            You do not have permission to use the {{$client->name}} application.
            If you think that this is an error, please open a ticket with your mom.
        </div>
    </div>
@endsection
