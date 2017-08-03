<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{!! csrf_token() !!}" />

    <title>{!! \Config::get('constants.PANEL_NAME') !!} </title>

    <!-- Bootstrap Core CSS -->
    {!! Html::style('assets/css/bootstrap.min.css') !!}

    <!-- MetisMenu CSS -->
    {!! Html::style('assets/components/metis-menu/metisMenu.min.css') !!}

    <!-- Template CSS -->
    {!! Html::style('assets/css/style.css') !!}

    <!-- Custom CSS -->
    {!! Html::style('assets/css/template.css') !!}

    <!-- Custom Fonts -->
    {!! Html::style('assets/components/font-awesome/css/font-awesome.min.css') !!}

    <!-- Select2 CSS -->
    {!! HTML::style('assets/components/select2/select2.min.css') !!}
    {!! HTML::style('assets/css/fSelect.css') !!}
    {!! HTML::style('assets/css/jquery-ui.css') !!}
    {!! HTML::style('assets/css/jquery-ui-timepicker.css') !!}
    {!! HTML::style('assets/css/template.css') !!}

    <!-- jQuery -->
    {!! HTML::script('assets/js/jquery.min.js') !!}

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- script anychart column start -->
    @yield('script')


</head>

<body onload="startTime()">

<div id="wrapper" >

<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top nav-red" role="navigation" style="margin-bottom: 0">
    @include('layouts.header')
    @include('layouts.sidebar')


</nav>

<!-- Page Content -->
    @yield('content')

</div>

<!--processing-->
<div class="loader">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <p class='text-center'>
                    {!! HTML::image('/assets/images/preload.gif', 'processing', ['class' => 'processing', 'width' => 40]) !!}
                </p>
                <p class='text-center message'>Loading...</p>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- end processing -->
<div class="backDrop"> </div>
<!-- Modal Start for editing all-->
<div class="modal fade" id="dynamicEdit" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="formTitle"> </h4>
            </div>
            <div id="dataResult" class="modal-body clearfix padding0 paddingtop20 overlay-form-modal">
                ...
            </div>
        </div>
    </div>
</div>
<!-- Modal editing End-->
<!-- /#wrapper -->
@yield('script')
<!-- Placed at the end of the document so the pages load faster -->
<script type="text/javascript">
    $(document).ready(function() {
        $('.nav-link-container').click(function() {
            $('.nav-container').toggleClass('nav-active-menu-container');
        });
        $(".nav-close-menu-li > a").click(function(event){
            $(".nav-container").toggleClass("nav-active-menu-container");
        });
    });
</script>



{!! HTML::script('assets/js/jquery-ui.js') !!}

<!-- Bootstrap Core JavaScript -->
{!! HTML::script('assets/js/bootstrap.min.js') !!}

<!-- Metis Menu Plugin JavaScript -->
{!! HTML::script('assets/components/metis-menu/metisMenu.min.js') !!}

<!-- Custom Theme JavaScript -->
{!! HTML::script('assets/js/main.js') !!}

<!-- Le javascript -->

<!-- Jquery Form JavaScript -->
{!! HTML::script('assets/js/jquery.form.js') !!}
<!-- Input Mask JavaScript -->
{!! HTML::script('assets/components/input-mask/jquery.inputmask.js') !!}
{!! HTML::script('assets/components/input-mask/jquery.inputmask.date.extensions.js') !!}
{!! HTML::script('assets/components/select2/select2.min.js') !!}
{!! HTML::script('assets/js/fSelect.js') !!}
{!! HTML::script('assets/js/time.js') !!}
{!! HTML::script('assets/js/template.js') !!}

<!-- optional -->
{!! HTML::script('assets1/js/excanvas.min.js') !!}
{!! HTML::script('assets1/megamenu/webslidemenu.js') !!}
<!-- optional -->


<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
{!! HTML::script('assets1/js/ie10-viewport-bug-workaround.js') !!}
{!! HTML::style('assets1/news-ticker/modern-ticker.css') !!}
{!! HTML::script('assets1/news-ticker/jquery.modern-ticker.min.js') !!}



</body>

</html>