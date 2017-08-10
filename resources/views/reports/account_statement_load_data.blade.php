<thead>
<tr>
    <th width="3%">{!! lang('common.sr_no') !!}</th>
    <th>{!! lang('common.date') !!}</th>
    <th width="12%">{!! lang('report.voucher_bill_no') !!}</th>
    <th width="25%">{!! lang('common.account') !!}</th>
    <th class="text-right">{!! lang('report.dr_amount') !!}</th>
    <th class="text-right">{!! lang('report.cr_amount') !!}</th>
    <th class="text-right">{!! lang('report.balance') !!}</th>
    <th class="text-center">{!! lang('report.dr_cr') !!}</th>
</tr>
</thead>
<tbody>
<?php $index = 1; $drTotal = $crTotal = $oldBalance = $drBalance = $crBalance = 0 ; $account = isset($inputs['account']) ? $inputs['account'] : ''; ?>
@foreach($data as $detail)
    <tr>
        <td> {!! $index++ !!} </td>
        <td> {!! dateFormat('d.m.Y', $detail->transaction_date) !!} </td>
        <td>{!! geVoucherOrBillNo($detail) !!}</td>
        <td class="heavy"> {!! ($detail->account_cr_id == $account) ? $detail->dr_account : $detail->cr_account !!} </td>
        <td class="text-right"> @if($detail->account_dr_id == $account) {!! numberFormat(getRoundedAmount($detail->amount)) !!} @endif </td>
        <td class="text-right"> @if($detail->account_cr_id == $account) {!! numberFormat(getRoundedAmount($detail->amount)) !!} @endif </td>
        <?php
            if ($detail->account_cr_id == $account) {
                $crBalance += getRoundedAmount($detail->amount);
            } else {
                $drBalance += getRoundedAmount($detail->amount);
            }

            $balance = ($crBalance - $drBalance);
        ?>
        <td class="text-right"> {!! (abs($balance) > 0) ? numberFormat(getRoundedAmount(abs($balance))) : 'NIL' !!}</td>
        <td class="text-center"> @if($balance > 0) CR @elseif($balance < 0) DR  @endif </td>
    </tr>
@endforeach
@if(count($data) == 0)
    <tr>
        <td colspan="9" class="text-center"> {!! lang('messages.no_data_found') !!} </td>
    </tr>
@else
    <tr>
        <td colspan="4" class="text-center"> </td>
        <td class="text-right font-20"> {!! ($drBalance > 0) ? numberFormat(getRoundedAmount(abs($drBalance))) : '' !!} </td>
        <td class="text-right font-20"> {!! ($crBalance > 0) ? numberFormat(getRoundedAmount(abs($crBalance))) : '' !!} </td>
        <td class="text-right font-20"> {!! (abs($balance) > 0) ? numberFormat(getRoundedAmount(abs($balance))) : 'NIL' !!} </td>
        <td class="text-center font-20"> @if($balance > 0) CR @elseif($balance < 0) DR  @endif  </td>
    </tr>
@endif
</tbody>
