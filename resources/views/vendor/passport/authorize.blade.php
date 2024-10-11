@extends('default.default')

@section('title', 'Permission Denied')

@section('content')
<?php dd($client); ?>
    <div class="row">
        <div class="col-sm-offset-3 col-sm-6 ">
            <h1>Permission Denied</h1>
            <div class="alert alert-warning">
                You do not have permission to use the "{{$client->name}}" application.
                If you believe that this is in error, please contact 
                <a href="mailto:itsresearchsupport@binghamton.edu">itsresearchsupport@binghamton.edu</a>.
            </div>
            <a href="/"><i class="fa fa-arrow-left fa-fw"></i> Return to Home</a>
        </div>
    </div>
@endsection
