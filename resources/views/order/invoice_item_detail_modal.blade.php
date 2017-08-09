`<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th>{!! lang('sale_invoice.product') !!}</th>
                <th width="15%">{!! lang('sale_invoice.quantity') !!}</th>
                <th width="12%">{!! lang('sale_invoice.cost') !!}</th>
                <th width="12%"> {!! lang('sale_invoice.total_cost') !!}</th>
                <th width="10%">{!! lang('sale_invoice.price') !!}</th>
                <th width="12%">{!! lang('sale_invoice.total') !!}</th>
            </tr>

            @foreach($items as $key => $item)
                @if(Session::get('productId') != $item->product_id)
                    <tr>
                        <td>
                            {!! $item->item_code . ' - ' .  $item->product_name . ' (' . $item->product_code .')' !!}
                        </td>
                        <td>
                            {!! $item->quantity !!}
                        </td>
                        <td>
                            {!! numberFormat($item->price) !!}
                        </td>
                        <td>{!! numberFormat($item->price * $item->quantity) !!}</td>
                        <td>
                            {!! ($item->manual_price > 0) ? numberFormat($item->manual_price) : "--" !!}
                        </td>
                        <td>
                            <?php $total = (($item->manual_price > 0) ? $item->manual_price * $item->quantity : $item->price * $item->quantity); ?>
                            <div class="total"> {!! numberFormat($total) !!} </div>
                        </td>

                    </tr>
                @endif
            @endforeach
            <tr>
                <td colspan="5" class="text-right font-16">
                    {!! lang('common.sub_total') !!}:
                </td>
                <td colspan="2" class="font-16">
                    <span class="sub_total_final">{!! numberFormat($invoice->gross_amount) !!}</span>
                </td>
            </tr>
            <tr class="gst_box">
                <td colspan="5" class="text-right font-16">
                    {!! lang('sale_invoice.gst') !!}:
                </td>
                <td colspan="2" class="font-16">
                    <div class="col-md-12 padding0">
                        {!! numberFormat($invoice->gst_amount) !!}
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="5" class="text-right font-20">
                    {!! lang('sale_invoice.total_sale_amount') !!}
                </td>
                <td colspan="2" class="font-20">
                      <span class="total_amt_final">
                       {!! numberFormat($invoice->net_amount) !!}
                      </span>
                </td>
            </tr>
        </table>
    </div>
</div>