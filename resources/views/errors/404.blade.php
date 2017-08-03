@extends('layouts.admin')
@section('content')
    <div id="page-wrapper">
        <!-- start: PAGE HEADER -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header margintop10">{!! lang('common.oops_404') !!}</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- end: PAGE HEADER -->
        <!-- start: PAGE CONTENT -->
        <div class="row">
            <div class="col-md-12 not-found padding0 text-center">
                <div class="error-404">
                    404
                </div>
            </div>
            <div class="col-md-4 col-md-offset-4 not-found text-center">
                <div class="content">
                    <h1>{!! lang('common.something_wrong') !!}</h1>
                    <p>
                        {!! lang('common.worry') !!}
                    </p>
                    <div class="btn-group">
                        <a  href="javascript:void(0)" class="btn _back btn-danger"><i class="fa fa-chevron-left"></i> {!! lang('common.go_back') !!}</a>
                        <a href="{!! route('home') !!}" class="btn  btn-default"> {!! lang('master.dashboard') !!}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /#page-wrapper -->
@stop