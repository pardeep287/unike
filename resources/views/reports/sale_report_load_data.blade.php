<thead>
<tr>
    <th width="3%">{!! lang('common.sr_no') !!}</th>
    <th width="10%">{!! lang('report.order_number') !!}</th>
    <th width="10%">{!! lang('report.order_date') !!}</th>
    <th width="30%">{!! lang('customer.customer_name') !!}</th>
    <th width="30%">{!! lang('user.mr_name') !!}</th>
    <th width="10%" class="text-right">{!! lang('report.total') !!}</th>
</tr>
</thead>
<tbody>
<?php $index = 1; $oldProduct = ''; $grossAmount = $gstAmount = $netAmount = 0; ?>
@if(count($data) > 0)
@foreach($data as $productId => $detail)
    <tr>
        <td> {!! $index++ !!} </td>
        <td class="heavy"> {!! 'UNK - '.paddingLeft($detail->order_number) !!} </td>
        <td class="heavy"> {!! convertToLocal($detail->order_date, 'd.m.Y') !!} </td>
        <td class="">{!! isset($detail->customer_id)?$detail->customer_name:$detail->mr_name !!} </td>
        <td class="">{!! isset($detail->customer_id)?$detail->mr_name:'-' !!} </td>
        <?php $netAmount += $detail->gross_amount;?>
        <td  class="text-right">{!! indianFormat($detail->gross_amount) !!}</td>
        {{--<td  class="text-right">{!! numberFormat(getRoundedAmount($detail->gross_amount)) !!}</td>--}}
    </tr>
@endforeach
<tr>
    <td colspan="5" class="text-right heavy font-16">
        {!! lang('report.net_sale_amount') !!}
    </td>
    <td class="text-right heavy font-16">
        {!! indianFormat($netAmount) !!}
    </td>
</tr>
@else
    <tr>
        <td colspan="10" class="text-center"> {!! lang('messages.no_data_found') !!}  </td>
    </tr>
@endif
</tbody>