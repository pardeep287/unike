<thead>
<tr>
    <th width="5%">{!! lang('common.sr_no') !!}</th>
    <th width="25%">{!! lang('common.name') !!}</th>
    <th>{!! lang('report.cost') !!}</th>
    <th>{!! lang('report.in_transit') !!}</th>
    <th>{!! lang('report.stock_in_hand') !!}</th>
</tr>
</thead>
<tbody>
<?php $index = 1; $oldProduct = ''; ?>
@foreach($render as $productId => $detail)
    <?php if (isset($detail['intransit']) && count($detail['intransit']) > 0) { ?>
    @foreach($detail['intransit'] as $values)
        <?php
        $rowSpan = 0;
        if (isset($detail['intransit'])) {
            $rowSpan = count($detail['intransit']);
        }
        ?>
        @if($oldProduct != $productId)
            <tr>
                <td rowspan="{!! $rowSpan !!}">{!! $index++ !!}</td>
                <td rowspan="{!! $rowSpan !!}" class="heavy">{!! $detail['item_code'] . ' - ' . $detail['product_name'] . ' - ' . $detail['product_code'] !!}</td>
                <td rowspan="{!! $rowSpan !!}">{!! $detail['cost'] !!}</td>
                <td>{!! $values['quantity'] !!} --> {!! convertToLocal($values['date'], 'd.m.Y') !!}</td>
                <td rowspan="{!! $rowSpan !!}">{!! ($detail['stock_in'] - $detail['stock_out']) !!} {!! lang('common.pcs') !!}</td>
            </tr>
        @else
            <tr>
                <td>{!! $values['quantity'] !!} --> {!! $values['date'] !!}</td>
            </tr>
        @endif
        <?php $oldProduct = $productId; ?>
    @endforeach
    <?php } else { ?>
    <tr>
        <td> {!! $index++ !!} </td>
        <td class="heavy"> {!! $detail['item_code'] . ' - ' . $detail['product_name'] . ' - ' . $detail['product_code'] !!} </td>
        <td>{!! $detail['cost'] !!}</td>
        <td> --- </td>
        <td>{!! ($detail['stock_in'] - $detail['stock_out']) !!} {!! lang('common.pcs') !!}</td>
    </tr>
    <?php } ?>
@endforeach
</tbody>
