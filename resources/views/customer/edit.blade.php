@extends('layouts.admin')
@section('content')
<div id="page-wrapper">
    <!-- start: PAGE HEADER -->
    <div class="row">
        <div class="col-lg-12">
            <a class="btn btn-sm btn-default pull-right margintop10 _back" href="javascript:void(0)"> <i class="fa fa-arrow-left fa-fw"></i> {!! lang('common.back') !!} </a>
            <h1 class="page-header margintop10"> {!! lang('common.edit_heading', lang('customer.customer')) !!} #{{ $result->customer_name }}</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- end: PAGE HEADER -->
    <!-- start: PAGE CONTENT -->
    
    {{-- for message rendering --}}
    @include('layouts.messages')
    <div class="row">
        <div class="col-md-12 padding0">
        {!! Form::model($result, array('route' => array('customer.update', $result->id), 'method' => 'PATCH', 'id' => 'customer-form', 'class' => 'form-horizontal')) !!}
         <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-external-link-square"></i> &nbsp;
                    {!! lang('customer.customer_detail') !!}
                </div>
                <div class="panel-body">

                    <div>


                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="@if($tab == 1) active @endif"><a href="#customer" aria-controls="product" role="tab" data-toggle="tab">{!! lang('customer.customer_detail') !!}</a></li>
                            <li role="presentation" class=" @if($tab == 2) active @endif"><a href="#customer_address" aria-controls="product_tax" role="tab" data-toggle="tab">{!! lang('customer.Customer_address') !!}</a></li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane @if($tab == 1) active @endif" id="customer">
                                <div class="col-md-6 margintop20">
                                    <div class="form-group">
                                        {!! Form::label('customer_name', lang('customer.customer_name'), array('class' => 'col-sm-4 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('customer_name', null, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('customer_code', lang('customer.customer_code'), array('class' => 'col-sm-4 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('customer_code', null, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('username', lang('customer.username'), array('class' => 'col-sm-4 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('username', (is_object($user)) ? $user->username : null, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('password', lang('customer.password'), array('class' => 'col-sm-4 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::password('password', array('class' => 'form-control')) !!}
                                           {{-- <input name="password" type="password" class="form-control" value="(empty($result->password))? null : $result->password" id="password"/>--}}
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

                                <div class="col-md-6 margintop20">

                                    <div class="form-group">
                                        {!! Form::label('email', lang('customer.email'), array('class' => 'col-sm-3 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('email', (empty($result->email))? null : $result->email, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('mobile_no', lang('customer.mobile'), array('class' => 'col-sm-3 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('mobile_no', (empty($result->mobile_no))? null : $result->mobile_no, array('class' => 'form-control')) !!}
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
                                    <div class="form-group">
                                        {!! Form::label('status', lang('common.active') . '&nbsp;', array('class' => 'col-sm-3 control-label')) !!}
                                        <div class="col-sm-8">
                                            <label class="checkbox col-sm-3">
                                                {!! Form::checkbox('status', '1', true) !!}
                                            </label>
                                        </div>
                                    </div>
                            

                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane @if($tab == 2) active @endif" id="customer_address">

                                <div class="col-md-6 margintop20">

                                    <div class="form-group">
                                        {!! Form::label('address', lang('customer.address1'), array('class' => 'col-sm-3 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::textarea('address', null, array('class' => 'form-control', 'size' => '5x4')) !!}
                                        </div>
                                    </div>

                                    <div class="form-group hide">
                                        {!! Form::label('alternate_address', lang('customer.address2'), array('class' => 'col-sm-3 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::textarea('alternate_address', null, array('class' => 'form-control', 'size' => '5x4')) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('country', lang('customer.country'), array('class' => 'col-sm-3 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('country', null, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('state', lang('customer.state'), array('class' => 'col-sm-3 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('state', null, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('city', lang('customer.city'), array('class' => 'col-sm-3 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('city', null, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('pincode', lang('customer.pincode'), array('class' => 'col-sm-3 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('pincode', null, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 margintop20 hide">

                                    <div class="form-group">
                                        {!! Form::label('d_address', lang('customer.delivery_address'), array('class' => 'col-sm-3 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::textarea('d_address', null, array('class' => 'form-control', 'size' => '5x4')) !!}
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        {!! Form::label('d_country', lang('customer.delivery_country'), array('class' => 'col-sm-3 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('d_country', null, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('d_state', lang('customer.delivery_state'), array('class' => 'col-sm-3 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('d_state', null, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('d_city', lang('customer.delivery_city'), array('class' => 'col-sm-3 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('d_city', null, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('d_pincode', lang('customer.delivery_pincode'), array('class' => 'col-sm-3 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('d_pincode', null, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                </div>
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
            <!-- end: TEXT FIELDS PANEL -->
        </div>
        {!! Form::close() !!}
    </div>
</div>
</div>
<!-- /#page-wrapper -->
@stop
