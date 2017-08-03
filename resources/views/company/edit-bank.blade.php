@extends('layouts.admin-new')
@section('content')
<div id="page-wrapper">
    <!-- start: PAGE HEADER -->
    <div class="row">
        <div class="col-lg-12">
            <a class="btn btn-sm btn-default pull-right margintop10 _back" href="javascript:void(0)"> <i class="fa fa-arrow-left fa-fw"></i> {!! lang('common.back') !!} </a>
            <h1 class="page-header margintop10"> {!! lang('common.edit_heading', lang('company.company')) !!} #{{ $result->name }}</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- end: PAGE HEADER -->
    <!-- start: PAGE CONTENT -->
    
    {{-- for message rendering --}}
    @include('layouts.messages')
    <div class="row">
        <div class="col-md-12 padding0">
        {!! Form::model($result, array('route' => array('company.update-bank', $result->id), 'method' => 'PATCH', 'id' => 'company-form', 'class' => 'form-horizontal')) !!}
         <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-external-link-square"></i> &nbsp;
                    {!! lang('company.bank_detail') !!}
                </div>
                <div class="panel-body">
                    <div class="row">
                            
                                <div class="col-md-6 margintop20">
                                    <div class="form-group">
                                        {!! Form::label('name', lang('common.name'), array('class' => 'col-sm-4 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('name', null, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('account_number', lang('bank.account_number'), array('class' => 'col-sm-4 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('account_number', null, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('manager_name', lang('bank.manager_name'), array('class' => 'col-sm-4 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('manager_name', null, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('ifsc_code', lang('bank.ifsc_code'), array('class' => 'col-sm-4 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('ifsc_code', null, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('micr_code', lang('bank.micr_code'), array('class' => 'col-sm-4 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('micr_code', null, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        {!! Form::label('status', lang('common.status'), array('class' => 'col-sm-4 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::checkbox('status', '1', true) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 margintop20">
                                    <div class="form-group">
                                        {!! Form::label('mobile', lang('bank.mobile'), array('class' => 'col-sm-4 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('mobile', null, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('mobile2', lang('bank.mobile2'), array('class' => 'col-sm-4 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('mobile2', null, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('phone', lang('bank.phone'), array('class' => 'col-sm-4 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('phone', null, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('branch', lang('bank.branch'), array('class' => 'col-sm-4 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('branch', null, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('address', lang('bank.address'), array('class' => 'col-sm-4 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::textarea('address', null, array('class' => 'form-control', 'size' => '5x3')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 margintop10 clearfix text-center">
                                    <div class="form-group">
                                        {!! Form::submit(lang('common.update'), array('class' => 'btn btn-primary btn-lg')) !!}
                                    </div>
                                </div>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::hidden('company_id', $result->company_id) !!}
        {!! Form::close() !!}
        </div>
        <!-- end: TEXT FIELDS PANEL -->
    </div>
</div>
<!-- /#page-wrapper -->
@stop