@extends('layouts.admin')
@section('content')
<div id="page-wrapper">
    <!-- start: PAGE HEADER -->
    <div class="row">
        <div class="col-lg-12">
            <a class="btn btn-sm btn-danger pull-right margintop10 _back" href="javascript:void(0)"> <i class="fa fa-arrow-left fa-fw"></i> {!! lang('common.back') !!} </a>
            <h1 class="page-header margintop10">{!! lang('common.create_heading', lang('customer.customer')) !!}</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- end: PAGE HEADER -->
    <!-- start: PAGE CONTENT -->
    
    {{-- for message rendering --}}
    @include('layouts.messages')
    <div class="row">
        <div class="col-md-12 padding0">
            {!! Form::open(array('method' => 'POST', 'route' => array('customer.store'), 'id' => 'customer-form', 'class' => 'form-horizontal')) !!}
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-external-link-square"></i> &nbsp;
                        {!! lang('customer.customer_detail') !!}
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('customer_name', lang('customer.customer_name'), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('customer_name', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('customer_code', lang('customer.customer_code'), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('customer_code', $code, array('class' => 'form-control','readonly')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('username', lang('customer.username'), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('username', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('password', lang('customer.password'), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::password('password', array('class' => 'form-control')) !!}
                                </div>
                            </div>





                            <div class="form-group">
                                {!! Form::label('gst_number', lang('customer.gst_number'), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('gst_number', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>



                                <div class="form-group">
                                    {!! Form::label('pan_number', lang('customer.pan_number'), array('class' => 'col-sm-4 control-label')) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('pan_number', null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>


                        </div>
                        <div class="col-md-6">






                            <div class="form-group">
                                {!! Form::label('email', lang('customer.email'), array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('email', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>



                            <div class="form-group">
                                {!! Form::label('mobile_no', lang('customer.mobile'), array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('mobile_no', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group ">
                                {!! Form::label('alternate_mobile_no', lang('customer.mobile2'), array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('alternate_mobile_no', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('landline_no', lang('customer.phone'), array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('landline_no', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group hide">
                                {!! Form::label('address', lang('customer.address1'), array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::textarea('address', null, array('class' => 'form-control', 'size' => '5x4')) !!}
                                </div>
                            </div>

                            <div class="form-group hide">
                                {!! Form::label('address2', lang('customer.address2'), array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::textarea('address2', null, array('class' => 'form-control', 'size' => '5x4')) !!}
                                </div>
                            </div>

                            <div class="form-group hide">
                                {!! Form::label('country', lang('customer.country'), array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('country', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group hide">
                                {!! Form::label('state', lang('customer.state'), array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('state', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group hide">
                                {!! Form::label('city', lang('customer.city'), array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('city', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group hide">
                                {!! Form::label('pincode', lang('customer.pincode'), array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('pincode', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('status', lang('common.active') . '&nbsp;', array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-8">
                                    <label class="checkbox col-sm-3">
                                        {!! Form::checkbox('status', '1', true) !!}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 margintop10 clearfix text-center">
                            <div class="form-group">
                                {!! Form::hidden('tab', 1) !!}
                                {!! Form::submit(lang('common.save'), array('class' => 'btn btn-danger btn-lg')) !!}
                                {!! Form::submit(lang('common.save_edit'), array('name' => 'save_edit', 'class' => 'btn btn-danger btn-lg')) !!}
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