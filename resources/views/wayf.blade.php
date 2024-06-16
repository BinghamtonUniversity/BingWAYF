@extends('default.default')

@section('title', 'WAYF')

@section('content')
<div class="row">
    <center><h1 style="text-align:center;">Binghamton University's Federated Login</h1></center>
    <div class="col-lg-4 col-lg-offset-4 col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2" style="margin-top:20px;">
        <!-- here -->
        <div id="main_target"></div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <center><div>To log in, please select your organization's identity provider from the list above</div></center>
        </div>
    </div>
</div>
<!--
<div class="row">
    <div class="col-sm-8 col-sm-offset-2">
      <div class="alert alert-info">
        <p>
            BingWAYF is ... 
        </p>
      </div>
    </div>
</div>
-->
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
    fields: [{name:'filter',label:'','help':'Filter by Organization Name',type:'text',placeholder:'Organization\'s Name'}],
    actions: []
};
window.templates.main = `
<div id="filter-form"></div>
<div style="height:300px;overflow:scroll;margin-bottom:20px;">
<ul class="list-group"> 
    @{{#loading}}
        <li class="list-group-item">Loading ...</li>
    @{{/loading}}
    @{{#filtered_idps}}
        <a href="/saml2/wayf/@{{id}}@{{#redirect}}?redirect=@{{redirect}}@{{/redirect}}" class="list-group-item"><i style="margin-top: 4px;" class="fa fa-lock fa-lg fa-fw pull-right"></i>@{{name}}</a>
    @{{/filtered_idps}}
</ul>
</div>
`;
ractive.resetTemplate(window.templates.main);

app.data.loading = true;
app.update();

app.form('filter_form','#filter-form').on('change',function(event){
    var filter = event.form.get().filter;
    app.data.filtered_idps = _.filter(app.data.idps,function(o) {
        return _.toLower(_.deburr(o.name)).includes(filter)
    })
    app.update();
});

app.get('/saml2/idps',{},function(data) {
    app.data.idps = data;
    app.data.filtered_idps = _.cloneDeep(app.data.idps);
    app.data.loading = false;
    app.update();
})

</script>
@endsection

