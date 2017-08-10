@extends('layouts.admin-new')
@section('content')
	<div id="page-wrapper">
		<div class="row topheading-row">
			<div class="col-lg-6 col-md-6 col-sm-9 col-xs-12">
				<h1 class="page-header margintop10">   {!! string_manip(lang('report.report'), 'UCW') !!} - {!! lang('report.bank_statement_report') !!} </h1>
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
			{!! Form::open(array('method' => 'POST', 'route' => array('report.bank-statement-report-paginate'), 'id' => 'ajaxForm')) !!}
			<div class="row">
				<div class="col-sm-2">
					<div class="form-group">
						{!! Form::label('group', lang('group_head.account_detail'), array('class' => 'control-label')) !!}
						{!! Form::select('group', $groups, (isset($inputs['group'])) ? $inputs['group'] : '', array('class' => 'form-control padding0 select2')) !!}
					</div>
				</div>

				<div class="col-sm-2">
					<div class="form-group">
						{!! Form::label('bank', lang('bank.bank'), array('class' => 'control-label')) !!}
						{!! Form::select('bank', $banks, (isset($inputs['bank'])) ? $inputs['bank'] : '', array('class' => 'form-control padding0 select2')) !!}
					</div>
				</div>

				<div class="col-sm-2">
					<div class="form-group">
						{!! Form::label('report_type', lang('report.report_type'), array('class' => 'control-label')) !!}
						{!! Form::select('report_type', getReportType(), (isset($inputs['report_type'])) ? $inputs['report_type'] : '', array('class' => 'form-control padding0 select2 report_type')) !!}
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
						{!! Form::select('year', getYear(2010, date('Y')), (isset($inputs['year'])) ? $inputs['year'] : '', array('class' => 'form-control padding0 select2')) !!}
					</div>
				</div>

				<div class="col-sm-2 margintop20">
					<div class="form-group">
						{!! Form::hidden('form-search', 1) !!}
						{!! Form::submit(lang('report.filter'), array('class' => 'btn btn-primary', 'title' => lang('common.filter'))) !!}
						<a href="{!! route('report.bank-statement-report') !!}" class="btn btn-primary" title="{!! lang('common.reset_filter') !!}"> {!! lang('report.reset_filter') !!}</a>
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
					{!! lang('report.bank_statement_report') !!}
				</div>
				<div class="panel-body padding0">
					<table id="paginate-load" data-route="{{ route('report.bank-statement-report-paginate') }}" class="table table-responsive table-hover table-bordered clearfix margin0 col-md-12 padding0 table-report1">
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
				}
				else if(report_type == '2')
				{
					$(".date, .year").addClass("hidden");
					$(".month").removeClass("hidden");
				}
				else if(report_type == '3')
				{
					$(".month, .date").addClass("hidden");
					$(".year").removeClass("hidden");
				}
			});
		});
	</script>
@stop