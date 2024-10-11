<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="Binghamton University">
  
    <title>@yield('title') | BingWAYF</title>
    <!-- Bootstrap -->
         <!-- Custom Light / Dark Mode CSS -->
    <script>
      if (window.matchMedia('(prefers-color-scheme: dark)').media === 'not all') {
        document.documentElement.style.display = 'none';
        document.head.insertAdjacentHTML(
          'beforeend',
          '<link rel="stylesheet" href="/light.css" onload="document.documentElement.style.display = \'\'">',
        );
      }
    </script>
    <link rel="stylesheet" href="/assets/css/bootstrap.darkly.min.css" media="(prefers-color-scheme: dark)" />
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css" media="(prefers-color-scheme: light)" />
    <link href="/assets/css/bingwayf.css" rel="stylesheet">
    <!-- Font Awesome -->
    <!-- <link href="/assets/css/font-awesome.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <!--<link href="../../assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">-->
    <!-- Custom styles for this template -->
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <nav class="navbar navbar-default navbar-inverse navbar-fixed-top" style="background-color:#014634;border-width:0px">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/" style="background:#014634;color:white;padding: 0px 0px 0px 25px;">
            <h3 style="color:#fff;margin-top:6px;"><i class="fa fa-share"></i> BingWAYF<br>
              <span style="font-size:10px;height:10px;display:block;margin-left:40px;">Where are you from?</span>
            </h3>
          </a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" style="color:#ccc;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                @if(!is_null(Auth::user()))
                    <img class="gravatar" src="https://www.gravatar.com/avatar/{{ md5(Auth::user()->email) }}?d=mm" /> 
                    {{Auth::user()->first_name}} {{Auth::user()->last_name}} <span class="caret"></span>
                @endif
                </a>
              @if(!is_null(Auth::user()))
                <ul class="dropdown-menu">
                  @can('app_admin', App\Models\User::class)
                    <li  style="color:#ccc;" ><a href="/admin"><i class="fa fa-user-shield fa-fw"></i> Admin</a></li>
                  @endcan
                  <li  style="color:#ccc;" ><a href="/logout"><i class="fa fa-times-circle fa-fw"></i> Logout</a></li>
                </ul>
              @endif
            </li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>

    <div class="container-fluid">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="hidden-xs" style=" position: fixed;
    text-align: center;
    bottom: 0px;
    right: 0px;
    left: 0px;
    padding: 5px 20px;
    z-index:-10">
    <i class="far fa-copyright fa-fw"></i> {{ now()->year }} Binghamton University</a>
    </div>
    </footer>
    <!-- Footer -->
    <script src='/assets/js/vendor/jquery.min.js'></script>
    <script src="/assets/js/vendor/bootstrap.min.js"></script>
    <script src="/assets/js/vendor/lodash.min.js">
    <script>_.findWhere = _.find; _.where = _.filter;_.pluck = _.map;_.contains = _.includes;</script>
    <script src="/assets/js/vendor/toastr.min.js"></script>
    <script src="/assets/js/vendor/gform_bootstrap.js"></script>
    <script src="/assets/js/vendor/ractive.min.js"></script>
    <script src="/assets/js/_framework.js"></script>
    @yield('data')
    @yield('scripts')
  </body>
</html>
