@extends('default.default')

@section('title', 'Login')

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
window.forms.filter_form = {
    name:'filter_form',
    el:'#filter-form',
    legend:'',
    fields: [{name:'filter',label:'','help':'Search by Organization Name',type:'text',placeholder:'Organization\'s Name'}],
    actions: []
};
window.templates.main = `

<div class="row">
    <div class="col-sm-12">
    @{{^selected_idp}}
    <div class="row">
        <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12" style="margin-top:40px;">
            <div class="well" style="text-align:center;">
                <div style="margin-bottom:15px;">Where are you from?</div>
                @{{#enabled_idps}}
                    <div style="margin-bottom:15px;">
                        <div data-idpid="@{{id}}" class="btn btn-lg btn-primary idp-link" style="background-color:#005a43;border-color:#004131;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:100%;">
                            <i class="fa fa-lg fa-university"></i> @{{name}}
                        </div>
                    </div>
                @{{/enabled_idps}}

                <div style="margin-bottom:15px;">
                    <a href="/idp/google/@{{#redirect}}?redirect=@{{redirect}}@{{/redirect}}" class="btn btn-lg btn-default">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 48 48" class="abcRioButtonSvg"><g><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path><path fill="none" d="M0 0h48v48H0z"></path></g></svg>
                        Sign in with Google
                    </a>
                </div>

                or
                <hr>
                <div id="filter-form"></div>
                <div style="max-height:300px;overflow:scroll;">
                    <ul class="list-group"> 
                        @{{#show_filtered_idps}}
                            @{{#filtered_idps}}
                                <li data-idpid="@{{id}}" class="list-group-item idp-link" style="cursor:pointer;">@{{name}}</li>
                            @{{/filtered_idps}}
                        @{{/show_filtered_idps}}
                    </ul>
                </div>
                @{{#show_filtered_idps}}<div class="alert alert-info" style="margin-bottom:0px;">To log in, please select your organization's identity provider<br> from the list above</div>@{{/show_filtered_idps}}
            </div>
        </div>
    </div>
    @{{/selected_idp}}
    @{{#selected_idp}}
    <div class="row">
        <div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2" style="margin-top:40px;">
            <div class="selected-idp">
                <div class="alert alert-success">
                <center>
                    Redirecting to ... <br>
                    @{{#selected_idp.logo}}<img src="@{{selected_idp.logo}}" style="max-height:200px;max-width:400px;">@{{/selected_idp.logo}}<br>
                    <b>@{{selected_idp.name}}</b>
                <center>
                </div>
            </div>
        </div>
    </div>
    @{{/selected_idp}}
    </div>
</div>
`;
ractive.resetTemplate(window.templates.main);

app.update();

app.form('filter_form','#filter-form').on('change',function(event){
    var filter = event.form.get().filter.toLowerCase();
    app.data.filtered_idps = _.filter(app.data.idps,function(o) {
        return _.toLower(_.deburr(o.name)).includes(filter)
    })
    app.data.show_filtered_idps = app.data.filtered_idps.length <= 50;
    app.update();
});

app.get('/saml2/idps',function(data) {
    app.data.idps = data;
    app.data.filtered_idps = _.cloneDeep(app.data.idps);
    app.data.show_filtered_idps = false;
    app.update();
})

app.click('.idp-link',function(event) {
    var idpid = event.currentTarget.dataset.idpid;
    app.data.selected_idp = _.find(app.data.idps, {id:parseInt(idpid)});
    app.update();
    app.get('/saml2/idps/'+idpid,function(data) {
        app.data.selected_idp = data;
        app.update();
    });
    setTimeout(function() {
        window.location = '/saml2/wayf/' + idpid + ((app.data.redirect != '')?'?redirect='+app.data.redirect:'');
}   ,2000);
})
</script>
@endsection

