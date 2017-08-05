@extends('layouts.admin-new')
@section('content')
<div id="page-wrapper">
    <div class="col-xs-12">
        <a href="{{ route('customer-invoice.invoice-print', [$id]) }}" target="_blank" class="btn btn-default pull-right"><i class="fa fa-print"></i> Print</a>
        @include('sale-invoice.invoice-common')
    </div>
    <div class="clearfix">
    </div>
</div>
@stop