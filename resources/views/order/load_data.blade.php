<thead>
<tr>
    <th width="5%" class="text-center">{!! lang('common.id') !!}</th>
    <th width="18%">
        <?php $sortOrderNumberAction =  ($inputs['sort_entity'] == 'order_number') ? sortAction($inputs['sort_action']) : 0; ?>
        <a href="javascript:void(0)" class="sort" data-sort-action="{!! $sortOrderNumberAction !!}" data-sort-entity="order_number">
            {!! lang('order.ord_number') !!}
            <i class="{!! sortIcon($sortOrderNumberAction) !!}"></i>
        </a>
    </th>
    <th>
        <?php $sortInvoiceDateAction =  ($inputs['sort_entity'] == 'order_date') ? sortAction($inputs['sort_action']) : 0; ?>
        <a href="javascript:void(0)" class="sort" data-sort-action="{!! $sortInvoiceDateAction !!}" data-sort-entity="order_date">
            {!! lang('order.ord_date') !!}
            <i class="{!! sortIcon($sortInvoiceDateAction) !!}"></i>
        </a>
    </th>
    <th>
        <?php $sortCustomerNameAction =  ($inputs['sort_entity'] == 'customer_name') ? sortAction($inputs['sort_action']) : 0; ?>
        <a href="javascript:void(0)" class="sort" data-sort-action="{!! $sortCustomerNameAction !!}" data-sort-entity="customer_name">
            {!! lang('customer.customer_name') !!}
            <i class="{!! sortIcon($sortCustomerNameAction) !!}"></i>
        </a>
    </th>
    <th >{!! lang('order.gross_amount') !!}</th>
    {{--<th class="text-center" width="10%">{!! lang('common.email_send') !!}</th>--}}
    @if(hasMenuRoute('order.edit') || isAdmin() || hasMenuRoute('order.order-adjustment'))
        <th width="8%" class="text-center">{!! lang('common.action') !!}</th>
    @endif
</tr>
</thead>
<tbody>
<?php $index = 1; ?>
@foreach($data as $detail)
<tr id="order_{{ $detail->id }}">
    <td class="text-center">{!! pageIndex($index++, $page, $perPage) !!}</td>
    <td>
    @if(hasMenuRoute('order.edit') || isAdmin())
     <a title="{!! lang('common.view') !!}" href="{!! route('order.edit', [$detail->id]) !!}">
      {!! $detail->order_number !!}
     </a>
    @else
     {!! $detail->order_number !!}
    @endif
    </td>
    <td>{!! convertToLocal($detail->order_date, 'd.m.Y') !!}</td>
    <td>{!! $detail->customer_name !!} </td>
    <td >
        <?php
            //$taxAmount = ($detail->sale == 1) ? (getRoundedAmount($detail->cgst_total) + getRoundedAmount($detail->sgst_total)) : getRoundedAmount($detail->igst_total);
            //$netAmount = getRoundedAmount(($detail->gross_amount));
            $netAmount = $detail->gross_amount;
        ?>
        {!!  numberFormat($netAmount) !!}
    </td>
    {{--<td class="text-center">
        @if($detail->is_email_sent == 1)
            <i class="fa fa-check text-success"></i>
        @else
            <i class="icon-ban-circle text-danger"></i>
        @endif
    </td>--}}
    <td class="text-center">
      @if(hasMenuRoute('order.edit') || isAdmin())
        <a data-title="{!! lang('common.view_order_item_detail') !!}" class="btn btn-xs dEdit btn-success hidden" href="javascript:void(0)" data-setting="modal-lg" data-route="{{ route('order.item-detail', [$detail->id]) }}">
            <i class="fa fa-eye"></i>
        </a>

        <a title="{!! lang('common.print') !!}" class="btn btn-xs btn-default" href="{{ route('order.order-print', [$detail->id]) }}">
            <i class="fa fa-print"></i>
        </a>

        <a title="{!! lang('common.edit') !!}" class="btn btn-xs btn-primary" href="{{ route('order.edit', [$detail->id]) }}">
            <i class="fa fa-edit"></i>
        </a>

        <a title="{!! lang('common.delete') !!}" class="btn btn-xs btn-danger __drop hidden" data-route="{!! route('order.drop', [$detail->id]) !!}" data-message="{!! lang('messages.sure_delete', string_manip(lang('order.order'))) !!}" href="javascript:void(0)">
            <i class="fa fa-times"></i>
        </a>

      @endif
    </td>
</tr>
@endforeach
@if (count($data) < 1)
<tr>
    <td class="text-center" colspan="9"> {!! lang('messages.no_data_found') !!} </td>
</tr>
@else
<tr class="margintop10">
    <td colspan="9">
        {!! paginationControls($page, $total, $perPage) !!}
    </td>
</tr>
@endif
</tbody>