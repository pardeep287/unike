<?php  $route = ($a == 1 ) ? 'sale-invoice.add-more' : 'sale-invoice.update'; ?>
{!! Form::model($result, array('route' => array($route, $result->id), 'method' => 'PATCH', 'id' => 'ajaxSave', 'class' => 'form-horizontal')) !!}
<div class="panel-heading">
    <i class="fa fa-external-link-square"></i> &nbsp;
    {!! lang('invoice.invoice_detail') !!}
    @if(hasMenuRoute('sale-invoice.update') || isAdmin() )
    <a title="{!! lang('common.cancel') !!}" href="javascript:void(0)" class="btn btn-sm btn-primary pull-right _cancel">
        <i class="fa fa-times"></i>
        {!! lang('common.cancel') !!}
    </a>
    @endif
</div>
<div class="panel-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="form-group">
                {!! Form::label('account', lang('common.party_head'), array('class' => 'col-sm-1 control-label')) !!}
                <div class="col-sm-2">
                    {!! Form::select('account', getAccountIn(), $result->account_id, array('class' => 'form-control padding0','data-route'=>route('invoice.set-sale-type'))) !!}
                </div>

                {!! Form::label('invoice_number', lang('invoice.invoice_number'), array('class' => 'col-sm-2 control-label')) !!}
                <div class="col-sm-2">
                    {!! Form::text('invoice_number', $result->invoice_number, array('class' => 'form-control', 'readonly' => 'readonly')) !!}
                </div>

                {!! Form::label('invoice_date', lang('invoice.invoice_date'), array('class' => 'col-sm-2 control-label')) !!}
                <div class="col-sm-2">
                    {!! Form::text('invoice_date', convertToLocal($result->invoice_date, 'd-m-Y'), array('class' => 'form-control date-past', 'readonly' => 'readonly')) !!}
                    {!! Form::hidden('invoice_time', convertToLocal($result->invoice_date, 'd-m-Y H:i:s'), array('class' => 'form-control')) !!}
                </div>

            </div>

            <div class="form-group hidden">
                {!! Form::label('bank', lang('bank.bank'), array('class' => 'col-sm-1 control-label')) !!}
                <div class="col-sm-2">
                    {!! Form::select('bank_id', $bank, $result->bank_id, array('class' => 'form-control select2 padding0')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('ledger', lang('common.ledger'), array('class' => 'col-sm-1 control-label hidden')) !!}
                <div class="col-sm-2 hidden">
                    {!! Form::select('ledger', getAccountByAccountGroup(28), $result->ledger_id, array('class' => 'form-control')) !!}
                </div>

                {!! Form::label('sale_type', lang('invoice.sale'), array('class' => 'col-sm-1 control-label ')) !!}
                <div class="col-sm-2">
                    {!! Form::select('sale', salePurchaseType(), null, array('class' => 'form-control')) !!}
                </div>

                {!! Form::label('c_cd', lang('invoice.c_cd'), array('class' => 'col-sm-2 control-label')) !!}
                <div class="col-sm-2">
                    {!! Form::select('cash_credit', cashOrCredit(), null, array('class' => 'form-control')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('order_number', lang('invoice.order_number'), array('class' => 'col-sm-1 control-label')) !!}
                <div class="col-sm-2">
                    {!! Form::text('order_number', null, array('class' => 'form-control')) !!}
                </div>

                {!! Form::label('order_date', lang('invoice.order_date'), array('class' => 'col-sm-2 control-label')) !!}
                <div class="col-sm-2">
                    {!! Form::text('order_date', dateFormat('d-m-Y', $result->order_date), array('class' => 'form-control date-past', 'readonly' => 'readonly')) !!}
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
                <table class="table table-bordered" id="scrollIt">
                    <tr>
                        <th style="width: 300px;">{!! lang('invoice.product') !!}</th>
                        <th width="10%">{!! lang('invoice.hsn_code') !!}</th>
                        <th width="10%">{!! lang('invoice.unit') !!}</th>
                        <th width="10%">{!! lang('invoice.gst') !!}</th>
                        <th width="10%">{!! lang('invoice.mrp') !!}</th>
                        <th width="10%">{!! lang('invoice.quantity') !!}</th>
                        <th width="10%">{!! lang('invoice.price') !!}</th>
                        <th width="10%">{!! lang('invoice.amount') !!}</th>
                        <th>{!! lang('common.action') !!}</th>
                    </tr>
                    <tr>
                        <td>
                            {!! Form::select('product', $products, null, array('class' => 'form-control focusIt width250 ajaxManual padding0', 'id' => 'product', 'data-route' => route('products.get-info'))) !!}
                        </td>
                        <td class="hsn_code">
                            @if(old('hsn_code') != "")
                                {!! old('hsn_code') !!}
                            @else
                                --
                            @endif
                        </td>
                        <td class="unit">
                            @if(old('unit') != "")
                                {!! old('unit') !!}
                            @else
                                --
                            @endif
                        </td>
                        <td class="gst">
                            @if(old('tax_group') != "")
                                {!! old('tax_group') !!}
                            @else
                                --
                            @endif
                        </td>
                        <td>
                            <div class="col-md-12 padding0">
                                {!! Form::text('price', null, array('class' => 'price_rate form-control', 'readonly' => true, 'id' => 'price_rate')) !!}
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
                            </div>
                        </td>
                        <td>
                            <div class="col-md-12 padding0">
                                <?php $totalPrice = Session::get('totalPrice') ? Session::get('totalPrice') : 0.00; ?>
                                {!! numberFormat($totalPrice) !!}
                            </div>
                        </td>
                        <td>
                            {!! Form::hidden('tax_id', null, array('class' => 'form-control tax_id')) !!}
                            @if(Session::get('update') == 1)
                                {!! Form::hidden('prevTotalPrice', $totalPrice) !!}
                                {!! Form::submit(lang('common.update'), array('class' => 'btn btn-success', 'name' => 'update_item')) !!}
                            @else
                                {!! Form::submit(lang('common.add_more'), array('class' => 'btn btn-success btn-sm', 'name' => 'addmore', 'title' => lang('common.add_more'))) !!}
                            @endif
                        </td>
                    </tr>

                    <?php $subTotal = $netTotal = $cgstAmount = $sgstAmount = $igstAmount = 0; ?>
                    @foreach($items as $key => $item)
                            <tr @if(old('itemId') == $item->id) class="hidden" @endif>
                                <td>
                                    {!! $item->product_name !!} {!! ($item->product_code != "") ? ' (' . $item->product_code . ')' : '' !!}
                                </td>
                                <td>
                                    {!! $item->hsn_code !!}
                                </td>
                                <td>
                                    {!! $item->unit !!}
                                </td>
                                <td>
                                    {!! $item->tax_group !!}
                                </td>
                                <td>
                                    {!! $item->price !!}
                                </td>
                                <td>
                                    {!! $item->quantity !!}
                                </td>
                                {{--<td>
                                    {!! numberFormat($item->price) !!}
                                </td>
                                <td>{!! numberFormat(round($item->price * $item->quantity)) !!}</td>--}}
                                <td>
                                    {!! ($item->manual_price > 0) ? numberFormat($item->manual_price) : "--" !!}
                                </td>
                                <td>
                                    <?php
                                        $total = round($item->total_price, 2);

                                        if($result->sale == 1) {
                                            $cgstAmount += round($item->cgst_amount, 2);
                                            $sgstAmount += round($item->sgst_amount, 2);
                                        } elseif ($result->sale == 2) {
                                            $igstAmount += round($item->igst_amount, 2);
                                        }

                                        $totalPrice = $item->total_price;
                                        $subTotal += $totalPrice;
                                    ?>

                                    <div class="total"> {!! numberFormat($total) !!} </div>
                                </td>
                                <td>
                                    @if(Session::get('update') != 1)
                                        @if(hasMenuRoute('invoice.update') || isAdmin())
                                            <a class="btn btn-xs btn-primary" href="{{ route('invoice-item.edit', [$item->invoice_id, $item->id]) }}"><i class="fa fa-edit"></i></a>
                                        @endif

                                        @if(hasMenuRoute('invoice.drop') || isAdmin())
                                        <a class="btn btn-xs btn-danger __drop"
                                           href="javascript:void(0);"
                                           data-route="{!! route('invoice-item.drop', [$item->invoice_id, $item->id]) !!}"
                                           data-message="{!! lang('messages.sure_delete', lang('invoice.order_item')) !!}"
                                                >
                                            <i class="fa fa-times"></i>
                                        </a>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                    @endforeach
                    <tr>
                        <td colspan="7" class="text-right font-16">
                            {!! lang('common.sub_total') !!}:
                        </td>
                        <td colspan="3" class="font-16">
                            <span class="sub_total_final">{!! numberFormat(getRoundedAmount($subTotal)) !!}</span>
                        </td>
                    </tr>
                    @if($result->sale == 1)
                        <tr class="gst_box">
                            <td colspan="7" class="text-right font-16">
                                {!! lang('invoice.cgst_amount') !!}:
                            </td>
                            <td colspan="3" class="font-16">
                                <div class="col-md-12 padding0">
                                    {!! numberFormat(getRoundedAmount($result->cgst_total)) !!}
                                </div>
                            </td>
                        </tr>
                        <tr class="gst_box">
                            <td colspan="7" class="text-right font-16">
                                {!! lang('invoice.sgst_amount') !!}:
                            </td>
                            <td colspan="3" class="font-16">
                                <div class="col-md-12 padding0">
                                    {!! numberFormat(getRoundedAmount($result->sgst_total)) !!}
                                </div>
                            </td>
                        </tr>
                    @else
                        <tr class="gst_box">
                            <td colspan="7" class="text-right font-16">
                                {!! lang('invoice.igst_amount') !!}:
                            </td>
                            <td colspan="3" class="font-16">
                                <div class="col-md-12 padding0">
                                    {!! numberFormat(getRoundedAmount($result->igst_total)) !!}
                                </div>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="7" class="text-right font-16">
                            {!! lang('invoice.freight') !!}:
                        </td>
                        <td colspan="2">
                            {!! Form::text('freight', $result->freight, array('class' => 'form-control')) !!}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7" class="text-right font-16">
                            {!! lang('invoice.other_charges') !!}:
                        </td>
                        <td colspan="2">
                            {!! Form::text('other_charges', $result->other_charges, array('class' => 'form-control')) !!}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7" class="text-right font-16">
                            {!! lang('invoice.round_off') !!}:
                        </td>
                        <td colspan="2">
                            {!! Form::text('round_off', $result->round_off, array('class' => 'form-control')) !!}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7" class="text-right font-20">
                            {!! lang('invoice.total_sale_amount') !!}:
                        </td>
                        <td colspan="3" class="font-20">
                            <span class="total_amt_final">
                                <?php

                                $taxAmount = ($result->sale == 1) ? (getRoundedAmount($result->cgst_total) + getRoundedAmount($result->sgst_total)) : getRoundedAmount($result->igst_total);
                                $netAmount = getRoundedAmount(($subTotal + $taxAmount + $result->freight + $result->other_charges) + $result->round_off);

                                ?>
                                {!! numberFormat($netAmount) !!}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="col-sm-12 margintop10 clearfix text-center">
            <div class="form-group">
                {!! Form::hidden('gross_total', $result->gross_amount) !!}
                {!! Form::hidden('a', $a) !!}
                {!! Form::hidden('itemId', '') !!}
                {!! Form::hidden('prevQty', '') !!}
                @if(Session::get('update') != 1)
                    {!! Form::submit(lang('common.save'), array('class' => 'btn btn-primary btn-lg')) !!} &nbsp; 
                    <a title="{!! lang('common.print') !!}" class="btn btn-info btn-lg" href="{{ route('invoice.invoice-print', [$result->id]) }}">
                        <i class="fa fa-print"></i>
                        {!! lang('common.print') !!}
                    </a>
                @endif
                {!! Form::close() !!}
            </div>
        </div>

    </div>
</div>