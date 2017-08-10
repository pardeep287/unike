@extends('layouts.admin')
@section('content')
	<div id="page-wrapper">
		<div class="row topheading-row">
			<div class="col-lg-6 col-md-6 col-sm-9 col-xs-12">
				<h1 class="page-header margintop10">
				{!! lang('report.sale_report') !!}
				</h1>
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
			{!! Form::open(array('method' => 'POST', 'route' => array('report.sale-report-paginate'), 'id' => 'ajaxForm')) !!}
			<div class="row">

				<div class="col-sm-3">
					<div class="form-group">
						{!! Form::label('customer_id', lang('customer.customer'), array('class' => 'control-label')) !!}
						{!! Form::select('customer_id',$customer, (isset($inputs['customer_id'])) ? $inputs['customer_id'] : '', array('class' => 'form-control padding0 select2')) !!}
					</div>
				</div>

				<div class="col-sm-2">
					<div class="form-group">
						{!! Form::label('report_type', lang('report.report_type'), array('class' => 'control-label')) !!}
						{!! Form::select('report_type', getReportType(), (isset($inputs['report_type'])) ? $inputs['report_type'] : '', array('class' => 'form-control padding0 report_type select2')) !!}
					</div>
				</div>

				<div class="col-md-2 date">
					<div class="form-group">
						{!! Form::label('from_date', lang('report.from_date'), array('class' => 'control-label')) !!}
						{!! Form::text('from_date', null, array('class' => 'form-control date-picker from_date', 'required' => 'true', 'placeholder' => lang('report.from_date'))) !!}
					</div>
				</div>

				<div class="col-md-2 date">
					<div class="form-group">
						{!! Form::label('to_date', lang('report.to_date'), array('class' => 'control-label')) !!}
						{!! Form::text('to_date', null, array('class' => 'form-control date-picker to_date', 'required' => 'true', 'placeholder' =>  lang('report.to_date'))) !!}
					</div>
				</div>

				<div class="col-md-2 hidden month">
					<div class="form-group">
						{!! Form::label('month', lang('report.month'), array('class' => 'control-label')) !!}
						{!! Form::select('month', getMonths(), (isset($inputs['month'])) ? $inputs['month'] : '', array('class' => 'form-control padding0 select2')) !!}
					</div>
				</div>

				<div class="col-md-2 hidden year">
					<div class="form-group">
						{!! Form::label('year', lang('report.year'), array('class' => 'control-label')) !!}
						{!! Form::select('year', getYear(2016, date('Y')), (isset($inputs['year'])) ? $inputs['year'] : '', array('class' => 'form-control padding0 select2')) !!}
					</div>
				</div>

				<div class="col-sm-3 margintop20">
					<div class="form-group">
						{!! Form::hidden('form-search', 1) !!}
						{!! Form::submit(lang('report.filter'), array('class' => 'btn btn-info', 'title' => lang('common.filter'))) !!}
						<a href="{!! route('report.sale-report') !!}" class="btn btn-success" title="{!! lang('common.reset_filter') !!}"> {!! lang('report.reset_filter') !!}</a>
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
					{!! lang('report.sale_report') !!}

					{{-- Reporting Section --}}
					<div class="pull-right hidden">
						<?php
							$linkCss = [
								asset('assets/css/bootstrap.min.css'),
								asset('assets/css/template.css')
							];
						?>
						{{--<a href="{!! route('sale-report-generate-pdf') !!}" class="btn btn-danger btn-xs" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
						<a href="{!! route('sale-report-generate-excel') !!}" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-file-excel-o"></i></a>--}}
					</div>
					<a href="javascript:void(0)" onclick="return reportPrint('p-report','{!! implode('|', $linkCss) !!}')" class="pull-right btn btn-primary btn-xs fa fa-print"></a>
					{{-- End of the reporting section --}}
				</div>
				<div class="panel-body padding0" id="p-report">
					<table id="paginate-load" data-route="{{ route('report.sale-report-paginate') }}" class="table table-responsive table-hover table-bordered clearfix margin0 col-md-12 padding0 table-report1">
					</table>
				</div>
			</div>
			<!-- end: BASIC TABLE PANEL -->
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".report_type").change(function(){
				var report_type = $(this).val();

				if(report_type == '1')
				{
					$(".month, .year").addClass("hidden");
					$(".date").removeClass("hidden");
					$(".from_date").attr("required", "required");
					$(".to_date").attr("required", "required");
				}
				else if(report_type == '2')
				{
					$(".date, .year").addClass("hidden");
					$(".month").removeClass("hidden");
					$(".from_date").removeAttr("required");
					$(".to_date").removeAttr("required");
				}
				else if(report_type == '3')
				{
					$(".month, .date").addClass("hidden");
					$(".year").removeClass("hidden");
					$(".from_date").removeAttr("required");
					$(".to_date").removeAttr("required");
				}
			});
		});
	</script>
@stop