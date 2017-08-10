@extends('layouts.admin-new')
@section('content')
	<div id="page-wrapper">
		<div class="row topheading-row">
			<div class="col-lg-6 col-md-6 col-sm-9 col-xs-12">
				<h1 class="page-header margintop10">   {!! string_manip(lang('report.report'), 'UCW') !!} - {!! lang('report.cost_report') !!} </h1>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-3 col-xs-12">
				<a class="btn btn-sm btn-default pull-right margintop10 _back" href="javascript:void(0)"> <i class="fa fa-arrow-left fa-fw"></i> {!! lang('common.back') !!} </a>
			</div>
			<div class="clearfix"></div>
		</div>

		<!-- /.col-lg-12 -->
		{{-- for message rendering --}}
		@include('layouts.messages')
		<div class="col-md-12">
			{!! Form::open(array('method' => 'POST', 'route' => array('report.cost-report-paginate'), 'id' => 'ajaxForm')) !!}
			<div class="row">
				<div class="col-sm-3">
					<div class="form-group">
						{!! Form::label('product', lang('products.product'), array('class' => 'control-label')) !!}
						{!! Form::select('product', $products, (isset($inputs['product'])) ? $inputs['product'] : '', array('class' => 'form-control padding0 select2')) !!}
					</div>
				</div>

				<div class="col-sm-3">
					<div class="form-group">
						{!! Form::label('customer', lang('customer.customer_name'), array('class' => 'control-label')) !!}
						{!! Form::select('customer', $customer, (isset($inputs['customer'])) ? $inputs['customer'] : '', array('class' => 'form-control padding0 select2')) !!}
					</div>
				</div>

				<div class="col-md-2">
					<div class="form-group">
						{!! Form::label('from_date', lang('report.from_date'), array('class' => 'control-label')) !!}
						{!! Form::text('from_date', null, array('class' => 'form-control date-picker', 'required' => 'true', 'placeholder' => lang('report.from_date'))) !!}
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						{!! Form::label('to_date', lang('report.to_date'), array('class' => 'control-label')) !!}
						{!! Form::text('to_date', null, array('class' => 'form-control date-picker', 'required' => 'true', 'placeholder' =>  lang('report.to_date'))) !!}
					</div>
				</div>

				<div class="col-sm-2 margintop20">
					<div class="form-group">
						{!! Form::hidden('form-search', 1) !!}
						{!! Form::submit(lang('report.filter'), array('class' => 'btn btn-primary', 'title' => lang('common.filter'))) !!}
						<a href="{!! route('report.cost-report') !!}" class="btn btn-primary" title="{!! lang('common.reset_filter') !!}"> {!! lang('report.reset_filter') !!}</a>
					</div>
				</div>
			</div>
			{!! Form::close() !!}
		</div>
		<div class="col-md-12">
			<!-- start: BASIC TABLE PANEL -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-external-link-square"></i> &nbsp;
					{!! lang('report.cost_report') !!}
                    <?php
						$linkCss = [
							asset('assets/css/bootstrap.min.css'),
							asset('assets/css/template.css')
						];
                    ?>
					{{-- Reporting Section --}}
					<div class="pull-right hidden">
						<a href="{!! route('cost-report-generate-pdf') !!}" class="btn btn-danger btn-xs" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
						<a href="{!! route('cost-report-generate-excel') !!}" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-file-excel-o"></i></a>
						<a href="javascript:void(0)" onclick="return reportPrint('p-report','{!! implode('|', $linkCss) !!}')" class="pull-right btn btn-primary btn-xs fa fa-print"></a>
					</div>

				</div>
				<div class="panel-body padding0" id="p-report">
					<table id="paginate-load" data-route="{{ route('report.cost-report-paginate') }}" class="table table-responsive table-hover table-bordered clearfix margin0 col-md-12 padding0 table-report1">
					</table>
				</div>
			</div>
			<!-- end: BASIC TABLE PANEL -->
		</div>
	</div>
@stop