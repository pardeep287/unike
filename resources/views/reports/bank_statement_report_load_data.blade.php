<thead>
<tr>
    <th width="1%">{!! lang('common.sr_no') !!}</th>
    <th width="2%">{!! lang('report.date') !!}</th>
    <th width="7%">{!! lang('report.bank_account_group') !!}</th>
    <th width="10%">{!! lang('report.bank') !!}</th>
    <th>{!! lang('report.narrative') !!}</th>
    <th width="9%">{!! lang('report.debit_amount') !!}</th>
    <th width="9%">{!! lang('report.credit_amount') !!}</th>
    <th width="8%" class="text-center">{!! lang('report.total') !!}</th>
</tr>
</thead>
<tbody>
<?php $index = 1; $debitTotal = 0; $creditTotal = 0; $debitCreditDiff = 0; $totalDebitCreditDiff = 0; ?>
@if(count($data) > 0)
@foreach($data as $productId => $detail)
    <tr>
        <td> {!! $index++ !!} </td>
        <td> {!! convertToLocal($detail->date, 'd.m.Y') !!} </td>
        <td> {!! $detail->group_name !!} </td>
        <td> {!! $detail->bank_name !!} </td>
        <td> {!! $detail->narrative !!} </td>
        <td class="text-right"> {!! $detail->debit_amount !!} </td>
        <td class="text-right"> {!! $detail->credit_amount !!} </td>

        <?php
            $debitTotal = $detail->debit_amount;
            $creditTotal = $detail->credit_amount;
            $debitCreditDiff = $debitTotal - $creditTotal;
            $totalDebitCreditDiff += $debitCreditDiff;
        ?>
        <td class="text-right heavy">{!! $debitCreditDiff !!}</td>
    </tr>
@endforeach
<tr>
    <td colspan="7" class="text-right heavy font-16">
        {!! lang('report.total') !!}
    </td>
    <td class="text-right heavy font-16">
        {!! numberFormat($totalDebitCreditDiff) !!}
    </td>
</tr>
@else
    <tr>
        <td colspan="8" class="text-center"> {!! lang('messages.no_data_found') !!}  </td>
    </tr>
@endif
</tbody>