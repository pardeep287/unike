<thead>
<tr>
    {{--<th width="3%"> <input type="checkbox" name="check-all" class="check-all" value="0" /> </th>--}}
    <th width="5%" class="text-center">{!! lang('common.id') !!}</th>
    <th  width="40%">
        <?php $sortHsnCodeAction =  ($inputs['sort_entity'] == 'hsn_code') ? sortAction($inputs['sort_action']) : 0; ?>
        <a href="javascript:void(0)" class="sort" data-sort-action="{!! $sortHsnCodeAction !!}" data-sort-entity="hsn_code">
            {!! lang('hsn.hsn') !!}
            <i class="{!! sortIcon($sortHsnCodeAction) !!}"></i>
        </a>
    </th>




    @if(isAdmin() || isSuperAdmin())
     <th width="20%" class="text-center"> {!! lang('common.status') !!} </th>
     <th width="20%" class="text-center">{!! lang('common.action') !!}</th>
    @endif 
</tr>
</thead>
<tbody>
<?php $index = 1; ?>
@foreach($data as $detail)
<tr id="hsn_{{ $detail->id }}">
    {{--<td> <input type="checkbox" name="tick[]" value="{{ $detail->id }}" class="check-one" /> </td>--}}
    <td class="text-center">{!! pageIndex($index++, $page, $perPage) !!}</td>
    <td >
    @if(isAdmin() || isSuperAdmin())
        <a title="{!! lang('common.edit') !!}" href="{!! route('hsn.edit', [$detail->id]) !!}">
            {!! $detail->hsn_code !!}
        </a>
    @else
         {!! $detail->hsn_code !!}
    @endif
    </td>




    @if( isAdmin() || isSuperAdmin())
        <td class="text-center">
            <a title="{!! lang('common.status') !!}" href="javascript:void(0);" class="toggle-status" data-message="{!! lang('messages.change_status') !!}" data-route="{!! route('hsn.toggle', $detail->id) !!}">
                {!! Html::image('assets/images/' . $detail->status . '.gif') !!}
            </a>
        </td>
        <td class="text-center col-md-1">
            <a class="btn btn-xs btn-default" title="{!! lang('common.edit') !!}" href="{{ route('hsn.edit', [$detail->id]) }}"><i class="fa fa-edit"></i></a>
        </td>
    @endif
</tr>
@endforeach
@if (count($data) < 1)
<tr>
    <td class="text-center" colspan="8"> {!! lang('messages.no_data_found') !!} </td>
</tr>
@else
<tr class="margintop10">
    <td colspan="8">
        {!! paginationControls($page, $total, $perPage) !!}
    </td>
</tr>
@endif
</tbody>