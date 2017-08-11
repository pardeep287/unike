@extends('layouts.admin')
@section('content')
<div id="page-wrapper">
    <!-- start: PAGE HEADER -->
    <div class="row topheading-row">
        <div class="col-lg-6 col-md-6 col-sm-9 col-xs-12">
            <h1 class="page-header margintop10"> {!! lang('common.edit_heading', lang('role.role')) !!} #{{ $role->name }}</h1>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-3 col-xs-12">
            <a class="btn btn-sm btn-default pull-right margintop10 marginbottom10" href="{!! route('role.index') !!}"> <i class="fa fa-arrow-left fa-fw"></i> {!! lang('common.back') !!} </a>
        </div>

        <!-- /.col-lg-12 -->
    </div>
    <!-- end: PAGE HEADER -->
    <!-- start: PAGE CONTENT -->
    
    {{-- for message rendering --}}
    @include('layouts.messages')
    <div class="row">
        <div class="col-md-12">
        {!! Form::model($role, array('route' => array('role.update', $role->id), 'method' => 'PATCH', 'id' => 'role-form', 'class' => 'form-horizontal')) !!}
         
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-external-link-square"></i> &nbsp;
                        {!! lang('role.role_detail') !!}
                    </div>
                    <div class="panel-body">

                        <div class="row">
                            <div class="form-group">
                                {!! Form::label('name', lang('common.name'), array('class' => 'col-sm-3 control-label')) !!} 

                                <div class="col-sm-8">
                                    {!! Form::text('name', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('code', lang('common.code'), array('class' => 'col-sm-3 control-label')) !!}

                                <div class="col-sm-8">
                                    {!! Form::text('code', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('status', lang('common.active') . '&nbsp;', array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-5 margintop8">
                                     {!! Form::checkbox('status', '1', ($role->status == '1') ? true : false) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 margintop20 clearfix text-center">
                            <div class="form-group">
                                {!! Form::submit(lang('common.update'), array('class' => 'btn btn-danger')) !!}
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
@stop