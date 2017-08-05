<div class="invoice-box" style="clear: both;"><br/>
    <table style="margin-left:0px;margin: 0 auto; width:900px;" cellspacing="0" cellpadding="0">
        <tbody>
        @if($setting->header_required == 1)
            <tr class="top">
                <td colspan="4">
                    <table style="width: 100%;">
                        <tbody>
                        <tr>
                            <td class="title" style="text-align: center; width: 70%; font-weight: 600; font-size: 1.3em; padding-left:210px;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="text-decoration: underline;">{!! $setting->invoice_label !!}</span>
                            </td>

                            <td style="text-align:right; width: 30%;font-size: 0.90em;">
                                &nbsp;
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr class="header-info">
                <td colspan="4">
                    <table style="border: 1px solid #555555;background: #94fff5;border-bottom: 0;" width="100%" cellpadding="5">
                        <tbody>
                        <tr valign="top">
                            @if($copy == 1)
                                <div style="text-align: center;padding-bottom: 10px;">Original for buyer<br></div>
                            @endif
                            @if($copy == 2)
                                <div style="text-align: center;padding-bottom: 10px;">Duplicate for transporter<br></div>
                            @endif
                            @if($copy == 3)
                                <div style="text-align: center;padding-bottom: 10px;">Triplicate for Assessee<br></div>
                            @endif
                            <td style="width: 50%; text-align: left; padding-top: 0px;padding-left: 0; font-size: 1.2em;">
                                @if($setting->gst_display == 1)
                                <p style="margin: 0 0 0px 0;"><strong> GST No:</strong> {!! (!empty($company->gst_number)) ? $company->gst_number : '---' !!} </p>
                                @endif
                                @if($setting->pan_display == 1)
                                <p style="margin: 0 0 0px 0;"><strong> PAN No:</strong> {!! (!empty($company->pan_number)) ? $company->pan_number : '---' !!} </p>
                                @endif
                            </td>

                            <td style="width: 50%; text-align: right; padding-top: 0px; font-size: 1.2em;">
                                <!-- Mob: -->
                                Phone (O): {!! (!empty($company->phone))? $company->phone : '---' !!} <br />
                                &nbsp;&nbsp;Mobile: {!! (!empty($company->mobile1))? $company->mobile1 : '---' !!}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align: center; font-size: 1.1em;padding-bottom: 0;padding-left: 0;">
                                <h3 style="margin-bottom: 0px; margin-top: 0px;margin-bottom:0;  font-family: times new roman; text-transform: uppercase; font-size: 2.6em; font-weight: 900;"> {!! $company->company_name !!} </h3>

                                <div style="text-align: center; font-size: 1.0em;margin: 0;">
                                    {!! ($setting->deal_in != "") ? $setting->deal_in . ' <br>' : '' !!}
                                    {!! $company->permanent_address !!},
                                    {!! $company->city . ' - ' . $company->pincode !!} ({!! $company->state_name !!})<br>
                                    <div>@if((!empty($company->email1))) Email: {!! $company->email1 !!} @endif</div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        @endif

        <tr class="invoive-no">
            <td colspan="4">
                @if($setting->header_required == 2)
                    @for($i =1; $i<=$setting->header_top_space; $i++)
                        <br/>
                    @endfor
                @endif
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tr>
                        <td width="50%">
                            <table width="100%" cellspacing="0" cellpadding="5" border="0">
                                <tbody>
                                <tr>
                                    <td style="text-align: center; font-weight: 600; font-size: 1.4em;
                                                                            border-color: #555555; border-style: solid; border-width:  1px 0px 0px 1px;" bgcolor="#909090" width=" 48%">
                                        Invoice No. :
                                    </td>
                                    <td style="text-align: center; font-weight: 600; font-size: 1.4em;
                                                                            border-color: #555555; border-style: solid;border-width: 1px 0px 0px 1px;" width="52%" bgcolor="#ffffa8">
                                        {!! $setting->invoice_prefix !!}{!! $result->invoice_number !!}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                        <td width="50%">
                            <table style="width: 100%;" cellspacing="0" cellpadding="5" border="0">
                                <tbody>
                                <tr>
                                    <td bgcolor="#909090" width="50%" style="text-align: center; font-weight: 600; font-size: 1.4em;
                                                                        border-color: #555555; border-style: solid;border-width: 1px 0px 0px 1px;">
                                        Date :
                                    </td>
                                    <td style="width: 50%; text-align: center;
                                                                           font-weight: 600; font-size: 1.4em;
                                                                           border-color: #555555; border-style: solid;
                                                                           border-width: 1px 1px 0px 1px" bgcolor="#ffffa8">
                                        {!! convertToLocal($result->invoice_date, 'd.m.Y') !!}
                                        <?php $orderTime = convertToLocal($result->invoice_date, 'Y-m-d H:i:s'); ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr top="" valign="top">
            <td colspan="2" style="border-width:1px 0px 0px 1px;border-style:solid ;border-color:#555555;" width="50%">
                <table valign="" top="" width="100%" cellspacing="0" cellpadding="5" border="0">
                    <tbody>
                    <tr>
                        <td style="font-size: 1.1em; text-decoration: underline; font-weight: bold; padding-bottom: 0px;">
                            Consignee:
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 1.1em;" valign="top" width="100%">
                            {!! $party->salutation !!}
                            {!! $party->account_name !!}<br>
                            {!! $party->address1 !!}<br>
                            @if($party->city != "")
                                {!! $party->city !!}
                            @endif

                            @if($party->pincode != "")
                                {!! $party->state_name !!} {!! ' - ' . $party->pincode !!} <br>
                            @endif

                            <br> MOBILE: {!! $party->mobile1 !!}, Phone: {!! $party->phone !!}
                            <br> GST NO: {!! $party->gst_number !!}
                            @if($party->pan_number != "")
                                <br> PAN NO: {!! $party->pan_number !!}
                            @endif
                            @if($party->ecc_number != "")
                                <br>ECC NO: {!! $party->ecc_number !!}
                            @endif
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td colspan="2" style="border-width:1px 1px 0px 1px;border-style:solid ;border-color:#555555;" width="50%">
                <table width="100%" cellspacing="2" cellpadding="0" border="0" style="font-size: 1.0em;">
                    <tbody>
                    @if($company->state_name != "")
                    <tr>
                        <td bgcolor="#ffffa8" style="padding-top:0;padding-bottom: 0;">Place of Supply :</td>
                        <td colspan="4"> {!! $company->state_name  !!} ({!! paddingLeft($company->state_digit_code) !!}) </td>
                    </tr>
                    @endif

                    @if($result->order_number != "")
                    <tr>
                            <td bgcolor="#ffffa8">Order Number:</td>
                            <td> {!! $result->order_number !!}</td>
                            <td bgcolor="#ffffa8">Date :</td>
                            <td> {!! dateFormat('d.m.Y', $result->order_date) !!}</td>
                    </tr>
                    @endif
                    <tr>
                        <td bgcolor="#ffffa8" style="padding-top:0;padding-bottom: 0;">Mode :</td>
                        <td colspan="4"> {!! cashOrCredit($result->cash_credit) !!} </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffa8" style="padding-top:0;padding-bottom: 0;">Transport :</td>
                        <td colspan="4"> {!! $result->carriage !!} </td>
                    </tr>
                    <tr valign="top">
                        <td bgcolor="#ffffa8" style="padding-top:0;padding-bottom: 0;width:30%;"> Through :</td>
                        <td colspan="4"> {!! $result->through !!}</td>
                    </tr>
                    <tr style="display: none;">
                        <td bgcolor="#ffffa8" style="padding-top:0;padding-bottom: 0;"> Dispatch To :</td>
                        <td colspan="4">  {!! $result->dispatch_to !!} </td>
                    </tr>

                    <tr style="display: none;">
                        <td bgcolor="#ffffa8" style="padding-top:0;padding-bottom: 0;"> Private Mark :</td>
                        <td> {!! $result->private_mark !!} </td>
                        <td bgcolor="#ffffa8" style="padding-top:0;padding-bottom: 0;"> Freight :</td>
                        <td> {!! ($result->freight > 0) ? 'Paid' : 'To Pay' !!} </td>
                    </tr>
                    <tr style="display: none;">
                        <td bgcolor="#ffffa8" style="padding-top:0;padding-bottom: 0;"> No. of Boxes :</td>
                        <td> {!! $result->no_of_cases !!} </td>
                        <td bgcolor="#ffffa8" style="padding-top:0;padding-bottom: 0;">Vehicle No:</td>
                        <td> {!! $result->vehicle_no !!} </td>
                    </tr>
                    {{--<tr>
                        <td bgcolor="#ffffa8" style="padding-top:0;padding-bottom: 0;">Road Permit No. :</td>
                        <td colspan="4"> {!! $result->road_permit_number !!} </td>
                    </tr>--}}
                    </tbody>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="4">
                <table style="border-top: 0;box-shadow: none; border-color: #555555;" width="100%" cellspacing="0" cellpadding="3" border="1">
                    <tr style="border-top:0;border-bottom:0;">
                        <td style="text-align: center; border-width: 1px 1px 1px 0px;border-style: solid;border-color: #555555;
                                               font-weight: bold; text-transform: uppercase;
                                               font-size: 1em;" width="3%">
                            S.no.
                        </td>
                        <td style="text-align: center;border-width: 1px 0px 1px 0px;border-style: solid;
                                               border-color: #555555;font-weight: 600;text-transform: uppercase;
                                               font-size: 1em;" width="40%">
                            DESCRIPTION OF GOODS
                        </td>
                        <td style="text-align: center;border-width: 1px 0px 1px 1px;border-style: solid;border-color: #555555;font-weight: 600;text-transform: uppercase;font-size: 1em;"
                            width="5%"> HSN
                        </td>
                        <td style="text-align: center;border-width: 1px 0px 1px 1px;border-style: solid;border-color: #555555;font-weight: 600;text-transform: uppercase;font-size: 1em;"
                            width="8%"> QUANTITY
                        </td>
                        <td style="text-align: center;border-width: 1px 0px 1px 1px;border-style: solid;border-color: #555555;font-weight: 600;text-transform: uppercase;font-size: 1em;"
                            width="8%"> UNIT
                        </td>
                        <td style="text-align: center;border-width: 1px 0px 1px 1px;border-style: solid;border-color: #555555;font-weight: 600;font-size: 1em;text-transform:uppercase"
                            width="10%"> RATE
                        </td>
                        @if($result->sale == 1)
                            <td style="text-align: center;border-width: 1px 0px 1px 1px;border-style: solid;border-color: #555555;font-weight: 600;text-transform: uppercase;font-size: 1em;"
                                width="9%"> CGST%
                            </td>
                            <td style="text-align: center;border-width: 1px 0px 1px 1px;border-style: solid;border-color: #555555;font-weight: 600;text-transform: uppercase;font-size: 1em;"
                                width="9%"> SGST%
                            </td>
                        @elseif($result->sale == 2)
                            <td style="text-align: center;border-width: 1px 0px 1px 1px;border-style: solid;border-color: #555555;font-weight: 600;text-transform: uppercase;font-size: 1em;"
                                width="9%"> IGST%
                            </td>
                        @endif
                        <td style="text-align: center;border-width: 1px 0px 1px 1px;border-style: solid;border-color: #555555;font-weight: 600;text-transform: uppercase;font-size: 1em;" width="10%"> AMOUNT
                        </td>
                    </tr>
                    <tbody>
                    <?php $i = $PageBreak = 1; $subTotal = 0; $totalQuantity = $cgstAmount = $sgstAmount = $igstAmount = 0;
                    $totalRecords = count($orderItems); $addBr = 18;
                    ?>
                    @foreach($orderItems as $detail)
                        <tr style="border-top:0;border-bottom:0;font-size:1.1em" data-rowcount="{!! $PageBreak !!}">
                            <td style="border-top:0;border-bottom:0;text-align:center;border-width:0 1px 0 0;">
                                {!! $i++ !!}.
                            </td>
                            <td style="text-align:left; border-top:0;border-bottom:0;border-width:0 0 0 0px;">
                                {!! $detail->product_name !!}
                            </td>
                            <td style="text-align:left; border-top:0;border-bottom:0;border-right:0">
                                {!! $detail->hsn_code !!}
                            </td>
                            <td style="text-align:left; border-top:0;border-bottom:0;border-right:0">
                                {!! $detail->quantity !!}
                            </td>
                            <td style="text-align:center; border-top:0;border-bottom:0;border-right:0">
                                {!! $detail->unit !!}
                            </td>

                            <td style="text-align:right;border-top:0;border-bottom:0;border-right:0;">
                                <?php
                                $price = ($detail->manual_price > 0) ? $detail->manual_price : $detail->price;
                                ?>
                                {!! numberFormat($price) !!}
                            </td>
                            @if($result->sale == 1)
                                <td style="text-align:center;border-top:0;border-bottom:0;border-right:0;">
                                    {!! $detail->cgst !!}%
                                </td>
                                <td style="text-align:center;border-top:0;border-bottom:0;border-right:0;">
                                    {!! $detail->sgst !!}%
                                </td>
                            @elseif($result->sale == 2)
                                <td style="text-align:center;border-top:0;border-bottom:0;border-right:0;">
                                    {!! $detail->igst !!}%
                                </td>
                            @endif
                            <td style="text-align:right;border-top:0;border-bottom:0;border-right:0;">
                                <?php
                                $total = getRoundedAmount($detail->total_price);
                                if($result->sale == 1) {
                                    $cgstAmount += getRoundedAmount($detail->cgst_amount);
                                    $sgstAmount += getRoundedAmount($detail->sgst_amount);
                                } elseif ($result->sale == 2) {
                                    $igstAmount += getRoundedAmount($detail->igst_amount);
                                }
                                $totalPrice = $detail->total_price;
                                $subTotal += $totalPrice;
                                ?>
                                {!! numberFormat($total) !!}
                            </td>
                        </tr>
                    @endforeach

                    @for($i = 1; $i <= (24 - $totalRecords); $i++)
                        <tr style="border-top:0;border-bottom:0;" data-totalrecordcount="2">
                            <td style="border-top:0;border-bottom:0;text-align: center;border-left:0;">
                                &nbsp;
                            </td>
                            <td style="border-top: 0px none; border-bottom: 0px none; text-align: right; font-weight: 600; font-size: 15px;border-left:0;border-right:0;"></td>
                            <td style="border-top:0;border-bottom:0;text-align: right;border-right:0;"></td>
                            <td style="border-top:0;border-bottom:0;text-align: right;border-right:0;"></td>
                            <td style="border-top:0;border-bottom:0;text-align: right;border-right:0;"></td>
                            <td style="border-top:0;border-bottom:0;text-align: right;border-right:0;"></td>
                            @if($result->sale == 1)
                                <td style="border-top:0;border-bottom:0;text-align: right;border-right:0;"></td>
                                <td style="border-top:0;border-bottom:0;text-align: right;font-size: 15px;border-right:0"></td>
                            @elseif($result->sale == 2)
                                <td style="border-top:0;border-bottom:0;text-align: right;font-size: 15px;border-right:0"></td>
                            @endif
                            <td style="border-top:0;border-bottom:0;text-align: right;font-size: 15px;border-right:0"></td>
                        </tr>
                    @endfor

                    <?php $sgstTax = $cgstTax = $calculate = $igstTax = 0; $taxRate = ''; ?>
                    <tr>
                        <td colspan="@if($result->sale == 2) 4 @else 5 @endif" rowspan="7"
                            style="text-transform: uppercase;text-align:left;vertical-align:top;font-size: 15px;border-left:0;border-right:0;border-bottom:0">
                            <table width="100%">
                                <tr>
                                    <td rowspan="6">
                                        <strong>Bank Detail:</strong> {!! $bank->name !!}
                                        <div style="font-size: 13px;padding-top: 5px;">
                                            A/C Number: {!! $bank->account_number !!} <br/>
                                            IFS CODE: {!! $bank->bsb_number !!}
                                        </div>
                                    </td>
                                </tr>
                                <tr><td>&nbsp;</td></tr>
                                <tr><td>&nbsp;</td></tr>
                            </table>

                            <table width="70%" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td colspan="7"><br/>
                                        <strong>Tax Summary:</strong>
                                    </td>
                                </tr>
                                <?php
                                if($result->sale == 1) {
                                    $gst = $taxes['cgst'];
                                } elseif($result->sale == 2) {
                                    $gst = $taxes['igst'];
                                }
                                $totalCgstTax = $totalSgstTax = 0;
                                ?>
                                <tr>
                                    <th width="3%">&nbsp; </th>
                                    @foreach($gst as $values)
                                        <th width="2%" style="text-align: center;">(%) </th>
                                        <th width="4%" style="text-align: center;">Amount </th>
                                    @endforeach
                                    <th width="5%" style="text-align: center;">Total </th>
                                </tr>
                                @foreach($taxes as $label => $tax)
                                    @if(count($tax) > 0)
                                        <tr>
                                            <th>{!! $label !!} </th>
                                            <?php $totalTax = 0; ?>
                                            @foreach($tax as $key => $amount)
                                                <td style="text-align: center;">{!! trim($key, "'") !!}</td>
                                                <td style="text-align: center;">{!! numberFormat(getRoundedAmount($amount)) !!} </td>
                                                <?php $totalTax +=$amount; ?>
                                            @endforeach
                                            <td style="text-align: center;">{!! numberFormat(getRoundedAmount($totalTax)) !!} </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </table>
                        </td>
                        <td colspan="3"
                            style="text-align:right;vertical-align:middle;font-weight: 600;border-right:0;border-bottom:0">
                            Total
                        </td>
                        <td style="border-right:0;border-bottom:0;text-align: right;">{!! numberFormat(getRoundedAmount($subTotal)) !!}</td>
                    </tr>
                    @if($result->sale == 1)
                        <tr>
                            <td colspan="3"
                                style="text-align:right;vertical-align:middle;font-weight: 600;border-bottom:0;border-right:0">
                                CGST AMOUNT:
                            </td>
                            <td style="border-bottom:0;border-right:0;text-align: right;"> {!! numberFormat(getRoundedAmount($cgstAmount)) !!} </td>
                        </tr>

                        <tr>
                            <td colspan="3"
                                style="text-align:right;vertical-align:middle;font-weight: 600;border-bottom:0;border-right:0">
                                SGST AMOUNT
                            </td>
                            <td style="border-bottom:0;border-right:0;text-align: right;"> {!! numberFormat(getRoundedAmount($sgstAmount)) !!} </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="3"
                                style="text-align:right;vertical-align:middle;font-weight: 600;border-bottom:0;border-right:0">
                                IGST AMOUNT
                            </td>
                            <td style="border-bottom:0;border-right:0;text-align: right;"> {!! numberFormat(getRoundedAmount($igstAmount)) !!} </td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="3"
                            style="text-align:right;vertical-align:middle;font-weight: 600;border-right:0;border-bottom:0">
                            &nbsp;
                        </td>
                        <td style="border-right:0;border-bottom:0;text-align: right;"> &nbsp; </td>
                    </tr>
                    <tr>
                        <td colspan="3"
                            style="text-align:right;vertical-align:middle;font-weight: 600;border-bottom:0;border-right:0">
                            {!! lang('invoice.freight') !!}:
                        </td>
                        <td style="border-bottom:0;border-right:0;text-align: right;">
                            {!! numberFormat($result->freight) !!}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"
                            style="text-align:right;vertical-align:middle;font-weight: 600;border-bottom:0;border-right:0">
                            {!! lang('invoice.other_charges') !!}:
                        </td>
                        <td style="border-bottom:0;border-right:0;text-align: right;">
                            {!! numberFormat($result->other_charges) !!}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"
                            style="text-align:right;vertical-align:middle;font-weight: 600;border-bottom:0;border-right:0">
                            {!! lang('invoice.round_off') !!}:
                        </td>
                        <td style="border-bottom:0;border-right:0;text-align: right;">
                            {!! ($result->round_off < 0) ? '-' : ' ' !!}{!! numberFormat(substr($result->round_off, 1)) !!}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>

        <tr class="total-amount">
            <td colspan="4">
                <table style="border-width:0px 1px 0 1px;border-style:solid;border-color:#222" width="100%"
                       cellspacing="0" cellpadding="10" border="0">
                    <tbody>
                    <tr style="border:1px solid #555;text-align: right;font-size: 15px;">
                        <td style="text-align: right; width: 85%; border-right: 0px none; font-weight: bold; font-size: 1em;padding-top: 5px;padding-bottom: 5px;border-top: 0;">
                            TOTAL AMOUNT
                        </td>
                        <td style="text-align: right;width: 15%;border-left: 0;font-size: 1em;padding-top: 5px;padding-bottom: 5px;border-top: 0;">
                            <?php $netAmount = getRoundedAmount(($subTotal + $cgstAmount + $sgstAmount + $igstAmount + $result->freight + $result->other_charges) + $result->round_off); ?>
                            {!! numberFormat($netAmount) !!}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr style="border: 1px solid #555;">
            <td colspan="4">
                <table valign="" style="border-width:1px 1px 1px 1px;border-style:solid;border-color:#222;" width="100%"
                       cellspacing="0" cellpadding="5" border="0">
                    <tbody>
                    <tr valign="top">
                        <td style="text-align: left;width: 30%;border-top:0;border-right:1px solid">
                            <table style="font-size: 1.1em;" width="100%">
                                <tbody>
                                <tr>
                                    <td style="background: rgb(255, 255, 164) none repeat scroll 0px 0px;"> Amount in
                                        words :
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                        <td style="width: 70%;border-top:0">
                            <table>
                                <tbody>
                                <tr>
                                    <td style="font-size: 0.9em;text-transform: uppercase;font-weight: bold;">
                                        {!! string_manip(numberToWord($netAmount)) !!} only
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

        @if($setting->footer_required == 1)
            <tr valign="top">
                <td colspan="4">
                    <table style="border-width:0px 0px 0 0px;border-style:solid;border-color:#222;" width="100%"
                           cellspacing="0" cellpadding="5" border="1">
                        <tbody>
                        <tr valign="top">
                            <td style="border-right:0;border-top:0;border-bottom:0;background: #ccffbf;" width="50%">
                                <table width="100%" cellspacing="0" cellpadding="3" border="0">
                                    <tbody>
                                    <tr>
                                        <td style="text-decoration: underline;font-size: 0.90em"> TERMS :</td>
                                    </tr>

                                    <tr>
                                        <td style="font-size: 0.85em;padding-bottom: 0;">
                                            {!! nl2br($setting->terms) !!}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="border-left:0;border-top:0;border-bottom:0;" width="50%">
                                <table width="100%" cellspacing="0" cellpadding="5">
                                    <tbody>
                                    <tr valign="top">
                                        <td style="text-align: right;"><h3
                                                    style="margin-top: 0px; margin-bottom: 0px; font-size: 18px;">For <span
                                                        style="text-transform:uppercase">{!! $company->company_name !!} </span></h3></td>
                                    </tr>
                                    <tr>
                                        <td><br><br></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right; font-size: 14px;"><h4
                                                    style="margin-top: 0;margin-bottom: 0;">
                                                @if($setting->auth_signature_show == 1)
                                                    {!! $setting->auth_text !!}
                                                @endif
                                            </h4>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                        <tr valign="top">
                            <td style="border-right:0;border-top:0;" width="50%">
                                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                    <tr>
                                        <td style="font-size: 0.90em;font-weight:bold;text-align: right;"></td>
                                    </tr>
                                </table>
                            </td>
                            <td style="border-left:0;border-top:0" width="50%">
                                <table width="100%" cellspacing="0" cellpadding="0">
                                    <tbody>
                                    <tr valign="top">
                                        <td style="text-align: left;">
                                            <br/>
                                            @if($setting->customer_signature_show == 1)
                                                {!! $setting->signature_text !!}
                                            @endif
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
        @endif

        <tr>
            <td colspan="4">
                @if($setting->footer_required == 2)
                    @for($i =1; $i<=$setting->footer_space; $i++)
                        <br/>
                    @endfor
                @endif
                <table width="100%">
                    <tr>
                        <td width="50%" style="padding: 5px 0;font-size: 13px;font-weight:bold;"> This is computer generated invoice.
                        </td>
                        <td width="50%" style="padding: 5px 0;text-align: right;font-size: 13px;font-weight:bold;"> Powered by cyberconsorts.com
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>
