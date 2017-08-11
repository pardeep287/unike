@extends('layouts.admin ')
@section('content')
<div id="page-wrapper">
    <!-- start: PAGE HEADER -->
    <div class="row">
        <div class="col-lg-12">
            <a class="btn btn-sm btn-default pull-right margintop10 _back" href="javascript:void(0)"> <i class="fa fa-arrow-left fa-fw"></i> {!! lang('common.back') !!} </a>
            <h1 class="page-header margintop10">{!! lang('common.create_heading', lang('company.company')) !!}</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- end: PAGE HEADER -->
    <!-- start: PAGE CONTENT -->
    
    {{-- for message rendering --}}
    @include('layouts.messages')
    <div class="row">
        <div class="col-md-12 padding0">
            {!! Form::open(array('method' => 'POST', 'route' => array('company.store'), 'id' => 'company-form', 'class' => 'form-horizontal')) !!}
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-external-link-square"></i> &nbsp;
                        {!! lang('company.company_detail') !!}
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('company_name', lang('company.company_name'), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('company_name', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('contact_person', lang('company.contact_person'), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('contact_person', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group hidden">
                                {!! Form::label('brand_name', lang('company.brand_name'), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('brand_name', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group hidden">
                                {!! Form::label('abn_number', lang('setting.abn_number'), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('abn_number', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('tin_number', lang('company.tin_number'), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('tin_number', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('pan_number', lang('company.pan_number'), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('pan_number', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('gst_number', lang('company.gst_number'), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('gst_number', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('email1', lang('company.email1'), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('email1', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('email2', lang('company.email2'), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('email2', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('mobile1', lang('company.mobile1'), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('mobile1', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('mobile2', lang('company.mobile2'), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('mobile2', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('phone', lang('company.phone'), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('phone', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>


                            <div class="form-group">
                                {!! Form::label('status', lang('common.active') . '&nbsp;', array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    <label class="checkbox col-sm-3">
                                        {!! Form::checkbox('status', '1', true) !!}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                {!! Form::label('website', lang('company.website'), array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('website', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('permanent_address', lang('company.permanent_address'), array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::textarea('permanent_address', null, array('class' => 'form-control', 'size' => '5x4')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('correspondence_address', lang('company.correspondence_address'), array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::textarea('correspondence_address', null, array('class' => 'form-control', 'size' => '5x4')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('country', lang('company.country'), array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('country', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('state', lang('company.state'), array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('state', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('city', lang('company.city'), array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('city', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('pincode', lang('company.pincode'), array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('pincode', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group hidden">
                                {!! Form::label('timezone', lang('company.timezone'), array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('timezone', $timezone, null, array('class' => 'form-control select2')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                    {!! Form::label('is_full_version', lang('company.is_full_version') . '&nbsp;', array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    <label class="checkbox col-sm-3">
                                        {!! Form::checkbox('is_full_version', '1', false) !!}
                                    </label>
                                </div>
                            </div>

                        </div>

                        <div class="col-sm-12 margintop10 clearfix text-center">
                            <div class="form-group">
                                {!! Form::submit(lang('common.save'), array('class' => 'btn btn-danger btn-lg')) !!}
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