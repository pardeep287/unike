<thead>
<tr>
    <th width="5%" class="text-center">{!! lang('common.id') !!}</th>
    <th>{!! lang('company.company_name') !!}</th>
    <th>{!! lang('company.contact_person') !!}</th>
    <th>{!! lang('company.gst_number') !!}</th>
    <th>{!! lang('company.email1') !!}</th>
    <th>{!! lang('company.mobile1') !!}</th>
    @if(hasMenuRoute('company.edit') || isSuperAdmin())
     <th width="6%" class="text-center"> {!! lang('common.status') !!} </th>
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
    @if(hasMenuRoute('company.edit') || isSuperAdmin())
        <a href="{!! route('company.edit', [$detail->id]) !!}">
            {!! $detail->company_name !!}
        </a>
    @else
            {!! $detail->company_name !!}
    @endif
    </td>
    <td>{!! $detail->contact_person !!}</td>
    <td>{!! $detail->gst_number !!}</td>
    <td>{!! $detail->email1 !!}</td>
    <td>{!! $detail->mobile1 !!}</td>
    @if(hasMenuRoute('company.edit') || isSuperAdmin())
        <td class="text-center">
            <a href="javascript:void(0);" class="toggle-status" data-message="{!! lang('messages.change_status') !!}" data-route="{!! route('company.toggle', $detail->id) !!}">
                {!! Html::image('assets/images/' . $detail->status . '.gif') !!}
            </a>
        </td>
        <td class="text-center col-md-1">
            <a class="btn btn-xs btn-primary" href="{{ route('company.edit', [$detail->id]) }}"><i class="fa fa-edit"></i></a>
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