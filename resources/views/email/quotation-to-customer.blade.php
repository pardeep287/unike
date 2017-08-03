<div style="font-family: Arial, sans-serif; width: 600px; margin: auto; background: #fff; border:1px solid #dddddd; margin-top: 10px; ">
    <!-- Header -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr align="center">
            <td style="padding: 6px 0px; font-size:22px;"> <img style="max-width: 55px; vertical-align: middle;" class="" src="{{ \URL:: asset('assets/images/SF_logo.png') }}" alt="SALE FORCE" /></td>
        </tr>
    </table>
    <!-- Middle Section  -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%">

        <tr>
            <td height="1" colspan="2" bgcolor="#dddddd"></td>
        </tr>

        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td style="font-size: 13px; padding: 0px 16px; color: #333333;" align="left">
                <p>
                    {!! lang('email.hello') !!} {!! $customer->customer_name !!},
                </p>
                <p>
                    {!! lang('email.quotation_order') !!}
                </p>
                @if(trim($customer->message) != '')
                    <p>
                        {!! lang('email.note') !!}: <br />
                        {!! string_manip($customer->message, 'UC') !!}
                    </p>
                @endif
                <br/>
                <br/>
                <p style="text-align: left; font-size: 1.2em;margin: 0;line-height:25px;font-weight:bold; padding-top:10px;">
                   {!! lang('email.regards_detail') !!}
                </p>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
    </table>
    <!-- End Middle Section  -->
    <!-- Footer -->
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="color: #fff; background: #777777; font-size: 13px;">
        <tr>
            <td align="center" height="40px">
                {!! lang('email.copyright') !!}
                <!-- end footer -->
            </td>
        </tr>

    </table>
</div>