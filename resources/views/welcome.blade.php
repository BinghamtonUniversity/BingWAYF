@extends('default.default')

@section('title', 'My Profile')

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
        <div class="col-sm-6 ">
            <div class="well">
                <h3 style="text-align:center;margin-top:0px;padding-top:0px;">My User Info</h3>
                <label>User ID: </label> @{{user.id}}<br>
                <label>First Name:</label> @{{user.first_name}}<br>
                <label>Last Name:</label> @{{user.last_name}}<br>
                <label>Email:</label> @{{user.email}}<br>
            </div>
            <div class="well">
                <h3 style="text-align:center;margin-top:0px;padding-top:0px;">My IDPs</h3>
                @{{#user_idps}}
                    <label>Type:</label> @{{type}}<br>
                    @{{#saml2_idp}}
                        <label>IDP:</label> @{{saml2_idp.name}}<br>
                        @{{#saml2_idp.logo}}
                            <label>Logo:</label> @{{saml2_idp.logo}}<br>
                        @{{/saml2_idp.logo}}
                    @{{/saml2_idp}}
                    <label>Unique ID:</label> @{{unique_id}}<br>
                    <label>Last Login:</label> @{{last_login}}<br>
                    @{{#attributes:key}} 
                        <label>@{{key}}:</label> @{{.}}<br>
                    @{{/attributes}} 
                    <hr>
                @{{/user_idps}}
            </div>
            <div class="well">
                <h3 style="text-align:center;margin-top:0px;padding-top:0px;">My OpenID Claims</h3>
                <label>email:</label> @{{claims.email}}<br>
                <label>given_name:</label> @{{claims.given_name}}<br>
                <label>family_name:</label> @{{claims.family_name}}<br>
                <label>name:</label> @{{claims.name}}<br>
                <label>sub:</label> @{{claims.sub}}<br>
                <label>preferred_username:</label> @{{claims.preferred_username}}<br>
                <label>groups:</label> @{{#claims.groups}}@{{.}}, @{{/claims.groups}}<br>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="well">
                <h3 style="text-align:center;margin-top:0px;padding-top:0px;">My Applications</h3>
                @{{#user_apps}} 
                    <label>Application:</label> <a href="@{{application.url}}">@{{application.name}}</a><br>
                    <label>Status:</label> @{{#approved}}Approved@{{/approved}}@{{^approved}}Not Approved@{{/approved}}<br>
                    <label>Last Login:</label> @{{last_login}}<br>
                    @{{#application.logo}}
                        <label>Logo:</label> @{{application.logo}}<br>
                    @{{/application.logo}}
                    @{{#application.description}}
                        @{{application.description}}<br>
                    @{{/application.description}}
                    <hr>
                @{{/user_apps}} 
            </div>
        </div>
        <div class="col-sm-3">
            <div class="well">
                <h3 style="text-align:center;margin-top:0px;padding-top:0px;">All Applications</h3>
                @{{#all_apps}} 
                    <label>Application:</label> <a href="@{{url}}">@{{name}}</a><br>
                    <label>Public:</label> @{{#public}}Yes@{{/public}}@{{^public}}No@{{/public}}<br>
                    @{{#logo}}
                        <label>Logo:</label> @{{logo}}<br>
                    @{{/logo}}
                    @{{#description}}
                        @{{description}}<br>
                    @{{/description}}
                    <hr>
                @{{/all_apps}} 
            </div>
        </div>
    </div>
`;
ractive.resetTemplate(window.templates.main);
app.update();
</script>
@endsection

