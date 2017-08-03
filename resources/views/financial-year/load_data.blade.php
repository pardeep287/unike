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
    <th>
        <?php $sortFromDateAction =  ($inputs['sort_entity'] == 'from_date') ? sortAction($inputs['sort_action']) : 0; ?>
        <a href="javascript:void(0)" class="sort" data-sort-action="{!! $sortFromDateAction !!}" data-sort-entity="from_date">
            {!! lang('common.from_date') !!}
            <i class="{!! sortIcon($sortFromDateAction) !!}"></i>
        </a>
    </th>
    <th>
        <?php $sortToDateAction =  ($inputs['sort_entity'] == 'to_date') ? sortAction($inputs['sort_action']) : 0; ?>
        <a href="javascript:void(0)" class="sort" data-sort-action="{!! $sortToDateAction !!}" data-sort-entity="to_date">
            {!! lang('common.to_date') !!}
            <i class="{!! sortIcon($sortToDateAction) !!}"></i>
        </a>
    </th>
    @if( isAdmin() )
        <th class="text-center"> {!! lang('common.status') !!} </th>
    @endif
    @if( isAdmin() )
        <th class="text-center"> {!! lang('common.action') !!} </th>
    @endif
</tr>
</thead>
<tbody>
@foreach($data as $detail)
    <tr id="order_{{ $detail->id }}">
        <td class="text-center">{{ $detail->id }}</td>
        <td>
            @if(isAdmin())
                <a title="{!! lang('common.edit') !!}" href="{{ route('financial-year.edit', [$detail->id]) }}">
                    {!! $detail->name !!}
                </a>
            @else
                {!! $detail->name !!}
            @endif
        </td>
        <td>{!! dateFormat('d-m-Y',$detail->from_date) !!}</td>
        <td>{!! dateFormat('d-m-Y',$detail->to_date) !!}</td>
        @if(isAdmin())
            <td class="text-center">
                <a title="{!! lang('common.status') !!}" href="javascript:void(0);" class="toggle-status" data-realod="1"  data-message="{!! lang('messages.change_status') !!}" data-route="{!! route('financial-year.toggle', $detail->id) !!}">
                    {!! Html::image('assets/images/' . $detail->status . '.gif') !!}
                </a>
            </td>
        @endif
            <td class="text-center col-md-1">
                {{--@if(hasMenuRoute('financial-year.edit') || isAdmin())--}}
                @if(isAdmin())
                    <a title="{!! lang('common.edit') !!}" class="btn btn-xs btn-default" href="{{ route('financial-year.edit', [$detail->id]) }}"><i class="fa fa-edit"></i></a>
                @endif
                @if( isAdmin())
                    <a title="{!! lang('common.delete') !!}" class="btn btn-xs btn-danger __drop" data-route="{!! route('financial-year.drop', [$detail->id]) !!}" data-message="{!! lang('messages.sure_delete', string_manip(lang('financial_year.financial_year'))) !!}" href="javascript:void(0)"><i class="fa fa-times"></i></a>
                @endif
            </td>
    </tr>
@endforeach
@if (count($data) < 1)
    <tr>
        <td class="text-center" colspan="7"> {!! lang('messages.no_data_found') !!} </td>
    </tr>
@else
    <tr>
        <td colspan="7">
            {!! paginationControls($page, $total, $perPage) !!}
        </td>
    </tr>
@endif
</tbody>

