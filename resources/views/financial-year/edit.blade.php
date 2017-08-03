@extends('layouts.admin')
@section('content')
<!-- start: PAGE HEADER -->
<div id="page-wrapper">
<div class="row">
    <div class="col-lg-12">
        <div class="col-lg-6 col-md-6 col-sm-9  col-xs-12">
            <h1 class="page-header margintop10">{!! lang('common.edit_heading', lang('financial_year.financial_year')) !!}: #{!! $result->name !!}</h1>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-3  col-xs-12">
            <a class="btn btn-sm btn-danger pull-right margintop10" href="{!! route('financial-year.index') !!}"> <i class="fa fa-arrow-left fa-fw"></i> {!! lang('common.back') !!} </a>
        </div>

        <div class="clearfix"></div>
    </div>
</div>
@include('layouts.messages')
<div class="row">
    <div class="col-md-12 padding0">
        {!! Form::model($result, array('route' => array('financial-year.update', $result->id), 'method' => 'PATCH',  'id' => 'ajaxSave', 'class' => 'form-horizontal')) !!}

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-external-link-square"></i> &nbsp;
                    {!! lang('financial_year.financial_year') !!}
                </div>
                <div class="panel-body">

                    <div class="row1">
                        <div class="form-group">
                            {!! Form::label('name', lang('common.name'), array('class' => 'col-sm-3 control-label')) !!}

                            <div class="col-sm-8">
                                {!! Form::text('name', $result->name , array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('code', lang('common.from_date'), array('class' => 'col-sm-3 control-label')) !!}

                            <div class="col-sm-8">
                                {!! Form::text('from_date',  dateFormat('d-m-Y',$result->from_date) , array('class' => 'form-control date-picker')) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('to_date', lang('common.to_date'), array('class' => 'col-sm-3 control-label')) !!}

                            <div class="col-sm-8">
                                {!! Form::text('to_date', dateFormat('d-m-Y',$result->to_date) , array('class' => 'form-control date-picker')) !!}
                            </div>
                        </div>
                        <div class="form-group @if($result->status == 1) hide @endif">
                            {!! Form::label('status', lang('common.active') . '&nbsp;', array('class' => 'col-sm-3 col-xs-3 control-label')) !!}
                            <div class="col-sm-5 col-xs-5 margintop5">
                                    {!! Form::checkbox('status',1,($result->status == '1')?true:false)  !!}
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
@stop
