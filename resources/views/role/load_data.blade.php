<thead>
<tr>
    <th width="5%" class="text-center">{!! lang('common.id') !!}</th>
    <th>{!! lang('common.name') !!}</th>
    <th>{!! lang('common.code') !!}</th>

   {{-- @if( isSuperAdmin() )
        <th>{!! lang('company.company') !!}</th>
    @endif--}}

    @if(hasMenuRoute('role.edit') || isAdmin())
     <th class="text-center"> {!! lang('common.status') !!} </th>
     <th class="text-center">{!! lang('common.action') !!}</th>
    @endif
</tr>
</thead>
<tbody>
<?php $index = 1; ?>
@foreach($data as $detail)
    <tr id="order_{{ $detail->id }}">
        <td class="text-center">{!! pageIndex($index++, $page, $perPage) !!}</td>
        <td>
            @if(!$detail->isdefault && hasMenuRoute('role.edit') || isAdmin())
            <a title="{!! lang('common.edit') !!}" href="{!! route('role.edit', [$detail->id]) !!}">
                {!! $detail->name !!}
            </a>
            @else
                {!! $detail->name !!}
            @endif
        </td>
        <td>{!! $detail->code !!}</td>
        @if(hasMenuRoute('role.edit') || isAdmin())
            <td class="text-center">
                @if(!$detail->isdefault)
                    <a title="{!! lang('common.status') !!}" href="javascript:void(0);" class="toggle-status" data-message="{!! lang('messages.change_status') !!}" data-route="{!! route('role.toggle', $detail->id) !!}">
                        {!! Html::image('assets/images/' . $detail->status . '.gif') !!}
                    </a>
                @else
                    Default
                @endif
            </td>
            {{--@if( isSuperAdmin() )
                <td>
                    {!! $detail->company_name !!}
                </td>
            @endif--}}

            <td class="text-center col-md-1">
                @if(!$detail->isdefault)
                    <a title="{!! lang('common.edit') !!}" class="btn btn-xs btn-primary" href="{{ route('role.edit', [$detail->id]) }}"><i class="fa fa-edit"></i></a>
                @else
                    Default
                @endif
            </td>
        @endif
    </tr>
@endforeach
@if (count($data) < 1)
    <tr>
        <td class="text-center" colspan="6"> {!! lang('messages.no_data_found') !!} </td>
    </tr>
@else
    <tr class="margintop10">
        <td colspan="6">
            {!! paginationControls($page, $total, $perPage) !!}
        </td>
    </tr>
@endif
</tbody>