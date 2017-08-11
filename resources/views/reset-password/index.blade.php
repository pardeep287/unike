<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{!! \Config::get('constants.PANEL_NAME') !!} </title>
{{--<title>:: {!! Config::get('app.appname') . ' - ' .  Config::get('app.slogan') !!} ::</title>--}}

<!-- Bootstrap Core CSS -->
    <link href="{!! URL::asset('assets/css/bootstrap.min.css') !!}" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="{!! URL::asset('assets/components/metis-menu/metisMenu.min.css') !!}" rel="stylesheet">

<!-- Custom CSS
    <link href="{{--{!! URL::asset('assets/css/style.css') !!}--}}" rel="stylesheet">-->
{!! Html::style('assets/css/style.css') !!}
{!! Html::style('assets1/css/signin.css') !!}

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
            <div class="login-panel panel panel-default" style="box-shadow: 0px 0px 18px rgb(185, 4, 13);">
                <div class="panel-heading">
                    <h3 class="panel-title">{!! ucwords(\Config::get('constants.PANEL_NAME')) !!}</h3>
                </div>
                <div class="panel-body">
<div class="account-container">
    <div class="content clearfix">
        {!! Form::open(['route' => 'reset-password.reset', 'class' => 'form-login', 'method'=>'post']) !!}
            <h4>Reset Password - {!! ucwords(\Config::get('constants.PANEL_NAME')) !!}</h4>
        @if ($errors->count() > 0)
            {{--@if ($errors->has())--}}
                <div class="alert alert-danger">
                    <button data-dismiss="alert" class="close">
                        &times;
                    </button>
                    <i class="fa fa-times-circle"></i> &nbsp;
                    @foreach($errors->all() as $error)
                        {!! $error !!} <br>
                    @endforeach
                </div>
            @endif
            <div class="login-fields">
                <p>Please provide your details</p>
                <div class="field">
                    <label for="username">Username</label>
                    <input type="text" id="email" name="email" placeholder="Username / Email ID" class="login username-field" />
                </div>
            </div>

            <div class="login-actions">
                <button class="button btn btn-danger btn-large">Reset Password</button>
            </div>
        {!! form::close() !!}
    </div>
</div>
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

