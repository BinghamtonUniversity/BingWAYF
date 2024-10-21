@extends('default.default')

@section('title', 'Permission Denied')

@section('content')
<?php //$auth_client_id = $client['id'] ?>
    <div class="row">
        <div class="col-sm-offset-3 col-sm-6 ">
            <h1>Permission Denied</h1>
            <div class="alert alert-warning">
                You do not have permission to use the "{{$client->name}}" application.<br>
                <div class="btn btn-info" id="request-access-btn" data-id="{{$client->id}}">Request Access to {{$client->name}}</div>
            </div>
            <a href="/"><i class="fa fa-arrow-left fa-fw"></i> Return to Home</a>
        </div>
    </div>
@endsection

@section('scripts')
<script>
app.click('#request-access-btn',function(event) {
    var client_id = event.currentTarget.dataset.id;
    toastr.info("Processing ...")
    app.post('/api/oauth_clients/'+client_id+'/request_access',{},function(response) {
        toastr.success("Request Sent!")
    })
})
</script>
@endsection

