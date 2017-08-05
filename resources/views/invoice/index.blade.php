@extends('layouts.admin')
@section('content')
<div id="page-wrapper">
	<div class="row topheading-row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h1 class="page-header margintop10"> {!! lang('invoice.invoice') !!} </h1>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			@if(hasMenuRoute('invoice.create') || isAdmin())
				<a class="btn btn-sm btn-danger pull-right margintop10 marginbottom10" href="{!! route('invoice.create') !!}">
					<i class="fa fa-plus fa-fw"></i>
					{!! lang('common.create_heading', lang('invoice.invoice')) !!}
				</a>
			@endif
		</div>
		<!-- /.col-lg-12 -->
	</div>
    {{-- for message rendering --}}
    @include('layouts.messages')
    <div class="row">
		<div class="col-md-12">
			{!! Form::open(array('method' => 'POST', 'route' => array('invoice.paginate'), 'id' => 'ajaxForm')) !!}
			<div class="row">
				<div class="ol-lg-2 col-md-2 col-sm-12 col-xs-12">
					<div class="form-group">
						{!! Form::label('invoice_date', lang('invoice.invoice_date'), array('class' => 'control-label')) !!}
						{!! Form::text('invoice_date', (isset($inputs['invoice_date'])) ? $inputs['invoice_date'] : '', array('class' => 'form-control padding0 date-picker')) !!}
					</div>
				</div>

				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margintop20">
					<div class="form-group">
						{!! Form::hidden('form-search', 1) !!}
						{!! Form::submit(lang('common.filter'), array('class' => 'btn btn-danger')) !!}
						<a href="{!! route('invoice.index') !!}" class="btn btn-danger"> {!! lang('common.reset_filter') !!}</a>
					</div>
				</div>
			</div>
			{!! Form::close() !!}
		</div>
    	<div class="col-md-12">
		<!-- start: BASIC TABLE PANEL -->
		<div class="panel panel-default" style="position: static;">
			<div class="panel-heading">
				<i class="fa fa-external-link-square"></i> &nbsp;
				{!! lang('invoice.invoice_list') !!}
			</div>
			<div class="panel-body">
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 text-right pull-right padding0 marginbottom10">
					{!! lang('common.per_page') !!}: {!! Form::select('name', ['20' => '20', '40' => '40', '100' => '100', '200' => '200', '300' => '300'], '20', ['id' => 'per-page']) !!}
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding0 marginbottom10">
					{!! Form::hidden('page', 'search') !!}
					{!! Form::hidden('_token', csrf_token()) !!}
					{!! Form::text('name', null, array('class' => 'form-control live-search', 'placeholder' => lang('common.search_heading', lang('invoice.invoice')))) !!}
				</div>
				<table id="paginate-load" data-route="{{ route('invoice.paginate') }}" class="table table-hover table-responsive clearfix margin0 col-md-12 padding0 table-fullbox cust-invoice-table">
				</table>
			</div>
		</div>
		<!-- end: BASIC TABLE PANEL -->
		</div>	
	</div>
</div>
<!-- /#page-wrapper -->
@stop
