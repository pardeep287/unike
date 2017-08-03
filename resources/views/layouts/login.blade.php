<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{!! \Config::get('constants.PANEL_NAME') !!} </title>

    <!-- Bootstrap Core CSS -->
    <link href="{!! URL::asset('assets/css/bootstrap.min.css') !!}" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="{!! URL::asset('assets/components/metis-menu/metisMenu.min.css') !!}" rel="stylesheet">

    <!-- Custom CSS
    <link href="{{--{!! URL::asset('assets/css/style.css') !!}--}}" rel="stylesheet">-->
    {!! Html::style('assets/css/style.css') !!}

    <!-- Custom Fonts -->
    <link href="{!! URL::asset('assets/components/font-awesome/css/font-awesome.min.css') !!}" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Please Sign In</h3>
                </div>
                <div class="panel-body">
                    {{--<form class="" method="POST" action="{{ route('login') }}">--}}
                        {!! Form::open(['url' => 'login', 'class' => '', 'method'=>'post']) !!}
                        {{ csrf_field() }}
                        <fieldset>

                            {{--print_r(session()->get('errors'))--}}
                            @include('layouts.messages')

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

                                <input class="form-control" placeholder="Username" name="username" type="text" autofocus>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            {{--<div class="checkbox">
                                <label>
                                    <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                </label>
                            </div>--}}
                            <!-- Change this to a button or input when using this as a form
                            <a href="{{--{!! route('password.request') !!}--}}" class="btn btn-lg btn-success btn-block">Login</a> -->
                            <button type="submit" class="btn btn-lg btn-success btn-block theme-color">
                                Login
                            </button>
                        </fieldset>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
{!! Html::script('assets/js/jquery.min.js') !!}

<!-- Bootstrap Core JavaScript -->
{!! Html::script('assets/js/bootstrap.min.js') !!}

<!-- Metis Menu Plugin JavaScript -->
{!! Html::script('assets/components/metis-menu/metisMenu.min.js') !!}

<!-- Custom Theme JavaScript -->
<script src="{!! URL::asset('assets/js/main.js') !!} "></script>

</body>

</html>
