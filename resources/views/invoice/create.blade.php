@extends('layouts.admin-new')
@section('content')
<div id="page-wrapper">
    <!-- start: PAGE HEADER -->
    <div class="row topheading-row padding0 marginbottom10">
        <div class="col-lg-6 col-md-6 col-sm-9 col-xs-12">
            <h1 class="page-header margintop10">{!! lang('common.create_heading', lang('invoice.invoice')) !!}</h1>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-3 col-xs-12">
            <a class="btn btn-sm btn-default pull-right margintop10 marginbottom10 _back" href="javascript:void(0)"> <i class="fa fa-arrow-left fa-fw"></i> {!! lang('common.back') !!} </a>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    {{-- for message rendering --}}
    @include('layouts.messages')
    <div class="row margintop10">
        <div class="col-lg-12  col-md-12 col-sm-12 col-xs-12 padding0">
            {!! Form::open(array('method' => 'POST', 'route' => array('invoice.store'), 'id' => 'ajaxSave', 'class' => 'form-horizontal')) !!}
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-external-link-square"></i> &nbsp;
                        {!! lang('invoice.invoice_detail') !!}
                    </div>
                    <div class="panel-body">
                        <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('account', lang('common.party_head'), array('class' => 'col-sm-1 control-label')) !!}
                                <div class="col-sm-2">
                                    {!! Form::select('account', getAccountIn(), null, array('class' => 'form-control padding0','data-route'=>route('invoice.set-sale-type'))) !!}
                                </div>
                                <div class="col-sm-1 hidden">
                                    <a href="javascript:void(0);" class="btn btn-primary pull-left  dEdit" data-title="{!! lang('customer.add_customer') !!}" data-route="{{ route('customer.customer-modal') }}" data-setting="modal-lg"><i class="fa fa-plus"></i></a>
                                </div>

                                {!! Form::label('invoice_number', lang('invoice.invoice_number'), array('class' => 'col-sm-2 control-label')) !!}
                                <div class="col-sm-2">
                                    {!! Form::text('invoice_number', $invoiceNumber, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                                </div>

                                {!! Form::label('invoice_date', lang('invoice.invoice_date'), array('class' => 'col-sm-2 control-label')) !!}
                                <div class="col-sm-2">
                                    {!! Form::text('invoice_date', date('d-m-Y'), array('class' => 'form-control date-past', 'readonly' => 'readonly')) !!}
                                </div>

                            </div>

                            <div class="form-group hidden">
                                {!! Form::label('bank', lang('bank.bank'), array('class' => 'col-sm-1 control-label')) !!}
                                <div class="col-sm-2">
                                    {!! Form::select('bank_id', $bank, null, array('class' => 'form-control select2 padding0')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {{--{!! Form::label('ledger', lang('common.ledger'), array('class' => 'col-sm-1 control-label hidden')) !!}
                                <div class="col-sm-2 hidden">
                                    {!! Form::select('ledger', getAccountByAccountGroup(28), 34, array('class' => 'form-control')) !!}
                                </div>--}}

                                {!! Form::label('sale', lang('invoice.sale'), array('class' => 'col-sm-1 control-label')) !!}
                                <div class="col-sm-2">
                                    {!! Form::select('sale', salePurchaseType(), 1, array('class' => 'form-control')) !!}
                                </div>

                                {!! Form::label('c_cd', lang('invoice.c_cd'), array('class' => 'col-sm-2 control-label')) !!}
                                <div class="col-sm-2">
                                    {!! Form::select('cash_credit', cashOrCredit(), 2, array('class' => 'form-control')) !!}
                                </div>

                                {!! Form::label('sale_type', lang('invoice.sale_type'), array('class' => 'col-sm-2 hidden control-label')) !!}
                                <div class="col-sm-2">
                                    {!! Form::select('sale_type', $saleType, null, array('class' => 'form-control hidden')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('order_number', lang('invoice.order_number'), array('class' => 'col-sm-1 control-label')) !!}
                                <div class="col-sm-2">
                                    {!! Form::text('order_number', null, array('class' => 'form-control')) !!}
                                </div>

                                {!! Form::label('order_date', lang('invoice.order_date'), array('class' => 'col-sm-2 control-label')) !!}
                                <div class="col-sm-2">
                                    {!! Form::text('order_date', null, array('class' => 'form-control date-past', 'readonly' => 'readonly')) !!}
                                </div>

                                {!! Form::label('carriage', lang('invoice.carriage'), array('class' => 'col-sm-2 control-label')) !!}
                                <div class="col-sm-2">
                                    {!! Form::text('carriage', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('through', lang('invoice.through'), array('class' => 'col-sm-1 control-label')) !!}
                                <div class="col-sm-2">
                                    {!! Form::text('through', lang('invoice.direct'), array('class' => 'form-control')) !!}
                                </div>

                                {!! Form::label('vehicle_no', lang('invoice.vehicle_no'), array('class' => 'col-sm-2 control-label')) !!}
                                <div class="col-sm-2">
                                    {!! Form::text('vehicle_no', null, array('class' => 'form-control')) !!}
                                </div>

                                {!! Form::label('dispatch_to', lang('invoice.dispatch_to'), array('class' => 'col-sm-2 control-label hidden')) !!}
                                <div class="col-sm-2 hidden">
                                    {!! Form::text('dispatch_to', null, array('class' => 'form-control')) !!}
                                </div>

                                {!! Form::label('weight', lang('invoice.weight'), array('class' => 'col-sm-2 control-label hidden')) !!}
                                <div class="col-sm-2 hidden">
                                    {!! Form::text('weight', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group hidden">
                                {!! Form::label('private_mark', lang('invoice.private_mark'), array('class' => 'col-sm-1 control-label')) !!}
                                <div class="col-sm-2">
                                    {!! Form::text('private_mark', null, array('class' => 'form-control')) !!}
                                </div>

                                {!! Form::label('no_of_cases', lang('invoice.no_of_cases'), array('class' => 'col-sm-2 control-label')) !!}
                                <div class="col-sm-2">
                                    {!! Form::text('no_of_cases', null, array('class' => 'form-control')) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-fullbox">
                                    <tr>
                                        <th style="width: 300px;">{!! lang('invoice.product') !!}</th>
                                        <th width="10%">{!! lang('invoice.hsn_code') !!}</th>
                                        <th width="10%">{!! lang('invoice.unit') !!}</th>
                                        <th width="10%">{!! lang('invoice.gst') !!}</th>
                                        <th width="10%">{!! lang('invoice.mrp') !!}</th>
                                        <th width="10%">{!! lang('invoice.quantity') !!}</th>
                                        <th width="10%">{!! lang('invoice.price') !!}</th>
                                        <th>{!! lang('common.action') !!}</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            {!! Form::select('product', $products, null, array('class' => 'form-control width250 ajaxManual padding0', 'id' => 'product', 'data-route' => route('products.get-info'))) !!}
                                        </td>
                                        <td class="hsn_code">
                                            --
                                        </td>
                                        <td class="unit">
                                            --
                                        </td>
                                        <td class="gst">
                                            --
                                        <td>
                                            <div class="col-md-12 padding0">
                                                {!! Form::text('price', 0, array('class' => 'price_rate form-control', 'readonly' => true, 'id' => 'price_rate')) !!}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12 padding0">
                                            {!! Form::text('quantity', null, array('class' => 'quantity form-control')) !!}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-md-12 padding0">
                                                {!! Form::text('manual_price', null, array('class' => 'manual_price form-control')) !!}
                                                {!! Form::hidden('tax_id', null, array('class' => 'form-control tax_id')) !!}
                                            </div>
                                        </td>
                                        <td>
                                            {!! Form::hidden('tax_id', null, array('class' => 'form-control tax_id')) !!}
                                            {!! Form::submit(lang('common.add_more'), array('class' => 'btn btn-success btn-sm', 'name' => 'addmore', 'title' => lang('common.add_more'))) !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="text-right font-16">
                                            {!! lang('invoice.freight') !!}:
                                        </td>
                                        <td colspan="2">
                                            {!! Form::text('freight', 0, array('class' => 'form-control')) !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="text-right font-16">
                                            {!! lang('invoice.other_charges') !!}:
                                        </td>
                                        <td colspan="2">
                                            {!! Form::text('other_charges', 0, array('class' => 'form-control')) !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7" class="text-right font-16">
                                            {!! lang('invoice.round_off') !!}:
                                        </td>
                                        <td colspan="2">
                                            {!! Form::text('round_off', 0, array('class' => 'form-control')) !!}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="col-sm-12 margintop10 clearfix text-center">
                            <div class="form-group">
                                {!! Form::submit(lang('common.save'), array('class' => 'btn btn-primary btn-lg')) !!}
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
@include('invoice.js-script')
@stop