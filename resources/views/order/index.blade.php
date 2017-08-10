@extends('layouts.admin')
@section('content')
<div id="page-wrapper">
	<div class="row topheading-row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h1 class="page-header margintop10"> {!! lang('order.order') !!} </h1>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 hide">
			@if(hasMenuRoute('order.create') || isAdmin())
				<a class="btn btn-sm btn-danger pull-right margintop10 marginbottom10" href="{!! route('order.create') !!}">
					<i class="fa fa-plus fa-fw"></i>
					{!! lang('common.create_heading', lang('order.order')) !!}
				</a>
			@endif
		</div>
		<!-- /.col-lg-12 -->
	</div>
    {{-- for message rendering --}}
    @include('layouts.messages')
    <div class="row">
		<div class="col-md-12 hide">
			{!! Form::open(array('method' => 'POST', 'route' => array('order.paginate'), 'id' => 'ajaxForm')) !!}
			<div class="row">
				<div class="ol-lg-2 col-md-2 col-sm-12 col-xs-12 ">
					<div class="form-group">
						{!! Form::label('order_date', lang('order.order_date'), array('class' => 'control-label')) !!}
						{!! Form::text('order_date', (isset($inputs['order_date'])) ? $inputs['order_date'] : '', array('class' => 'form-control padding0 date-picker')) !!}
					</div>
				</div>

				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 margintop20 ">
					<div class="form-group">
						{!! Form::hidden('form-search', 1) !!}
						{!! Form::submit(lang('common.filter'), array('class' => 'btn btn-danger')) !!}
						<a href="{!! route('order.index') !!}" class="btn btn-danger"> {!! lang('common.reset_filter') !!}</a>
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
				{!! lang('order.order_list') !!}
			</div>
			<div class="panel-body">
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 text-right pull-right padding0 marginbottom10">
					{!! lang('common.per_page') !!}: {!! Form::select('name', ['20' => '20', '40' => '40', '100' => '100', '200' => '200', '300' => '300'], '20', ['id' => 'per-page']) !!}
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding0 marginbottom10">
					{!! Form::hidden('page', 'search') !!}
					{!! Form::hidden('_token', csrf_token()) !!}
					{!! Form::text('name', null, array('class' => 'form-control live-search', 'placeholder' => lang('common.search_heading', lang('order.order')))) !!}
				</div>
				<table id="paginate-load" data-route="{{ route('order.paginate') }}" class="table table-hover table-responsive clearfix margin0 col-md-12 padding0 table-fullbox cust-order-table">
				</table>
			</div>
		</div>
		<!-- end: BASIC TABLE PANEL -->
		</div>	
	</div>
</div>
<!-- /#page-wrapper -->
@stop
