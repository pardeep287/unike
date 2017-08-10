@extends('layouts.admin-new')
@section('content')
<div id="page-wrapper">
	<div class="row topheading-row">
		<div class="col-lg-6 col-md-6 col-sm-9 col-xs-12">
			<h1 class="page-header margintop10">   {!! string_manip(lang('report.report'), 'UCW') !!} - {!! lang('report.account_group_statement') !!} </h1>
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
		{!! Form::open(array('method' => 'POST', 'route' => array('report.account-group-statement-paginate'), 'id' => 'ajaxForm')) !!}
		<div class="row">

			<div class="col-sm-2">
				<div class="form-group">
					{!! Form::label('account_group', lang('account_group.account_group'), array('class' => 'control-label')) !!}
					{!! Form::select('account_group', ['' => ''] + getDebtorCreditorId(), (isset($inputs['account_group'])) ? $inputs['account_group'] : '', array('class' => 'form-control padding0 select2')) !!}
				</div>
			</div>

			<div class="col-sm-2 hidden">
				<div class="form-group">
					{!! Form::label('report_type', lang('report.report_type'), array('class' => 'control-label')) !!}
					{!! Form::select('report_type', getReportType(), (isset($inputs['report_type'])) ? $inputs['report_type'] : '', array('class' => 'form-control padding0 select2 report_type')) !!}
				</div>
			</div>

			<div class="col-md-2 date hidden">
				<div class="form-group">
					{!! Form::label('from_date', lang('report.from_date'), array('class' => 'control-label')) !!}
					{!! Form::text('from_date', null, array('class' => 'form-control date-picker from_date', 'placeholder' => lang('report.from_date'))) !!}
				</div>
			</div>

			<div class="col-md-2 date hidden">
				<div class="form-group">
					{!! Form::label('to_date', lang('report.to_date'), array('class' => 'control-label')) !!}
					{!! Form::text('to_date', null, array('class' => 'form-control date-picker to_date', 'placeholder' =>  lang('report.to_date'))) !!}
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

			<div class="col-sm-2 margintop20">
				<div class="form-group">
					{!! Form::hidden('form-search', 1) !!}
					{!! Form::submit(lang('report.filter'), array('class' => 'btn btn-primary', 'title' => lang('common.filter'))) !!}
					<a href="{!! route('report.account-group-statement') !!}" class="btn btn-primary" title="{!! lang('common.reset_filter') !!}"> {!! lang('report.reset_filter') !!}</a>
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
				{!! lang('report.account_group_statement') !!}
				{{-- Reporting Section --}}
				<div class="pull-right hidden">
                    <?php
						$linkCss = [
							asset('assets/css/bootstrap.min.css'),
							asset('assets/css/template.css')
						];
                    ?>
					<a href="javascript:void(0)" onclick="return reportPrint('p-report','{!! implode('|', $linkCss) !!}')" class="pull-right btn btn-primary btn-xs hidden fa fa-print"></a>
				</div>
				{{-- End of the reporting section --}}
			</div>
			<div class="panel-body padding0" id="p-report">
				<table id="paginate-load" data-route="{!! route('report.account-group-statement-paginate') !!}" class="table table-responsive table-hover table-bordered clearfix margin0 col-md-12 padding0 table-report1">
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