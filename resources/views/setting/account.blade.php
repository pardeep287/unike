@extends('layouts.admin')
@section('content')
    <div id="page-wrapper">
        <!-- start: PAGE HEADER -->
        <div class="row topheading-row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h1 class="page-header margintop10">{!! lang('setting.manage_account') !!}</h1>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <a class="btn btn-sm btn-danger pull-right margintop10 marginbottom10 _back" href="javascript:void(0)"> <i class="fa fa-arrow-left fa-fw"></i> {!! lang('common.back') !!} </a>
            </div>

            <!-- /.col-lg-12 -->
        </div>

        <!-- end: PAGE HEADER -->
        <!-- start: PAGE CONTENT -->

        {{-- for message rendering --}}
        @include('layouts.messages')
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding0">
                {!! Form::open(array('route' => array('setting.manage-account'), 'id' => 'setting-myprofile', 'class' => 'form-horizontal')) !!}
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-external-link-square"></i> &nbsp;
                            {!! lang('setting.manage_detail') !!}
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        {!! Form::label('name', lang('common.name'), array('class' => 'col-sm-3 control-label')) !!}
                                        <div class="col-sm-8 paddingtop8">
                                            {!! \Auth::user()->username !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('old_password', lang('setting.old_password'), array('class' => 'col-sm-3 control-label')) !!}
                                        <div class="col-sm-6">
                                            {!! Form::password('password', array('class' => 'form-control' )) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('new_password', lang('setting.new_password'), array('class' => 'col-sm-3 control-label')) !!}
                                        <div class="col-sm-6">
                                            {!! Form::password('new_password', array('class' => 'form-control')) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('confirm_password', lang('setting.confirm_password'), array('class' => 'col-sm-3 control-label')) !!}
                                        <div class="col-sm-6">
                                            {!! Form::password('confirm_password', array('class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 margintop10 text-center">
                                    <div class="form-group marginright15">
                                        {!! Form::submit(lang('setting.change_password'), array('class' => 'btn btn-danger btn-lg')) !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- end: TEXT FIELDS PANEL -->
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <!-- /#page-wrapper -->
    </div>
@stop