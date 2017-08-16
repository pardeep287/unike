@extends('layouts.admin')
@section('content')
<div id="page-wrapper">
	<div class="row topheading-row">
		<div class="col-lg-6 col-md-6 col-sm-9 col-xs-12">
			<h1 class="page-header margintop10"> {!! lang('user.users') !!} </h1>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-3 col-xs-12">
			@if(hasMenuRoute('user.create') || isAdmin() || isSuperAdmin())
				<a class="btn btn-sm btn-danger pull-right margintop10 marginbottom10" href="{{ route('user.create') }}"> <i class="fa fa-plus fa-fw"></i> {!! lang('common.create_heading', lang('user.user')) !!} </a>
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
					{!! lang('user.users_list') !!}
				</div>
				<div class="panel-body">
					<form id="serachable" action="{{ route('user.action') }}" method="post">
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 text-right pull-right padding0 marginbottom10">
							{!! lang('common.per_page') !!}: {!! Form::select('name', ['20' => '20', '40' => '40', '100' => '100', '200' => '200', '300' => '300'], '20', ['id' => 'per-page']) !!}
						</div>
						<table id="paginate-load" data-route="{{ route('user.paginate') }}" class="table table-responsive  table-hover clearfix margin0 col-md-12 padding0 table-fullbox">
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
