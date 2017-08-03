@extends('layouts.admin')
@section('content')
<div id="page-wrapper">
    <!-- start: PAGE HEADER -->
    <div class="row topheading-row">
        <div class="col-lg-6 col-md-6 col-sm-9 col-xs-12">
            <h1 class="page-header margintop10">{!! lang('common.edit_heading', lang('hsn.hsn')) !!}   #{{ $hsn->hsn_code }}</h1>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-3 col-xs-12">
            <a class="btn btn-sm btn-danger pull-right margintop10 marginbottom10" href="{!! route('hsn.index') !!}"> <i class="fa fa-arrow-left fa-fw"></i> {!! lang('common.back') !!} </a>
        </div>

        <!-- /.col-lg-12 -->
    </div>
    <!-- end: PAGE HEADER -->
    <!-- start: PAGE CONTENT -->

    {{-- for message rendering --}}
    @include('layouts.messages')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding0">
        {!! Form::model($hsn, array('route' => array('hsn.update', $hsn->id), 'method' => 'PATCH', 'id' => 'hsn-form', 'class' => 'form-horizontal')) !!}
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-external-link-square"></i>
                        {!! lang('hsn.hsn_detail') !!}
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('hsn_code', lang('hsn.hsn'), array('class' => 'col-sm-4 control-label')) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('hsn_code', $hsn->hsn_code , array('class' => 'form-control')) !!}
                                    </div>
                                </div>

                                {{--<div class="form-group">
                                    {!! Form::label('username', lang('user.username'), array('class' => 'col-sm-4 control-label')) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('username', $user->username, array('class' => 'form-control')) !!}
                                    </div>
                                </div>

                                --}}

                                    {!! Form::hidden('company_id', loggedInCompanyId()) !!}

                                <div class="form-group">
                                    {!! Form::label('status', lang('common.active') . '&nbsp;', array('class' => 'col-sm-4 control-label')) !!}
                                    <div class="col-sm-8">
                                        <label class="checkbox col-sm-4">
                                            {!! Form::checkbox('status', '1' , ($hsn->status == '1') ? true : false) !!}
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-12 margintop5 clearfix text-center">
                                    <div class="form-group margin0">
                                        {{--{!! Form::hidden('pemission_id', ($userPermissions == null)?"":$userPermissions->permission_id) !!}--}}
                                        {!! Form::submit(lang('common.update'), array('class' => 'btn btn-danger')) !!}
                                    </div>
                                </div>
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
