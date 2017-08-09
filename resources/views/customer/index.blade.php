@extends('layouts.admin')
@section('content')
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
        @if(hasMenuRoute('customer.create') || isSuperAdmin() || isAdmin())
        	<a class="btn btn-sm btn-danger pull-right margintop10" href="{!! route('customer.create') !!}"> <i class="fa fa-plus fa-fw"></i> {!! lang('common.create_heading', lang('customer.customer')) !!} </a>
    	@endif
            <h1 class="page-header margintop10"> {!! lang('customer.customer') !!} </h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

	{{-- for message rendering --}}
    @include('layouts.messages')
    <div class="row">
    	<div class="col-md-12">
		<!-- start: BASIC TABLE PANEL -->
		<div class="panel panel-default" style="position: static;">
			<div class="panel-heading">
				<i class="fa fa-external-link-square"></i> &nbsp;
				{!! lang('customer.customer_list') !!}
			</div>
			<div class="panel-body">
				<form action="{{ route('customer.action') }}" method="post">
				<div class="col-md-3 text-right pull-right padding0 marginbottom10">
					{!! lang('common.per_page') !!}: {!! Form::select('name', ['20' => '20', '40' => '40', '100' => '100', '200' => '200', '300' => '300'], '20', ['id' => 'per-page']) !!}
				</div>
				<div class="col-md-3 padding0 marginbottom10">
					{!! Form::hidden('page', 'search') !!}
					{!! Form::hidden('_token', csrf_token()) !!}
					{!! Form::text('name', null, array('class' => 'form-control live-search', 'placeholder' => 'Search customer by name')) !!}
				</div>
				<table id="paginate-load" data-route="{{ route('customer.paginate') }}" class="table table-hover clearfix margin0 col-md-12 padding0">
				</table>
				</form>
			</div>
		</div>
		<!-- end: BASIC TABLE PANEL -->
		</div>	
	</div>
</div>
<!-- /#page-wrapper -->
@stop
