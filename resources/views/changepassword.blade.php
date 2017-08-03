@extends('layouts.admin-new')
@section('content')
    <div id="page-wrapper">
        <!-- start: PAGE HEADER -->
        <div class="row topheading-row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h1 class="page-header margintop10">{!! lang('common.change_password') !!}</h1>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6  col-xs-12">
                <a class="btn btn-sm btn-default pull-right margintop10 marginbottom10 _back" href="javascript:void(0)">
                    <i class="fa fa-arrow-left fa-fw"></i> {!! lang('common.back') !!} </a>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- end: PAGE HEADER -->
        <!-- start: PAGE CONTENT -->
        {{-- for message rendering --}}
        @include('layouts.messages')
        <div class="row">
            <div class="col-md-12 padding0">
                {!! Form::open(array('method' => 'POST', 'route' => array('updatepassword'), 'id' => 'changepassword-form', 'class' => 'form-horizontal')) !!}
                <div class="col-md-6">
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group">
                                    {!! Form::label('old_password', lang('common.old_password'), array('class' => 'col-sm-3 control-label')) !!}
                                    <div class="col-sm-8">
                                        {!! Form::password('old_password', null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('new_password', lang('common.new_password'), array('class' => 'col-sm-3 control-label')) !!}
                                    <div class="col-sm-8">
                                        {!! Form::password('new_password', null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('confirm_password', lang('common.confirm_password'), array('class' => 'col-sm-3 control-label')) !!}
                                    <div class="col-sm-8">
                                        {!! Form::password('confirm_password', null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 margintop10 clearfix text-center">
                                <div class="form-group">
                                    {!! Form::submit(lang('common.submit'), array('class' => 'btn btn-primary')) !!}
                                </div>
                            </div>

                        </div>
                    <!-- end: TEXT FIELDS PANEL -->
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop