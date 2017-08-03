@extends('layouts.admin')
@section('content')
<div id="page-wrapper">
<!-- start: PAGE HEADER -->
<div class="row topheading-row">
    <div class="col-lg-6 col-md-6 col-sm-9  col-xs-12">
        <h1 class="page-header margintop10">{!! lang('common.create_heading', lang('financial_year.financial_year')) !!}</h1>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-3  col-xs-12">
        <a class="btn btn-sm btn-default pull-right margintop10 marginbottom10 _back" href="javascript:void(0)"> <i class="fa fa-arrow-left fa-fw"></i> {!! lang('common.back') !!} </a>
    </div>

    <div class="clearfix"></div>
</div>
@include('layouts.messages')
<div class="row">
    <div class="col-md-12 padding0">
        {!! Form::open(array('method' => 'POST', 'route' => array('financial-year.store'), 'id' => 'ajaxSave', 'class' => 'form-horizontal')) !!}
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
                                {!! Form::text('name', null, array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('from_date', lang('common.from_date'), array('class' => 'col-sm-3 control-label')) !!}

                            <div class="col-sm-8">
                                {!! Form::text('from_date',null, array('class' => 'form-control date-picker')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('to_date', lang('common.to_date'), array('class' => 'col-sm-3 control-label')) !!}

                            <div class="col-sm-8">
                                {!! Form::text('to_date',null, array('class' => 'form-control date-picker')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('status', lang('common.active') . '&nbsp;', array('class' => 'col-sm-3 col-xs-3 control-label')) !!}
                            <div class="col-sm-5 col-xs-5 margintop5">
                                {!! Form::checkbox('status', '1', true) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 margintop20 clearfix text-center">
                        <div class="form-group">
                            {!! Form::submit(lang('common.save'), array('class' => 'btn btn-danger')) !!}
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

