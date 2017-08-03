<thead>
<tr>
    <th width="5%" class="text-center">{!! lang('common.id') !!}</th>
    <th>
        <?php $sortNameAction =  ($inputs['sort_entity'] == 'name') ? sortAction($inputs['sort_action']) : 0; ?>
        <a href="javascript:void(0)" class="sort" data-sort-action="{!! $sortNameAction !!}" data-sort-entity="name">
            {!! lang('common.name') !!}
            <i class="{!! sortIcon($sortNameAction) !!}"></i>
        </a>
    </th>
    <th>{!! lang('tax.cgst') !!}</th>
    <th>{!! lang('tax.sgst') !!}</th>
    <th>{!! lang('tax.igst') !!}</th>
    {{--@if(hasMenuRoute('tax.edit'))--}}
     <th class="text-center"> {!! lang('common.status') !!} </th>
     <th class="text-center">{!! lang('common.action') !!}</th>
    {{--@endif--}}
</tr>
</thead>
<tbody>
<?php $index = 1; ?>
@foreach($data as $detail)
    <tr id="order_{{ $detail->id }}">
        <td class="text-center">{!! pageIndex($index++, $page, $perPage) !!}</td>
        <td>
            @if(isAdmin())
                <a title="{!! lang('common.edit') !!}" href="{{ route('tax.edit', [$detail->id]) }}">
                    {!! $detail->name !!}
                </a>
            @else
                {!! $detail->name !!}
            @endif
        </td>
        {{--<td>
        @if(hasMenuRoute('tax.edit'))
         <a href="{{ route('tax.edit', [$detail->id]) }}">
           {!! $detail->name !!}
         </a>
        @else
           {!! $detail->name !!}
        @endif
        </td>--}}

        <td>{!! (!empty($detail->cgst_rate))? $detail->cgst_rate.'%' : '' !!}</td>
        <td>{!! (!empty($detail->sgst_rate))? $detail->sgst_rate.'%' : '' !!}</td>
        <td>{!! (!empty($detail->igst_rate))? $detail->igst_rate.'%' : '' !!}</td>
       {{-- @if(hasMenuRoute('tax.edit'))--}}
         <td class="text-center">
            <a href="javascript:void(0);" class="toggle-status" data-message="{!! lang('messages.change_status') !!}" data-route="{!! route('tax.toggle', $detail->id) !!}">
                {!! Html::image('assets/images/' . $detail->status . '.gif') !!}
            </a>
         </td>
         <td class="text-center col-md-1">
            <a class="btn btn-xs btn-default" href="{{ route('tax.edit', [$detail->id]) }}"><i class="fa fa-edit"></i></a>
         </td>
        {{--@endif--}}
    </tr>
@endforeach
@if (count($data) < 1)
    <tr>
        <td class="text-center" colspan="6"> {!! lang('messages.no_data_found') !!} </td>
    </tr>
@else
    <tr class="margintop10">
        <td colspan="7">
            {!! paginationControls($page, $total, $perPage) !!}
        </td>
    </tr>
@endif
</tbody>