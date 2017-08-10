<thead>
<tr>
    <th  class="text-center" width="3%">{!! lang('common.sr_no') !!}</th>
    <th width="15%">{!! lang('report.description') !!}</th>
<!-- <th width="7%" class="text-center">{!! lang('report.invoice_date') !!}</th>
<th width="10%">{!! lang('customer.customer_name') !!}</th> -->
    <th width="15%">{!! lang('products.product_name') !!}</th>
    <th width="4%" class="text-center">{!! lang('sale_invoice.quantity') !!}</th>
    <th width="5%" class="text-center">{!! lang('report.cost') !!}</th>
    <th width="7%" class="text-center">{!! lang('report.total_cost') !!}</th>
    <th width="4%" class="text-center">{!! lang('report.mrp') !!}</th>
    <th width="8%" class="text-center">{!! lang('report.total_value') !!}</th>
    <th width="5%" class="text-center">{!! lang('report.profit') !!}</th>
</tr>
</thead>
<tbody>
<?php $index = 1; $oldInvNumber = ''; $totalCost = $totalMrp = 0; ?>
@if(count($data) > 0)
    @foreach($data as $productId => $detail)
        <tr>
            @if($oldInvNumber != $detail->invoice_number)
                <td class="text-center"> {!! $index++ !!} </td>
                <td class="heavy">
                    Invoice: {!! $detail->invoice_number !!} <br/>
                    Date: {!! convertToLocal($detail->invoice_date, 'd.m.Y') !!} <br/>
                    Customer: {!! $detail->customer_name  !!}
                </td>
            <!-- <td class="heavy text-center"> {!! convertToLocal($detail->invoice_date, 'd.m.Y') !!} </td>
<td>{!! $detail->customer_name  !!}</td> -->
            @else
                <td colspan="2"> &nbsp; </td>
            @endif
            <td>{!! $detail->item_code . ' - ' . $detail->product_name  . ' (' . $detail->product_code . ')'  !!}</td>
            <td class="text-center">{!! $detail->quantity  !!}</td>
            <td class="text-center">{!! $detail->price  !!}</td>
            <td class="text-center"><?php echo $cost = ($detail->quantity * $detail->price); $totalCost +=$cost;  ?></td>
            <td class="text-center">{!! $detail->manual_price !!}</td>
            <td class="text-center"><?php echo $mrp = ($detail->quantity * $detail->manual_price); $totalMrp +=$mrp; ?></td>
            <?php $profit = ($mrp - $cost) ?>
            <td class="text-center heavy @if($profit > 0) success @else danger @endif">{!! $profit !!}</td>
        </tr>
        <?php $oldInvNumber = $detail->invoice_number; ?>
    @endforeach
    <tr>
        <td colspan="5"> &nbsp; </td>
        <td class="text-center heavy font-16"><?php echo $totalCost ?></td>
        <td class="text-center"> &nbsp; </td>
        <td class="text-center heavy font-16"><?php echo $totalMrp ?></td>
        <?php $totalProfit = ($totalMrp - $totalCost) ?>
        <td class="text-center heavy font-16 @if($totalProfit > 0) success @else danger @endif">
            {!! $totalProfit !!}
        </td>
    </tr>
@else
    <tr>
        <td colspan="9" class="text-center"> {!! lang('messages.no_data_found') !!}  </td>
    </tr>
@endif
</tbody>