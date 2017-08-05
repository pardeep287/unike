<!DOCTYPE html>
<html>
<head>
    <style type="text/css">

        @if($setting->invoice_format == 1)
            body{
                background:none;
                font-family: arial;
                font-size: 13px;
                -webkit-print-color-adjust: exact;
                margin: 0 0 0 30px;
            }

            @media all {
                .page-break, .hide  { display: none; }
            }
            @page
            {
                /*size: 8.7in 11in;*/
                /*margin : 15mm 15mm 25mm 15mm;*/
                page-break-inside: avoid;
                page-break-after: always;
            }
        @elseif($setting->invoice_format == 2)
            body{
                -webkit-print-color-adjust: exact;
                background:#ffffff;
                font-size:15px;
                margin:0px;
                text-align:center;
                padding:0px;
                font-family:Arial;

            }

            @media all {
                .page-break, .hide  { display: none; }
            }
            @page
            {
                /*size: 8.7in 11in;*/
                /*margin : 15mm 15mm 25mm 15mm;*/
                page-break-inside: avoid;
                page-break-after: always;
            }
        @elseif($setting->invoice_format == 3)
            @media all {
                .page-break, .hide {
                    display: none;
                }

                body {
                    margin-top: 50mm;
                    margin-bottom: 50mm;
                    margin-left: 0mm;
                    margin-right: 0mm
                }

                body, h1, h2, h3, h4, h5, h6, td, th, div {
                    font-size: 12px !important;
                    font-family: geneva, arial, helvetica, sans-serif;
                }
            }

            @media print {
                body, h1, h2, h3, h4, h5, h6, td, th, div {
                    font-size: x-small !important;
                    font-family: geneva, arial, helvetica, sans-serif;
                    line-height: 10px !important;
                }

                table, tbody, thead, tr, td, th {
                    /*padding: 0;*/
                }
            }

            @page {
                size: A5 portrait;
                margin: 5mm 5mm 5mm 0mm;
                page-break-inside: avoid;
                page-break-after: always;
            }
        @endif
    </style>
</head>
<body onload="window.print()" style="background:none;font-family: arial;font-size: 13px;-webkit-print-color-adjust: exact;margin: 0 0 0 30px;">
    @if(isset($pdf))
        @include('invoice.invoice-common'. $setting->invoice_format, ['copy' => 1])
    @else
        @foreach($printOptions as $copy)
            @include('invoice.invoice-common'. $setting->invoice_format, ['copy' => $copy])
            <div style="page-break-before: always;"></div>
        @endforeach
    @endif
</body>
</html>