@extends('default.default')

@section('title', 'WAYF')

@section('content')
    <div id="main_target"></div>
@endsection

@section('data')
<script>
app.data = <?php echo json_encode($data); ?>;
</script>
@endsection

@section('scripts')
<script>
window.templates.main = `
    <h1 style="text-align:center;">Welcome @{{user.first_name}} @{{user.last_name}}!</h1>
    <div class="row">
        <div class="col-sm-offset-3 col-sm-6 ">
            <div class="well">
                <h3 style="text-align:center;margin-top:0px;padding-top:0px;">My User Info</h3>
                <label>User ID: </label> @{{user.id}}<br>
                <label>First Name:</label> @{{user.first_name}}<br>
                <label>Last Name:</label> @{{user.last_name}}<br>
                <label>Email:</label> @{{user.email}}<br>
            </div>
            <div class="well">
                <h3 style="text-align:center;margin-top:0px;padding-top:0px;">My IDPs</h3>
                @{{#info}} 
                    <label>IDP:</label> @{{idp.name}}<br>
                    <label>Last Login:</label> @{{last_login}}<br>
                    @{{#idp.logo}}
                        <label>Logo:</label> @{{idp.logo}}<br>
                    @{{/idp.logo}}
                    <label>Unique ID:</label> @{{unique_id}}<br>
                    @{{#attributes:key}} 
                        <label>@{{key}}:</label> @{{.}}<br>
                    @{{/attributes:key}} 
                    <hr>
                @{{/info}}
            </div>
        </div>
    </div>
`;
ractive.resetTemplate(window.templates.main);
app.update();
</script>
@endsection

