N<div class="invoice-box" style="clear: both;">


    <table style="margin: 0 auto; width:700px;" cellspacing="0" cellpadding="0">
        <tbody>

        <tr class="header-info">
            <td colspan="4">
                <table style="border-width: 1px 1px 0px; border-style: solid solid none; -moz-border-top-colors: none; -moz-border-right-colors: none; -moz-border-bottom-colors: none; -moz-border-left-colors: none; border-image: none; border-color: rgb(0, 0, 0) rgb(0, 0, 0) -moz-use-text-color;" width="100%" cellpadding="5">
                    <tbody>
                    <tr valign="top">

                        <td style="width: 100%; text-align: center; font-size: 1.1em;padding-bottom: 0;">
                            <h3 style="margin-bottom: 0px; margin-top: 0px;margin-bottom:0;  font-family: arial;  font-size: 3.0em; font-weight: 900;"> {!! $company->company_name !!} </h3>
                            <p style="text-align: center; font-size: 1.2em;margin: 0;line-height:25px;font-weight:bold; padding-top:10px;">
                             @if($company->permanent_address != '')   {!! $company->permanent_address !!} @endif
                             @if($company->city != '')  , {!! $company->city !!} @endif
                             @if($company->state != '')   {!! $company->state !!} @endif
                                <br>
                            @if($company->mobile1 != '')   {!! lang('sales_order.mobile') !!} {!! $company->mobile1 !!} @endif
                            @if($company->mobile2 != '')    , {!! lang('sales_order.indian_contact_no') !!} {!! $company->mobile2 !!} @endif
                                <br>
                            @if($company->website != '')    {!! $company->website !!} @endif
                            </p>

                        </td>

                    </tr>
                    <tr>
                        <td style="font-weight: 600;padding-right: 0;text-align:right;width: 100%;" align="right">
                            <p style="text-align: right;margin:0;padding-right: 5px"> {!! lang('sale_invoice.abn') !!} {!! $company->abn_number !!} </p>
                        </td>
                    </tr>
                    </tbody></table>
            </td>
        </tr>

        <tr top="" valign="top">
            <td colspan="2" style="border-style: solid; border-color: rgb(0, 0, 0); border-width: 1px 0px 0px 1px;" width="53%">
                <table valign="" top="" width="100%" cellspacing="0" cellpadding="5" border="0">
                    <tbody>
                    <tr>
                        <td style="font-size: 1.1em; text-decoration: underline; font-weight: bold; padding-bottom: 0px;">  {!! lang('sale_invoice.consignee') !!} </td>
                    </tr>
                    <tr>
                        <td style="font-size: 1.3em;" width="100%" valign="top">
                            {!! $party->salutation !!}
                            {!! $party->account_name !!}<br>
                            {!! $party->address1 !!}<br>
                            @if($party->city != "")
                                {!! $party->city !!}
                            @endif

                            @if($party->pincode != "")
                                {!! $party->state_name !!} {!! ' - ' . $party->pincode !!} <br>
                            @endif

                            <br> MOBILE: {!! $party->mobile1 !!} | Phone: {!! $party->phone !!}
                            <br> GST NO: {!! $party->gst_number !!}
                            @if($party->pan_number != "")
                                <br> PAN NO: {!! $party->pan_number !!}
                            @endif
                            @if($party->ecc_number != "")
                                <br>ECC NO: {!! $party->ecc_number !!}
                            @endif
                        </td>
                    </tr>
                    </tbody></table>
            </td>
            <td colspan="2" style="border-style: solid; border-width: 1px 1px 0px; border-color: rgb(0, 0, 0);vertical-align:middle" width="43%">
                <table style="font-size: 1.0em;padding-top:10px" width="100%" cellspacing="2" cellpadding="3" border="0">
                    <tbody>
                    <tr>
                        <td>
                            <div class="logo" style="text-align: center;">
                                ETT
                            </div>
                        </td>
                    </tr>
                    </tbody></table>
            </td>
        </tr>
        <tr class="invoive-no">
            <td colspan="4">
                <table style="" width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                    <tr>
                        <td style="border-style: solid; border-width: 1px 0px 0px; border-color: rgb(0, 0, 0);" width="55.2%">
                            <table style="width: 100%" cellspacing="0" cellpadding="5" border="0">
                                <tbody>
                                <tr>
                                    <td style="font-weight: 600; border-style: solid; border-width: 0px 0px 0px 1px; text-align: left; font-size: 1.2em; border-color: rgb(0, 0, 0);">
                                        {!! lang('sale_invoice.date') !!}
                                    </td>
                                    <td style="font-weight: 600; border-style: solid; border-width: 0px; text-align: center; font-size: 1.2em; border-color: rgb(0, 0, 0);">
                                        {!! convertToLocal($result->invoice_date, 'd.m.Y') !!}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                        <td style="border-style: solid; border-width: 1px 0px 0px 1px; border-color: rgb(0, 0, 0);" width="44.8%">
                            <table width="100%" cellspacing="0" cellpadding="5" border="0">
                                <tbody>
                                <tr>
                                    <td style="font-weight: 600; width:200px; border-style: solid; border-width: 0px; font-size: 1.2em; text-align: left; border-color: rgb(0, 0, 0);">
                                        {!! lang('sale_invoice.invoice_no') !!}
                                    </td>
                                    <td style="font-weight: 600; border-style: solid; border-width: 0px 1px 0px 0px; font-size: 1.2em; text-align: center; border-color: rgb(0, 0, 0);">
                                        {!! $result->invoice_number !!}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>

                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <table style="border-collapse: collapse; border-color: rgb(0, 0, 0);" width="100%" cellspacing="0" cellpadding="3" border="1">
                    <thead>
                    <tr style="border-top:0;border-bottom:0;">
                        <td style="text-align: center; border-style: solid; font-weight: bold; text-transform: uppercase; font-size: 1.1em; border-width: 1px 0px 1px 1px; border-color: rgb(0, 0, 0);" width="5%">
                            {!! lang('sale_invoice.s_no') !!}
                        </td>
                        <td style="text-align: left; border-width: 1px 0px 1px 1px; border-style: solid; font-weight: 600; text-transform: uppercase; font-size: 1.1em; border-color: rgb(0, 0, 0);" width="35%">
                            {!! lang('sale_invoice.description') !!}
                        </td>
                        <td style="text-align: center; border-width: 1px 0px 1px 1px; border-style: solid; font-weight: 600; text-transform: uppercase; font-size: 1.1em; border-color: rgb(0, 0, 0);" width="10%"> {!! lang('sale_invoice.unit') !!} </td>
                        <td style="text-align: center; border-width: 1px 0px 1px 1px; border-style: solid; font-weight: 600; text-transform: uppercase; font-size: 1.1em; border-color: rgb(0, 0, 0);" width="10%"> {!! lang('sale_invoice.qty') !!} </td>
                        <td style="text-align: center; border-width: 1px 0px 1px 1px; border-style: solid; font-weight: 600; font-size: 1.1em; text-transform: uppercase; border-color: rgb(0, 0, 0);" width="10%"> {!! lang('sale_invoice.rate') !!} </td>
                        <td style="text-align: center; border-width: 1px; border-style: solid; font-weight: 600; text-transform: uppercase; font-size: 1.1em; border-color: rgb(0, 0, 0);" width="15%"> {!! lang('sale_invoice.amount') !!} </td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i = 1; $oldProduct = ''; $totalRecords = count($orderItems);?>
                    @foreach($orderItems as $detail)
                    <tr style="border-top:0;border-bottom:0;">
                        <td style="border-top-style: none; border-top-color: -moz-use-text-color; border-bottom-style: none; border-bottom-color: -moz-use-text-color; text-align: center; padding-top: 5px; padding-bottom: 5px;">  {!! $i++ !!}.</td>
                        <td style="text-align:left; border-top:0;border-bottom:0;padding-top: 5px; padding-bottom: 5px;">
                            {!! $detail->product_name !!}
                        </td>
                        <td style="text-align:center; border-top:0;border-bottom:0;padding-top: 5px; padding-bottom: 5px;">
                            {!! lang('sale_invoice.pcs') !!}
                        </td>
                        <td style="text-align:center; border-top:0;border-bottom:0;padding-top: 5px; padding-bottom: 5px;">
                            {!! $detail->quantity !!}
                        </td>
                        <td style="text-align:center;border-top:0;border-bottom:0;padding-top: 5px; padding-bottom: 5px;">
                            <?php $price = ($detail->manual_price > 0) ? $detail->manual_price : $detail->price; ?>
                            {!! numberFormat($price) !!}
                        </td>
                        <td style="text-align:center;border-top:0;border-bottom:0;padding-top: 5px; padding-bottom: 5px;">
                            <?php $total = ($detail->manual_price > 0) ? $detail->manual_price * $detail->quantity : $detail->price * $detail->quantity; ?>
                            {!! numberFormat($total) !!}
                        </td>
                    </tr>

                    @endforeach
                    <tr style="border-top:0;border-bottom:0;">
                        <td style="border-top:0;border-bottom:0;text-align: center">
                            <br><br>
                            <?php
                                $loop = (15 - $totalRecords);
                                for ($j=1; $j <= $loop; $j++) {
                                    echo "<br>";
                                }
                            ?>
                        </td>
                        <td style="border-top: 0px none; border-bottom: 0px none; text-align: right; font-weight: 600; font-size: 15px;"> </td>
                        <td style="border-top:0;border-bottom:0;text-align: right"> </td>
                        <td style="border-top:0;border-bottom:0;text-align: right"> </td>
                        <td style="border-top:0;border-bottom:0;text-align: right"> </td>
                        <td style="border-top:0;border-bottom:0;text-align: right"> </td>
                        <td style="border-top:0;border-bottom:0;text-align: right;font-size: 15px;"> </td>
                    </tr>
                    </tbody></table>
            </td>
        </tr>
        <tr class="total-amount">
            <td colspan="4">
                <table style="border-collapse: collapse; border-top: 0px none rgb(0, 0, 0);border-bottom: 0px none rgb(0, 0, 0);  border-right-color: rgb(0, 0, 0); border-left-color: rgb(0, 0, 0);" width="100%" cellspacing="0" cellpadding="10" border="1">
                    <tbody>
                    <tr style="text-align: right; font-size: 14px; border: 0px solid rgb(0, 0, 0);">
                        <td style="text-align: right; width: 30%; border-right: 0px none rgb(0, 0, 0); border-bottom: 0px;padding-top: 5px; padding-bottom: 5px; border-top: 0px none rgb(0, 0, 0); border-color: rgb(0, 0, 0);">
                        </td>
                        <td style="text-align: right; width: 30%; border-left: 0px;border-right: 0px none rgb(0, 0, 0);  border-bottom: 0px;padding-top: 5px; padding-bottom: 5px; border-top: 0px none rgb(0, 0, 0); border-color: rgb(0, 0, 0);">
                            {!! lang('sale_invoice.sub_total') !!}
                        </td>
                        <td style="text-align: right;width: 10%;border-left: 0;font-size: 14px;padding-top: 5px;padding-bottom: 5px;border-top: 0;">
                            {!! numberFormat($result->gross_amount) !!}
                        </td>
                    </tr>

                    <tr style="text-align: right; font-size: 14px; border: 0px solid rgb(0, 0, 0);">
                        <td style="text-align: right; width: 30%;  border-bottom: 0px;border-right: 0px none rgb(0, 0, 0); padding-top: 5px; padding-bottom: 5px; border-top: 0px none rgb(0, 0, 0); border-color: rgb(0, 0, 0);">
                        </td>
                        <td style="text-align: right; width: 30%;  border-bottom: 0px;border-left: 0px;border-right: 0px none rgb(0, 0, 0); padding-top: 5px; padding-bottom: 5px; border-top: 0px none rgb(0, 0, 0); border-color: rgb(0, 0, 0);">
                            {!! lang('sale_invoice.gst') !!}
                        </td>
                        <td style="text-align: right;width: 10%;border-left: 0;font-size: 14px;padding-top: 5px;padding-bottom: 5px;border-top: 0;">
                            {!! numberFormat($result->gst_amount) !!}
                        </td>
                    </tr>

                    <tr style="text-align: right; font-size: 15px; border: 0px solid rgb(0, 0, 0);">
                        <td style="text-align: left; width: 30%;  border-bottom: 0px;border-right: 0px none rgb(0, 0, 0); padding-top: 5px; padding-bottom: 5px; border-top: 0px none rgb(0, 0, 0); border-color: rgb(0, 0, 0);padding-left: 10px;">
                            {!! lang('sale_invoice.amount_in_words') !!}
                        </td>
                        <td style="text-align: right; width: 30%; border-bottom: 0px;border-left: 0px;border-right: 0px none rgb(0, 0, 0); font-weight: bold; font-size: 1.15em; padding-top: 5px; padding-bottom: 5px; border-top: 0px none rgb(0, 0, 0); border-color: rgb(0, 0, 0);">
                            {!! lang('sale_invoice.total_amount') !!}
                        </td>
                        <td style="text-align: right;width: 10%;border-left: 0;font-size: 1.15em;padding-top: 5px;padding-bottom: 5px;border-top: 0;">
                            {!! numberFormat($result->net_amount) !!}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="4"  style="">
                <table valign=""
                       style="border-collapse: collapse;
                              /*margin-top:5px;*/
                              border-top: 0px none;
                              border: 1px solid #000;
                              border-top:0px;"

                              width="100%"
                              cellspacing="0"
                              cellpadding="5"
                              border="0"
                              >
                    <tbody>
                    <tr>
                        <td colspan="2" style="font-size: 15px;
                                   width: 90%;
                                   font-weight: bold;
                                   border-top: 0px none;
                                   border-left:0px solid;
                                   border-right:0;
                                   padding-top: 0;
                                   vertical-align: top;
                                   text-transform: uppercase;
                                   padding-left: 10px;
                                   ">
                            <?php
                            $amount = round($result->net_amount, 2);
                            $amount = explode('.', $amount);
                            $number = $amount[0];
                            $fraction = (isset($amount[1]) && $amount[1] > 0) ? (int)$amount[1] : 0;
                            ?>
                            {!! numberToWord($number) !!} {!! numberToWord($fraction, false, true) !!} only
                        </td>
                        <td style="width: 10%;border-left: 0px;border-top:1px solid ;"></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="border-top:1px solid;border-right: 0px;">
                            <table >
                                <tbody>
                                @if(is_object($bank) && count($bank) > 0)
                                <tr>
                                    <td>
                                        <table style="font-size: 1.1em;" width="100%">
                                            <tbody>
                                            <tr>
                                                <td colspan="2"><strong> {!! lang('sale_invoice.bank_detail') !!}</strong></td><td>
                                                </td></tr>
                                            <tr>
                                                <td colspan="2" style="padding-left:15px">{!! $bank->name !!}</td><td>
                                                </td></tr>
                                            <tr><td colspan="2" style="padding-left:15px">
                                                    {!! lang('sale_invoice.account_holder') !!} {!! $bank->account_holder !!}<br>
                                                    {!! lang('sale_invoice.bsb') !!} {!! $bank->bsb_number !!}<br>
                                                    {!! lang('sale_invoice.account_number') !!} {!! $bank->account_number !!}<br>
                                                </td><td>
                                                </td></tr>
                                            </tbody></table>
                                    </td>
                                </tr>
                                @endif
                                </tbody></table></td>
                    </tr>
                    <tr>
                        <td style="border-top:1px solid;border-right: 0px;font-weight:bold">
                            This is computer generated invoice.
                        </td>
                        <td colspan="2" style="border-top:1px solid; text-align:right;border-right: 0px;font-weight:bold">
                            Powered by www.cyberconsorts.com
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody></table>
</div>