<div class="invoice-box" style="clear: both;"><br/>
    <table style="margin-left:0px;margin: 0 auto; width:480px;" cellspacing="0" cellpadding="0">
        <tbody>
        <tr class="top">
            <td colspan="4">
                <table style="width: 100%;">
                    <tbody>
                    <tr>
                        <td colspan="4" class="title" style="text-align: center; font-weight: 600; font-size: 1.4em !important;">
                            <span style="text-decoration: underline;">Bill Reciept</span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>


        <tr class="invoive-no">
            <td colspan="4">
                {{--@if($setting->header_required == 2)
                    @for($i =1; $i<=$setting->header_top_space; $i++)
                        <br/>
                    @endfor
                @endif--}}
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tr>
                        <td width="50%">
                            <table width="100%" cellspacing="0" cellpadding="5" border="0">
                                <tbody>
                                <tr>
                                    <td style="text-align: center; font-weight: 600; font-size: 1.3em !important;
                                                                            border-color: #555555; border-style: solid;border-width:1px 0px 0px 1px;" bgcolor="#909090" width=" 48%">
                                        Bill No. :
                                    </td>
                                    <td style="text-align: center; font-weight: 600; font-size: 1.3em !important;
                                                                            border-color: #555555; border-style: solid;border-width:1px 0px 0px 1px;" width="52%" bgcolor="#ffffa8">
                                        {!! 'UNK - '.$result->order_number !!}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                        <td width="50%">
                            <table style="width: 100%;" cellspacing="0" cellpadding="5" border="0">
                                <tbody>
                                <tr>
                                    <td bgcolor="#909090" width="50%" style="text-align: center; font-weight: 600; font-size: 1.3em !important;
                                                                        border-color: #555555; border-style: solid;border-width: 1px 0px 0px 1px;">
                                        Date :
                                    </td>
                                    <td style="width: 50%; text-align: center;
                                                                           font-weight: 600; font-size: 1.3em !important;
                                                                           border-color: #555555; border-style: solid;
                                                                           border-width: 1px 1px 0 1px;" bgcolor="#ffffa8">
                                        {!! convertToLocal($result->order_date, 'd.m.Y') !!}
                                        <?php $orderTime = convertToLocal($result->order_date, 'Y-m-d H:i:s'); ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr valign="top">
            <td colspan="2" style="border-width:1px 0px 0px 1px;border-style:solid ;border-color:#555555;" width="50%">
                <table valign="" top="" width="100%" cellspacing="0" cellpadding="5" border="0">
                    <tbody>
                    <tr>
                        <td style="font-size: 1.1em; text-decoration: underline; font-weight: bold; padding-bottom: 0px;">
                           Bill To:
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 1.1em;" valign="top" width="100%">
                            {!!  isset($party->customer_name)?$party->customer_name:$party->mr_name . ' <br/>' . $party->address !!} <br/>
                            @if($party->city != "")
                                {!! $party->city !!}
                            @endif

                            @if($party->pin_code != "")
                                {!! $party->state !!} {!! ' - ' . $party->pin_code !!} <br>
                            @endif

                            <br/> MOBILE: {!! $party->mobile_no !!} @if($party->landline_no != "")| Phone: {!! $party->landline_no !!}@endif
                           {{-- <br/> GST NO: {!! $party->gst_number !!}--}}
                           {{-- @if($party->pan_number != "")
                                <br> PAN NO: {!! $party->pan_number !!}
                            @endif--}}

                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td colspan="2" style="border-width:1px 1px 0px 1px;border-style:solid ;border-color:#555555;" width="50%">
                <table width="100%" cellspacing="2" cellpadding="0" border="0" style="font-size: 1.0em;">
                    <tbody>

                        <tr>
                            <td width="40%" bgcolor="#ffffa8" style="padding-top:0;padding-bottom: 0;">Company:</td>
                            <td colspan="4"> {!! $company->company_name  !!} ({!! paddingLeft($company->contact_person) !!}) </td>
                        </tr>

                    <tr>
                        <td bgcolor="#ffffa8" style="padding-top:0;padding-bottom: 0;">Address :</td>
                        <td colspan="4">{!! $company->address !!}{!! '  ' .$company->state !!}{!!  ', ' .$company->city !!}{!! '  ' .$company->pincode !!} </td>
                    </tr>
                    <tr>
                        @if($company->gst_number != "")
                            <td bgcolor="#ffffa8" style="padding-top:0;padding-bottom: 0;">Gst :</td>
                            <td colspan="4">{!! $company->gst_number !!} </td>
                        @endif
                     </tr>

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
                            width="8%"> QTY
                        </td>
                        {{--<td style="text-align: center;border-width: 1px 0px 1px 1px;border-style: solid;border-color: #555555;font-weight: 600;text-transform: uppercase;font-size: 1em;"
                            width="8%"> UNIT
                        </td>--}}
                        <td style="text-align: center;border-width: 1px 0px 1px 1px;border-style: solid;border-color: #555555;font-weight: 600;font-size: 1em;text-transform:uppercase"
                            width="10%"> RATE
                        </td>

                                {{--<td style="text-align: center;border-width: 1px 0px 1px 1px;border-style: solid;border-color: #555555;font-weight: 600;text-transform: uppercase;font-size: 1em;"
                                    width="9%"> CGST%
                                </td>
                                <td style="text-align: center;border-width: 1px 0px 1px 1px;border-style: solid;border-color: #555555;font-weight: 600;text-transform: uppercase;font-size: 1em;"
                                    width="9%"> SGST%
                                </td>--}}

                        <td style="text-align: center;border-width: 1px 0px 1px 1px;border-style: solid;border-color: #555555;font-weight: 600;text-transform: uppercase;font-size: 1em;" width="10%"> AMOUNT
                        </td>
                    </tr>
                    <tbody>
                    <?php $i = $PageBreak = 1; $subTotal = 0; $totalQuantity = $cgstAmount = $sgstAmount = $igstAmount = 0;
                    $totalRecords = count($orderItems); $addBr = 18;
                    $total=0;
                    ?>
                    @foreach($orderItems as $detail)
                        <tr style="border-top:0;border-bottom:0;font-size:1.1em" data-rowcount="{!! $PageBreak !!}">
                            <td style="border-top:0;border-bottom:0;text-align:center;border-width:0 1px 0 0;">
                                {!! $i++ !!}.
                            </td>
                            <td style="text-align:left; border-top:0;border-bottom:0;border-width:0 0 0 0px;">
                                {!! $detail->name .' - '. $detail->normal_size .'mm' !!}
                            </td>
                            <td style="text-align:left; border-top:0;border-bottom:0;border-right:0">
                                {!! $detail->hsn_code !!}
                            </td>
                            <td style="text-align:left; border-top:0;border-bottom:0;border-right:0">
                                {!! $detail->quantity !!}
                            </td>
                           {{-- <td style="text-align:center; border-top:0;border-bottom:0;border-right:0">
                               unit
                            </td>--}}
                            <td style="text-align:right;border-top:0;border-bottom:0;border-right:0;">
                                {!! indianFormat($detail->price) !!}
                            </td>
                               {{-- <td style="text-align:center;border-top:0;border-bottom:0;border-right:0;">
                                    {!! $detail->cgst !!}%
                                </td>
                                <td style="text-align:center;border-top:0;border-bottom:0;border-right:0;">
                                    {!! $detail->sgst !!}%
                                </td>--}}

                            <td style="text-align:right;border-top:0;border-bottom:0;border-right:0;">
                            <?php $total_amount=$detail->quantity*$detail->price;?>
                                {!! indianFormat($total_amount) !!}
                                {{--{!! numberFormat( $detail->quantity*$detail->price) !!}--}}
                            </td>
                        </tr>

                        <?php
                        $total += $total_amount;
                        //$total = $detail->total_price;

                            $cgstAmount += $detail->cgst_amount;
                            $sgstAmount += $detail->sgst_amount;

                            $igstAmount += $detail->igst_amount;

                        $totalPrice = $detail->total_price;
                        $subTotal += $totalPrice;
                        ?>
                    @endforeach
                    <?php
                    $totalAmount= $total;
                    ?>

                    @for($i = 1; $i <= (15 - $totalRecords); $i++)
                        <tr style="border-top:0;border-bottom:0;" data-totalrecordcount="2">
                            <td style="border-top:0;border-bottom:0;text-align: center;border-left:0;">
                                &nbsp;
                            </td>
                            <td style="border-top: 0px none; border-bottom: 0px none; text-align: right; font-weight: 600; font-size: 15px;border-left:0;border-right:0;"></td>
                            <td style="border-top:0;border-bottom:0;text-align: right;border-right:0;"></td>
                            <td style="border-top:0;border-bottom:0;text-align: right;border-right:0;"></td>
                            <td style="border-top:0;border-bottom:0;text-align: right;border-right:0;"></td>
                           {{-- <td style="border-top:0;border-bottom:0;text-align: right;border-right:0;"></td>--}}

                                {{--<td style="border-top:0;border-bottom:0;text-align: right;border-right:0;"></td>--}}
                                <td style="border-top:0;border-bottom:0;text-align: right;font-size: 15px;border-right:0"></td>

                            {{--<td style="border-top:0;border-bottom:0;text-align: right;font-size: 15px;border-right:0"></td>--}}
                        </tr>
                    @endfor

                    <?php $sgstTax = $cgstTax = $calculate = $igstTax = 0; $taxRate = ''; ?>
                    <tr>
                        <td colspan="2" rowspan="7"
                            style="text-transform: uppercase;text-align:left;vertical-align:top;font-size: 15px;border-left:0;border-right:0;border-bottom:0">

                            <table width="70%" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td colspan="7">
                                        <strong>Amount in
                                            words :</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 0.8em !important;text-transform: uppercase;">
                                        {!! string_manip(getIndianCurrency($result->gross_amount)) !!} only
                                    </td>
                                </tr>
                                {{-- <tr>
                                    <td colspan="7">
                                        <strong>Tax Summary:</strong>
                                    </td>
                                </tr>

                                <tr>
                                     <th width="3%">&nbsp; </th>

                                         <th width="2%" style="text-align: center;">(%) </th>
                                         <th width="4%" style="text-align: center;">Amount </th>

                                     <th width="5%" style="text-align: center;">Total </th>
                                 </tr>

                                         <tr>
                                             <th>Gst </th>


                                                 <td style="text-align: center;"> 9</td>
                                                 <td style="text-align: center;">{!!  $cgstAmount !!} </td>

                                             <td style="text-align: center;">{!!  $cgstAmount !!} </td>
                                         </tr>--}}

                            </table>
                        </td>
                        <td colspan="3"
                            style="text-align:right;vertical-align:middle;font-weight: 600;border-right:0;border-bottom:0">
                            Total
                        </td>
                        {{--<td style="border-right:0;border-bottom:0;text-align: right;">{!! $result->gross_amount !!}</td>--}}
                        <td style="border-right:0;border-bottom:0;text-align: right;">{!!  indianFormat($totalAmount) !!}</td>
                    </tr>

                        <tr>
                            {{--<td colspan="3"
                                style="text-align:right;vertical-align:middle;font-weight: 600;border-bottom:0;border-right:0">
                                CGST AMOUNT
                            </td>
                            <td style="border-bottom:0;border-right:0;text-align: right;"> cgstAmount </td>--}}
                        </tr>

                        <tr>
                            {{--<td colspan="3"
                                style="text-align:right;vertical-align:middle;font-weight: 600;border-bottom:0;border-right:0">
                                SGST AMOUNT
                            </td>
                            <td style="border-bottom:0;border-right:0;text-align: right;"> sgstAmount </td>--}}
                        </tr>

                    <tr>
                        <td colspan="3"
                            style="text-align:right;vertical-align:middle;font-weight: 600;border-right:0;border-bottom:0">
                            &nbsp;
                        </td>
                        <td style="border-right:0;border-bottom:0;text-align: right;"> &nbsp; </td>
                    </tr>
                   {{-- <tr>
                        <td colspan="3"
                            style="text-align:right;vertical-align:middle;font-weight: 600;border-bottom:0;border-right:0">
                            {!! lang('invoice.freight') !!}
                        </td>
                        <td style="border-bottom:0;border-right:0;text-align: right;">
                            freight
                        </td>
                    </tr>--}}
                   {{-- <tr>
                        <td colspan="3"
                            style="text-align:right;vertical-align:middle;font-weight: 600;border-bottom:0;border-right:0">
                            {!! lang('order.other_charges') !!}
                        </td>
                        <td style="border-bottom:0;border-right:0;text-align: right;">
                          0.00
                        </td>
                    </tr>--}}
                    <tr>
                        <td colspan="3"
                            style="text-align:right;vertical-align:middle;font-weight: 600;border-bottom:0;border-right:0">
                            {!! lang('order.round_off') !!}
                        </td>
                        <td style="border-bottom:0;border-right:0;text-align: right;">
                            {!! ($result->round_off < 0) ? '-' : ' ' !!}{!! numberFormat(substr($result->round_off, 1)) !!}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>

        <tr class="total-amount" >
            <td colspan="4">
                <table valign="" style="border-width:1px 1px 1px 1px;border-style:solid;border-color:#222;" width="100%"
                       cellspacing="0" cellpadding="5" border="0">
                    <tbody>
                    <tr style="border:1px solid #555;text-align: right;font-size: 15px;">
                        <td style="text-align: right; width: 85%; border-right: 0px none; font-weight: bold; font-size: 1em;padding-top: 5px;padding-bottom: 5px;border-top: 0;">
                            TOTAL AMOUNT
                        </td>
                        <td style="text-align: right;width: 15%;border-left: 0;font-size: 1em;padding-top: 5px;padding-bottom: 5px;border-top: 0;">

                            {!! indianFormat( $result->gross_amount ) !!}
                           {{-- {!! numberFormat( $result->net_amount ) !!}--}}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
       {{-- <tr  style="border: 1px solid #555;">
            <td colspan="4">
                <table valign="" style="border-width:1px 1px 1px 1px;border-style:solid;border-color:#222;" width="100%"
                       cellspacing="0" cellpadding="5" border="0">
                    <tbody>
                    <tr valign="top">
                        <td style="text-align: left;width: 30%;border-top:0;border-right:1px solid">
                            <table style="font-size: 1.1em;" width="100%" cellpadding="0" cellspacing="0">
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
                            <table cellpadding="0" cellspacing="0">
                                <tbody>
                                <tr>
                                    <td style="font-size: 0.8em !important;text-transform: uppercase;font-weight: bold;">
                                        {!! string_manip(numberToWord($result->net_amount)) !!} only
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>--}}

        </tbody>
    </table>
</div>
