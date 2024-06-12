@extends('default.default')

@section('title', 'WAYF')

@section('content')
    <h1 style="text-align:center;">Welcome {{Auth::user()->first_name}} {{Auth::user()->last_name}}!</h1>

    <div class="row">
        <div class="col-sm-offset-3 col-sm-6 ">
            <div class="well">
                <h3 style="text-align:center;margin-top:0px;padding-top:0px;">My User Info</h3>
                <label>User ID: </label> {{Auth::user()->id}}<br>
                <label>First Name:</label> {{Auth::user()->first_name}}<br>
                <label>Last Name:</label> {{Auth::user()->last_name}}<br>
                <label>Email:</label> {{Auth::user()->email}}<br>
            </div>
            <div class="well">
                @foreach ($info as $idp) 
                    <h3 style="text-align:center;margin-top:0px;padding-top:0px;">My IDPs</h3>
                    <label>IDP:</label> {{$idp->idp->name}}<br>
                    <label>Last Login:</label> {{$idp->last_login}}<br>
                    @if(!is_null($idp->idp->logo)) 
                        <label>Logo:</label> {{$idp->idp->logo}}
                    @endif
                    <label>Unique ID:</label> {{$idp->unique_id}}<br>
                    @foreach($idp->attributes as $key => $value) 
                        <label>{{$key}}:</label> {{$value}}<br>
                    @endforeach
                    <hr>
                @endforeach
            </div>
        </div>
    </div>
@endsection