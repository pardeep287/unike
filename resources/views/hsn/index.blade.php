@extends('layouts.admin')
@section('content')
<div id="page-wrapper">
	<div class="row topheading-row">
		<div class="col-lg-6 col-md-6 col-sm-9 col-xs-12">
			<h1 class="page-header margintop10"> {!! lang('hsn.hsn') !!} </h1>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-3 col-xs-12">

			@if(isAdmin())
				<a class="btn btn-sm btn-danger pull-right margintop10 marginbottom10" href="{{ route('hsn.create') }}"> <i class="fa fa-plus fa-fw"></i> {!! lang('common.create_heading', lang('hsn.hsn'))  !!} </a>
			@endif
		</div>

		<!-- /.col-lg-12 -->
	</div>

	{{-- for message rendering --}}
	@include('layouts.messages')
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-sm-12">
			<!-- start: BASIC TABLE PANEL -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-external-link-square"></i> &nbsp;
					{!! lang('hsn.hsn_list') !!}
				</div>
				<div class="panel-body">
					<form id="serachable" action="{{ route('hsn.action') }}" method="post">
						<div class="col-md-3 text-right pull-right padding0 marginbottom10">
							{!! lang('common.per_page') !!}: {!! Form::select('name', ['20' => '20', '40' => '40', '100' => '100', '200' => '200', '300' => '300'], '20', ['id' => 'per-page']) !!}
						</div>
						<div class="col-md-3 padding0 marginbottom10">
							{!! Form::hidden('page', 'search') !!}
							{!! Form::hidden('_token', csrf_token()) !!}
							{!! Form::text('name', null, array('class' => 'form-control live-search', 'placeholder' => 'Search Hsn Code by name')) !!}
						</div>
						<table id="paginate-load" data-route="{{ route('hsn.paginate') }}" class="table table-responsive  table-hover clearfix margin0 col-md-12 padding0 table-fullbox">
						</table>
					</form>
				</div>
			</div>
			<!-- end: BASIC TABLE PANEL -->
		</div>
	</div>
	<!-- /#page-wrapper -->
</div>
@stop
