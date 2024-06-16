@extends('default.default')

@section('title', 'WAYF')

@section('content')
<div class="row">
<center><h1 style="text-align:center;">Welcome to BingWAYF!</h1></center>
<center><h3 style="text-align:center;">To Log In, Please Select Your Identity Provider From The List Below:</h1></center>
    <div class="col-sm-4 col-sm-offset-4">
        <!-- here -->
        <div id="main_target"></div>
    </div>
</div>
<div class="row">
    <div class="col-sm-8 col-sm-offset-2">
      <div class="alert alert-info">
        <p>
            BingWAYF is ... 
        </p>
      </div>
    </div>
</div>
@endsection

@section('data')
<script>
app.data = <?php echo json_encode($data); ?>;
</script>
@endsection

@section('scripts')
<script>
window.templates.main = `
    <div style="max-height:300px;overflow:scroll;margin-bottom:10px;">
    <ul class="list-group"> 
        @{{#idps}}
            <a href="/saml2/wayf/@{{id}}@{{#redirect}}?redirect=@{{redirect}}@{{/redirect}}" class="list-group-item"><i style="margin-top: 4px;" class="fa fa-lock fa-lg fa-fw pull-right"></i>@{{name}}</a>
        @{{/idps}}
    </ul>
    </div>
`;
ractive.resetTemplate(window.templates.main);

app.update();
</script>
@endsection

