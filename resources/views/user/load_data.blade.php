<thead>
<tr>
    <th width="3%"> <input type="checkbox" name="check-all" class="check-all" value="0" /> </th>
    <th width="5%" class="text-center">{!! lang('common.id') !!}</th>
    <th>
        <?php $sortUserNameAction =  ($inputs['sort_entity'] == 'username') ? sortAction($inputs['sort_action']) : 0; ?>
        <a href="javascript:void(0)" class="sort" data-sort-action="{!! $sortUserNameAction !!}" data-sort-entity="username">
            {!! lang('user.username') !!}
            <i class="{!! sortIcon($sortUserNameAction) !!}"></i>
        </a>
    </th>

    <th>
        <?php $sortNameAction =  ($inputs['sort_entity'] == 'name') ? sortAction($inputs['sort_action']) : 0; ?>
        <a href="javascript:void(0)" class="sort" data-sort-action="{!! $sortNameAction !!}" data-sort-entity="name">
            {!! lang('user.name') !!}
            <i class="{!! sortIcon($sortNameAction) !!}"></i>
        </a>
    </th>
    @if( isSuperAdmin() )
        <th>
            {!! lang('company.company') !!}
        </th>
    @endif

    <th>
        <?php $sortRoleAction =  ($inputs['sort_entity'] == 'role') ? sortAction($inputs['sort_action']) : 0; ?>
        <a href="javascript:void(0)" class="sort" data-sort-action="{!! $sortRoleAction !!}" data-sort-entity="role">
            {!! lang('user.role') !!}
            <i class="{!! sortIcon($sortRoleAction) !!}"></i>
        </a>
    </th>

    @if(hasMenuRoute('user.edit') || isAdmin() || isSuperAdmin())
     <th width="6%" class="text-center"> {!! lang('common.status') !!} </th>
     <th class="text-center">{!! lang('common.action') !!}</th>
    @endif 
</tr>
</thead>
<tbody>
<?php $index = 1; ?>
@foreach($data as $detail)
<tr id="order_{{ $detail->id }}">
    <td> <input type="checkbox" name="tick[]" value="{{ $detail->id }}" class="check-one" /> </td>
    <td class="text-center">{!! pageIndex($index++, $page, $perPage) !!}</td>
    <td>
    @if(hasMenuRoute('user.edit') || isAdmin() || isSuperAdmin())
        <a title="{!! lang('common.edit') !!}" href="{!! route('user.edit', [$detail->id]) !!}">
            {!! $detail->username !!}
        </a>
    @else
         {!! $detail->username !!}
    @endif
    </td>



    <td>{!! $detail->name !!}</td>

    @if( isSuperAdmin() )
        <td>
            {!! $detail->company_name !!}
        </td>
    @endif
    <td>{!! $detail->role !!}</td>
    @if(hasMenuRoute('user.edit') || isAdmin() || isSuperAdmin())
        <td class="text-center">
            <a title="{!! lang('common.status') !!}" href="javascript:void(0);" class="toggle-status" data-message="{!! lang('messages.change_status') !!}" data-route="{!! route('user.toggle', $detail->id) !!}">
                {!! Html::image('assets/images/' . $detail->status . '.gif') !!}
            </a>
        </td>
        <td class="text-center col-md-1">
            <a class="btn btn-xs btn-primary" title="{!! lang('common.edit') !!}" href="{{ route('user.edit', [$detail->id]) }}"><i class="fa fa-edit"></i></a>
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