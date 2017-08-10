<thead>
<tr>
    <th width="5%">{!! lang('common.sr_no') !!}</th>
    <th width="50%">{!! lang('common.account') !!}</th>
    <th class="text-right">{!! lang('report.dr_amount') !!}</th>
    <th class="text-right">{!! lang('report.cr_amount') !!}</th>
    <th class="text-right">{!! lang('report.balance') !!}</th>
    <th class="text-center">{!! lang('report.dr_cr') !!}</th>
</tr>
</thead>
<tbody>
<?php $index = 1; $drTotal = $crTotal = $oldBalance = $drBalance = $crBalance = 0 ; $account = isset($inputs['account_group']) ? $inputs['account_group'] : ''; ?>
@foreach($result as $detail)
    <tr>
        <td> {!! $index++ !!} </td>
        <td class="heavy"> {!! $detail['account'] !!} </td>
        <?php
            $drBalance = $detail['amount_dr'];
            $crBalance = $detail['amount_cr'];
            $drTotal += $drBalance;
            $crTotal += $crBalance;
            $totalBalance = ($crTotal - $drTotal);
            $balance = ($crBalance - $drBalance);
        ?>
        <td class="text-right"> {!! numberFormat(getRoundedAmount($drBalance)) !!} </td>
        <td class="text-right"> {!! numberFormat(getRoundedAmount($crBalance)) !!} </td>
        <td class="text-right"> {!! (abs($balance) > 0) ? numberFormat(getRoundedAmount(abs($balance))) : 'NIL' !!}</td>
        <td class="text-center"> @if($balance > 0) CR @elseif($balance < 0) DR  @endif </td>
    </tr>
@endforeach
@if(count($result) == 0)
    <tr>
        <td colspan="7" class="text-center"> {!! lang('messages.no_data_found') !!} </td>
    </tr>
@else
    <tr>
        <td class="text-center"> </td>
        <td class="text-center"> </td>
        <td class="text-right font-20"> {!! ($drTotal > 0) ? numberFormat(getRoundedAmount(abs($drTotal))) : '' !!} </td>
        <td class="text-right font-20"> {!! ($crTotal > 0) ? numberFormat(getRoundedAmount(abs($crTotal))) : '' !!} </td>
        <td class="text-right font-20"> {!! (abs($totalBalance) > 0) ? numberFormat(getRoundedAmount(abs($totalBalance))) : 'NIL' !!} </td>
        <td class="text-center font-20"> @if($totalBalance > 0) CR @elseif($totalBalance < 0) DR  @endif  </td>
    </tr>
@endif
</tbody>
