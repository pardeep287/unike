<!--Invoice Layout-->
<table width="800px" cellpadding="0" cellspacing="0" border="0" style="text-align:left; margin:0 auto;margin-bottom: 40px;">
    <tr>
        <td valign="top">
            <table width="100%" border="1" cellspacing="10" cellpadding="5" bordercolor="#4EA08F" style="border-bottom:1px solid transparent; background:#4EA08F; border-collapse:collapse; color:#ffffff;">
                <tr>
                    <th width="50%" align="left" style="text-transform:uppercase; border-right:1px solid #ffffff;"> {!! $setting->invoice_label !!} </th>
                    <td width="50%" align="right" >
                        @if($copy == 1) Original for buyer @endif
                        @if($copy == 2) Duplicate for transporter @endif
                        @if($copy == 3) Triplicate for Assessee @endif
                    </td>
                </tr>
            </table>

        </td>
    </tr>
    <tr>
        <td valign="top">

            <table width="100%" bgcolor="#ffffff" border="1" cellpadding="0" bordercolor="#e1e1e1" style="padding:10px; border-style	:solid; border-collapse:collapse; color:#333333;">
                <tr>
                    <td width="50%" valign="top">
                        <table width="100%" border="0" cellpadding="5">
                            <tr>
                                <td valign="top">
                                    <span style="font-size:22px;display:block;color:#4EA08F;text-transform:uppercase;"> {!! $company->company_name !!}</span>
                                      <span style="display:block;">
                                         {!! ($setting->deal_in != "") ? $setting->deal_in . ' <br>' : '' !!}
                                          {!! $company->permanent_address. ' <br>' !!}
                                          @if((!empty($company->email1))) Email: {!! $company->email1 !!} @endif
                                      </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="50%" align="left" style="padding:5px;" valign="top">

                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td width="50%" valign="top">

                                    <table style="border-collapse:collapse;" cellpadding="">

                                        <tr>
                                            <td align="left" style="color:#4EA08F; font-size:14px;">
                                                @if($setting->gst_display == 1)
                                                <strong style="text-align:left; display:inline-block;">GST No: </strong> {!! (!empty($company->gst_number)) ? $company->gst_number : '' !!}
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <td align="left" style="font-size:14px;">
                                                @if($setting->pan_display == 1)
                                                 {!! (!empty($company->pan_number)) ? '<strong style="text-align:left; display:inline-block;">Pan No: </strong>' . $company->pan_number : '' !!}
                                                @endif
                                            </td>
                                        </tr>

                                    </table>

                                </td>

                                <td width="50%" valign="top" align="right">

                                    <table cellpadding="0" style="font-size:14px; border-collapse:collapse;">
                                        <tr>
                                            <td align="right">
                                                <strong style="width:30%; text-align:right;">Office: </strong> {!! (!empty($company->phone))? $company->phone : '---' !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="right">
                                                <strong style="width:30%; text-align:right;">  Mobile :  </strong> {!! (!empty($company->mobile1))? $company->mobile1 : '---' !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="right" style="font-size:17px; color:#4EA08F;"><br/>
                                                <strong style="width:30%; text-align:right;">Invoice No:  {!! $result->invoice_number !!} </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="right" style="font-size:17px; color:#4EA08F;">
                                                <strong style="width:30%; text-align:right;">  Date:
                                                    {!! convertToLocal($result->invoice_date, 'd.m.Y') !!}
                                                    <?php $orderTime = convertToLocal($result->invoice_date, 'Y-m-d H:i:s'); ?>
                                                </strong>
                                            </td>
                                        </tr>
                                    </table>

                                </td>

                            </tr>
                        </table>

                    </td>

                </tr>
            </table>

        </td>
    </tr>
    <tr>
        <td valign="top">

            <table width="100%" bgcolor="#FFFFFF" border="1" cellpadding="5" bordercolor="#e1e1e1" style="border-collapse:collapse; color:#333333; border-top:transparent; border-bottom:transparent; margin-right:-1px !important;">
                <tr >
                    <td width="50%" valign="top">

                        <table cellpadding="0">
                            <tr>
                                <td valign="top">
                                    <strong>Consignee:</strong><br>
                                    {!! $party->salutation !!} {!! $party->account_name !!}, {!! $party->address1 !!} <br>
                                    @if($party->city != "")
                                        {!! $party->city !!}
                                    @endif
                                    @if($party->pincode != "")
                                        {!! $party->state_name !!} {!! ' - ' . $party->pincode !!} <br>
                                    @endif
                                    <br><br><br>
                                    <strong>MOBILE:</strong> {!! $party->mobile1 !!},
                                    <strong>Phone:</strong> {!! $party->phone !!} <br>
                                    <strong>GST NO:</strong> {!! $party->gst_number !!} <br>
                                    <strong>PAN NO:</strong> {!! $party->pan_number !!}<br>
                                </td>
                            </tr>
                        </table>

                    </td>
                    <td width="50%" style="padding:0px;">
                        <table width="100%" border="1" cellpadding="5" bordercolor="#e1e1e1" style="padding:10px; border-collapse:collapse; color:#333333; border-left:transparent; margin-top:-2px; margin-bottom:-1px; ">
                            @if($company->state_name != "")
                                <tr>
                                    <td width="35%" bgcolor="#f3f3f3"><strong>Place of Supply: </strong></td>
                                    <td width="65%" style="border-right:transparent !important;"> {!! $company->state_name  !!} ({!! paddingLeft($company->state_digit_code) !!}) </td>
                                </tr>
                            @endif
                            @if($result->order_number != "" || $result->order_date != "")
                                <tr>
                                    <td width="35%" bgcolor="#f3f3f3"><strong>Order Number: </strong></td>
                                    <td width="65%" style="border-right:transparent !important;"> {!!  $result->order_number  !!} </td>
                                </tr>
                                <tr>
                                    <td width="35%" bgcolor="#f3f3f3"><strong>Order Date: </strong></td>
                                    <td width="65%" style="border-right:transparent !important;"> {!! dateFormat('d.m.Y', $result->order_date) !!} </td>
                                </tr>
                            @endif

                            @if($result->cash_credit != "")
                            <tr>
                                <td width="35%" bgcolor="#f3f3f3"><strong>Mode: </strong></td>
                                <td width="65%" style="border-right:transparent !important;"> {!! cashOrCredit($result->cash_credit) !!} </td>
                            </tr>
                            @endif
                            @if($result->through != "")
                            <tr >
                                <td width="35%" bgcolor="#f3f3f3"><strong>Through:</strong> </td>
                                <td width="65%"> {!! $result->through !!} </td>
                            </tr>
                            @endif

                            @if($result->carriage != "")
                                <tr >
                                    <td width="35%" bgcolor="#f3f3f3"><strong>Transport :</strong> </td>
                                    <td width="65%"> {!! $result->carriage !!} </td>
                                </tr>
                            @endif
                            @if($result->dispatch_to != "")
                            <tr >
                                <td width="35%" bgcolor="#f3f3f3"><strong> Dispatch To :</strong> </td>
                                <td width="65%">  {!! $result->dispatch_to !!}  </td>
                            </tr>
                            @endif
                            @if($result->private_mark != "")
                                <tr >
                                    <td width="35%" bgcolor="#f3f3f3"><strong> Dispatch To :</strong> </td>
                                    <td width="65%">  {!! $result->private_mark !!}  </td>
                                </tr>
                            @endif
                            @if($result->freight != "")
                                <tr >
                                    <td width="35%" bgcolor="#f3f3f3"><strong> Freight :</strong> </td>
                                    <td width="65%">  {!! ($result->freight > 0) ? 'Paid' : 'To Pay' !!}  </td>
                                </tr>
                            @endif
                            @if($result->no_of_cases != "")
                                <tr >
                                    <td width="35%" bgcolor="#f3f3f3"><strong> No. of CASES :</strong> </td>
                                    <td width="65%">  {!! $result->no_of_cases !!}  </td>
                                </tr>
                            @endif
                            @if($result->vehicle_no != "")
                                <tr >
                                    <td width="35%" bgcolor="#f3f3f3"><strong> Vehicle No :</strong> </td>
                                    <td width="65%">  {!! $result->vehicle_no !!}  </td>
                                </tr>
                            @endif
                            <tr>
                                <td width="35%" bgcolor="#f3f3f3"><strong>&nbsp; </strong></td>
                                <td width="65%" style="border-right:transparent !important;"> &nbsp; </td>
                            </tr>
                            {{--<tr >
                                <td width="35%" bgcolor="#f3f3f3"><strong>Dispatch Per :</strong> </td>
                                <td width="65%">  NEW PUNJAB LOGISTICS  </td>
                            </tr>--}}


                        </table>
                    </td>

                </tr>
            </table>

        </td>
    </tr>

    <tr>
        <td valign="top">

            <table width="100%" bgcolor="#FFFFFF" border="1" cellpadding="4" bordercolor="#e5e5e5" style="border-style:solid; border-collapse:collapse; color:#333333; margin-top:-1px;">
                <thead>
                <tr align="left" style="font-size:14px;">
                    <th width="5%" bgcolor="#f3f3f3"> S.NO. </th>
                    <th width="40%" bgcolor="#f3f3f3"> DESCRIPTION OF GOODS </th>
                    <th width="5%" bgcolor="#f3f3f3"> HSN </th>
                    <th width="10%" bgcolor="#f3f3f3"> QUANTITY </th>
                    <th width="10%" bgcolor="#f3f3f3"> UNIT </th>
                    <th width="10%" bgcolor="#f3f3f3"> RATE </th>
                    @if($result->sale == 1)
                        <th width="10%" bgcolor="#f3f3f3"> CGST% </th>
                        <th width="10%" bgcolor="#f3f3f3"> SGST% </th>
                    @elseif($result->sale == 2)
                        <th width="10%" bgcolor="#f3f3f3"> IGST% </th>
                    @endif
                    <th width="10%" bgcolor="#f3f3f3"	> AMOUNT </th>
                </tr>
                </thead>
                <?php $i = $PageBreak = 1; $subTotal = 0; $totalQuantity = $cgstAmount = $sgstAmount = $igstAmount = 0;
                $totalRecords = count($orderItems); $addBr = 18;
                ?>
                @foreach($orderItems as $detail)
                <tr style="border-bottom:transparent;" >
                    <th width="5%">  {!! $i++ !!}. </td>
                    <td width="40%"> {!! $detail->product_name !!}</td>
                    <td width="5%"> {!! $detail->hsn_code !!} </td>
                    <td width="10%"> {!! $detail->quantity !!} </td>
                    <td width="10%"> {!! $detail->unit !!} </td>
                    <td width="10%">
                        <?php $price = ($detail->manual_price > 0) ? $detail->manual_price : $detail->price; ?>
                        {!! numberFormat($price) !!}</td>
                    @if($result->sale == 1)
                        <td width="10%"> {!! $detail->cgst !!}% </td>
                        <td width="10%"> {!! $detail->sgst !!}% </td>
                    @elseif($result->sale == 2)
                        <td width="10%"> {!! $detail->igst !!}% </td>
                    @endif
                    <td width="10%"> <?php
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

                @for($i = 1; $i <= (16 - $totalRecords); $i++)
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




            </table>

        </td>
    </tr>
    <tr>
        <td valign="top">
            <table width="100%" cellpadding="4" border="1" style="border-collapse:collapse; font-size:13px; margin-top:-1px;" bordercolor="#e5e5e5">
                <tr align="right">
                    <td width="85%" style="background-color:#f3f3f3;font-size: 12px;"> <strong>TOTAL </strong></td>
                    <td width="15%"> <strong>{!! numberFormat(getRoundedAmount($subTotal)) !!}</strong> </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td valign="top">

            <table width="100%" bgcolor="#FFFFFF" border="1" cellpadding="0" bordercolor="#e5e5e5" style="padding:10px; border-style:solid; border-collapse:collapse; color:#333333; margin-top:-1px;">
                <tr >
                    <td width="50%" style="line-height:15px; padding:0px 10 px;" valign="top">

                        <table width="100%" align="left" cellpadding="0" style="margin-bottom:5px;">
                            <tr>
                                <th width="25%" align="left">Bank Detail :</th>
                                <td align="left">{!! $bank->name !!}</td>
                            </tr>
                            <tr>
                                <th align="left">A/C Number:</th>
                                <td align="left">{!! $bank->account_number !!}</td>
                            </tr>
                            <tr>
                                <th align="left">IFS CODE:</th>
                                <td align="left">{!! $bank->bsb_number !!}</td>
                            </tr>
                        </table>

                    </td>
                    <td width="50%" style="padding:0px;" valign="top">
                        <table width="100%" cellpadding="5" border="1" bordercolor="#e5e5e5" style="border-collapse:collapse;
          border-top:transparent !important; border-left:transparent !important; margin-top:-1px; margin-bottom:-1px; font-size:13px;">
                            @if($result->sale == 1)
                                <tr align="right">
                                    <th width="70%"><strong> CGST AMOUNT :</strong></th>
                                    <td width="30%">  {!! numberFormat(getRoundedAmount($cgstAmount)) !!}  </td>
                                </tr>
                                <tr align="right">
                                    <th width="70%"><strong> SGST AMOUNT :</strong></th>
                                    <td width="30%">  {!! numberFormat(getRoundedAmount($sgstAmount)) !!}   </td>
                                </tr>
                            @elseif ($result->sale == 2)
                                <tr align="right">
                                    <th width="70%"><strong> SGST AMOUNT :</strong></th>
                                    <td width="30%">  {!! numberFormat(getRoundedAmount($igstAmount)) !!} </td>
                                </tr>
                            @endif

                            <tr align="right">
                                <th width="70%"><strong>Freight :</strong></th>
                                <td width="30%" > {!! numberFormat($result->freight) !!} </td>
                            </tr>
                            <tr align="right">
                                <th width="70%"><strong> Other Charges: </strong></th>
                                <td width="30%"> {!! numberFormat($result->other_charges) !!}  </td>
                            </tr>
                            <tr align="right">
                                <th width="70%"><strong> Round Off: </strong></th>
                                <td width="30%"> {!! ($result->round_off < 0) ? '-' : ' ' !!}{!! numberFormat(substr($result->round_off, 1)) !!} 	 </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

        </td>
    </tr>

    <tr>
        <td valign="top">
            <table width="100%" cellpadding="4" border="1" style="border-collapse:collapse; font-size:12px; margin-top:-1px;" bordercolor="#e5e5e5">
                <tr align="left">
                    <td width="50%" bgcolor="#f3f3f3" style=" font-size:11px;">&nbsp;

                    </td>
                    <td width="50%" style="padding:0px;">
                        <table width="100%" border="1" cellpadding="5" bordercolor="#e5e5e5" style="border-collapse:collapse; color:#333333; border-left:transparent; margin-top:-1px; margin-bottom:-1px; font-size:13px; ">
                            <tr>
                                <td width="70%" style="background-color:#f3f3f3;font-size: 15px;"> <strong>TOTAL AMOUNT </strong></td>
                                <td width="30%" style="border-right:transparent;font-size: 15px;" align="right"> <strong>
                                        <?php $netAmount = getRoundedAmount(($subTotal + $cgstAmount + $sgstAmount + $igstAmount + $result->freight + $result->other_charges) + $result->round_off); ?>
                                        {!! numberFormat($netAmount) !!}</strong> </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td valign="top">
            <table width="100%" cellpadding="5" border="1" style="border-collapse:collapse; margin-top:-1px;" bordercolor="#e5e5e5">
                <tr align="left">
                    <td width="30%"> <strong>Amount in words : </strong></td>
                    <td width="70%" style="font-size: 0.9em;text-transform: uppercase;font-weight: bold;"> {!! string_manip(numberToWord(getRoundedAmount($netAmount))) !!} only </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td valign="top">

            <table width="100%" cellpadding="5" border="1" bordercolor="#e5e5e5" style="border-collapse:collapse;
     margin-bottom:-1px; border-top:transparent; font-size:14px;">
                <tr>
                    <td colspan="8"><strong> Tax Summary: </strong></td>
                </tr>
                <?php
                if($result->sale == 1) {
                    $gst = $taxes['cgst'];
                } elseif($result->sale == 2) {
                    $gst = $taxes['igst'];
                }
                $totalCgstTax = $totalSgstTax = 0;
                ?>
                <tr align="left" valign="top">
                    <th bgcolor="#f3f3f3" style="border-bottom:transparent;"></th>
                    @foreach($gst as $values)
                    <th bgcolor="#f3f3f3">(%) </th>
                    <th bgcolor="#f3f3f3">Amount</th>
                    @endforeach
                    <th bgcolor="#f3f3f3" style="border-right:transparent !important;">Total</th>
                </tr>
                @foreach($taxes as $label => $tax)
                    @if(count($tax) > 0)
                        <tr style="border-bottom:transparent;">
                            <th bgcolor="#f3f3f3">{!! strtoupper($label) !!} </th>
                            <?php $totalTax = 0; ?>
                            @foreach($tax as $key => $amount)
                                <td>{!! trim($key, "'") !!}</td>
                                <td>{!! numberFormat(getRoundedAmount($amount)) !!} </td>
                                <?php $totalTax +=$amount; ?>
                            @endforeach
                            <td style="margin-right:transparent !important;">{!! numberFormat(getRoundedAmount($totalTax)) !!} </td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </td>
    </tr>
    @if($setting->footer_required == 1)
    <tr>
        <td valign="top">
            <table width="100%" cellpadding="5" border="1" style="border-collapse:collapse; margin-top:0px; font-size:14px;" bordercolor="#e5e5e5">
                <tr align="left">
                    <td width="50%"> <strong style="display:block;">TERMS :</strong>
                        <span style="font-size: 0.85em;padding-bottom: 0;">{!! nl2br($setting->terms) !!} </span>
                    </td>
                    <td width="50%" align="right">
                        <span style="display:block; vertical-align:top; font-size:20px;">For <strong style="font-size:20px; text-transform:uppercase;"> {!! $company->company_name !!} </strong></span><br><br>
                        <span style="display:block; vertical-align:top;">
                            @if($setting->auth_signature_show == 1)
                                {!! $setting->auth_text !!}
                            @endif </span><br><br>
                        <span style="display:block; vertical-align:top; text-align:left;">
                            @if($setting->customer_signature_show == 1)
                                {!! $setting->signature_text !!}
                            @endif </span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    @endif
    <tr>
        <td valign="top">
            <table width="100%" bgcolor="#4EA08F" border="1" cellspacing="10" cellpadding="5" bordercolor="#4EA08F" style="border-style:solid; border-top:1px solid transparent; border-collapse:collapse; color:#ffffff; font-size:14px;font-weight: bold;;">
                <tr>
                    <td width="50%" align="left"> This is computerized generated invoice </td>
                    <td width="50%" align="right"> Powered by cyberconsorts.com </td>
                </tr>
            </table>
        </td>
    </tr>

</table>
<!--Invoice Layout-->
