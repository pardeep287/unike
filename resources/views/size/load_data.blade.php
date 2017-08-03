<thead>
<tr>
    <th width="5%" class="text-center">{!! lang('common.id') !!}</th>
    <th>
        <?php $sortNameAction = ($inputs['sort_entity'] == 'name') ? sortAction($inputs['sort_action']) : 0; ?>
        <a href="javascript:void(0)" class="sort" data-sort-action="{!! $sortNameAction !!}" data-sort-entity="name">
            {!! lang('common.name') !!}
            <i class="{!! sortIcon($sortNameAction) !!}"></i>
        </a>
    </th>

        {!! Form::hidden('sort_action', $inputs['sort_action'], ['id' => 'sort_action']) !!}
        {!! Form::hidden('sort_entity', $inputs['sort_entity'], ['id' => 'sort_entity']) !!}

    @if(hasMenuRoute('size.edit') || isAdmin())
     <th class="text-center"> {!! lang('common.status') !!} </th>
    @endif
    @if(hasMenuRoute('size.drop') || isAdmin() || hasMenuRoute('size.edit'))
     <th class="text-center">
         {!! lang('common.action') !!}
     </th>
    @endif
</tr>
</thead>
<tbody>
<?php $index = 1; ?>
@foreach($data as $detail)
    <tr id="order_{{ $detail->id }}">
        <td class="text-center">{!! pageIndex($index++, $page, $perPage) !!}</td>
        <td>
        @if(hasMenuRoute('size.edit') || isAdmin())
         <a title="{!! lang('common.edit') !!}" href="{{ route('size.edit', [$detail->id]) }}">
            {!! $detail->name !!}
         </a>
        @else
            {!! $detail->name !!}
        @endif
        </td>

        @if(hasMenuRoute('size.edit') || isAdmin())
         <td class="text-center">
            <a title="{!! lang('common.status') !!}" href="javascript:void(0);" class="toggle-status" data-message="{!! lang('messages.change_status') !!}" data-route="{!! route('size.toggle', $detail->id) !!}">
                {!! Html::image('assets/images/' . $detail->status . '.gif') !!}
            </a>
         </td>
        @endif
         <td class="text-center col-md-1">
             @if(hasMenuRoute('size.edit') || isAdmin())
                <a title="{!! lang('common.edit') !!}" class="btn btn-xs btn-default" href="{!! route('size.edit', [$detail->id]) !!}"><i class="fa fa-edit"></i></a>
            @endif
             @if(hasMenuRoute('size.drop') || isAdmin())
                <a title="{!! lang('common.delete') !!}" class="btn btn-xs btn-danger __drop" data-route="{!! route('size.drop', [$detail->id]) !!}" data-message="{!! lang('messages.sure_delete', string_manip(lang('size.size'))) !!}" href="javascript:void(0)"><i class="fa fa-times"></i></a>
             @endif
         </td>
    </tr>
@endforeach
@if (count($data) < 1)
    <tr>
        <td class="text-center" colspan="7"> {!! lang('messages.no_data_found') !!} </td>
    </tr>
@else
    <tr class="margintop10">
        <td colspan="7">
            {!! paginationControls($page, $total, $perPage) !!}
        </td>
    </tr>
@endif
</tbody>