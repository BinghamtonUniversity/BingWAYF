<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate, max-stale=0, post-check=0, pre-check=0" />
    <title>BingWAYF | {{$title}}</title>
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css" />
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <!--<link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">-->
    <!-- Custom styles for this template -->
    <link href="/assets/css/bingwayf.css" rel="stylesheet">
    <link href="/assets/css/toastr.min.css" rel="stylesheet">
    <link href="/assets/css/font-awesome.min.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <!--<script src="../../assets/js/ie-emulation-modes-warning.js"></script>-->
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link data-name="vs/editor/editor.main" rel="stylesheet" href="/assets/js/vendor/vs/editor/editor.main.css">
  </head>
  <body>
    <style>@media (prefers-color-scheme: dark) {}</style>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/" style="background:#005A43;color:white;padding: 0px 0px 0px 25px;">
            <h3 style="color:#fff;margin-top:12px;"><i class="fa fa-share"></i> BingWAYF</h3>
          </a>
            <ul class="nav navbar-nav  hidden-xs">
                <li><a href="#"><h4 style="margin:0">{{$title}}</h4></a></li>
            </ul>
          <ul class="nav navbar-nav navbar-right hidden-xs">
          </li>
            <li><a href="#"><h4 style="margin:0"></h4></a></li>
          </ul>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
          <li><a href="/"><h4 style="margin:0;">BingWAYF Admin</h4></a>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle user-info" data-toggle="dropdown" role="button">
                <img class="gravatar" src="https://www.gravatar.com/avatar/{{ md5(Auth::user()->email) }}?d=mm" /> 
                {{ Auth::user()->first_name }} {{ Auth::user()->last_name }} 
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <li><a href="/"><i class="fa fa-arrow-left"></i> Home</a></li>
                <li><a href="{{ url('/logout') }}"><i class="fa fa-times-circle"></i> Logout</a></li>
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right visible-xs-block">
            <li><a href="/admin/users"><i class="fa fa-user fa-fw"></i>&nbsp; Users</a></li>
            <li><a href="/admin/idps"><i class="fa fa-users fa-fw"></i>&nbsp; IDPs</a></li>
            <li><a href="/admin/applications"><i class="fa fa-notes-medical fa-fw"></i>&nbsp; Applications</a></li>
            <li><a href="/admin/oauth_clients"><i class="fa fa-notes-medical fa-fw"></i>&nbsp; OpenID/OAuth Clients</a></li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="col-sm-3 col-md-2 sidebar">
      <ul class="nav nav-sidebar">
        <li class="@if($page=='users') active @endif"><a href="/admin/users"><i class="fa fa-user fa-fw"></i>&nbsp; Users</a></li>
        <li class="@if($page=='idps') active @endif"><a href="/admin/idps"><i class="fa fa-users fa-fw"></i>&nbsp; IDPs</a></li>
        <li class="@if($page=='applications') active @endif"><a href="/admin/applications" ><i class="fa fa-pills fa-fw"></i>&nbsp; Applications</a></li>
        <li class="@if($page=='oauth_clients') active @endif"><a href="/admin/oauth_clients"><i class="fa fa-notes-medical fa-fw"></i>&nbsp; OpenID/OAuth Clients</a></li>
      </ul>
    </div>
    <div class="container-fluid" id="main-container">
      <div class="row">
        <div class="col-sm-12 admin-main">
          <div id="content">
            @if(isset($help))
                <div class="alert alert-info">{{$help}}</div>
            @endif
            <div id="adminDataGrid"></div>
            <style>
            div#adminDataGrid > div.well > div {
                /* Make All Datagrid Stuff Scrollable Hack */
                overflow: scroll !important;
            }
            </style>
          </div>
        </div>
      </div>
    </div>
    <script src='/assets/js/vendor/jquery.min.js'></script>
    <script src="/assets/js/vendor/bootstrap.min.js"></script>
    <script src="/assets/js/vendor/lodash.min.js">
    <script>_.findWhere = _.find; _.where = _.filter;_.pluck = _.map;_.contains = _.includes;</script>
    <script src="/assets/js/vendor/toastr.min.js"></script>
    <script src="/assets/js/vendor/gform_bootstrap.js"></script>
    <script src="/assets/js/vendor/ractive.min.js"></script>
    <script src="/assets/js/vendor/GrapheneDataGrid.js"></script>
    <script src="/assets/js/_framework.js"></script>
    <script>
    @if(isset($id)) app.data.id={!!json_encode($id)!!}; @endif
    @if(isset($actions)) app.data.actions={!!json_encode($actions)!!}; @endif
    </script>
    <script src="/assets/js/admin/{{$page}}.js"></script>
  </body>
</html>
