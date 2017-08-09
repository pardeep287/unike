@extends('layouts.admin')
@section('content')
<div id="page-wrapper">
    <!-- start: PAGE HEADER -->
    <div class="row topheading-row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h1 class="page-header margintop10">
                <span class="@if($t == 'edit') hidden @endif panel-view">
                    {!! lang('common.view_heading', lang('order.order')) !!}
                    #{!! $result->order_number !!}
                </span>
                <span class="@if($t != 'edit') hidden @endif panel-edit">
                    {!! lang('common.edit_heading', lang('order.order')) !!}
                    #{!! $result->order_number !!}
                </span>
            </h1>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <a class="btn btn-sm btn-default pull-right margintop10 marginbottom10 _back" href="javascript:void(0)"> <i class="fa fa-arrow-left fa-fw"></i> {!! lang('common.back') !!} </a>
            &nbsp;&nbsp;
            @if(hasMenuRoute('order.create') || isAdmin())
                <a class="btn btn-sm btn-danger pull-right marginright10 margintop10 marginbottom10" href="{!! route('order.create') !!}">
                    <i class="fa fa-plus fa-fw"></i>
                    {!! lang('common.create_heading', lang('order.order')) !!}
                </a>
            @endif
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- end: PAGE HEADER -->
    <!-- start: PAGE CONTENT -->

    {{-- for message rendering --}}
    @include('layouts.messages')
    <div class="row">
        <div class="col-md-12 padding0">
        {!! Form::model($result, array('route' => array('order.update', $result->id), 'method' => 'PATCH', 'id' => 'ajaxSave', 'class' => 'form-horizontal')) !!}
         <div class="col-md-12">
            <div class="@if($t == 'edit') hidden @endif panel panel-default panel-view">
                @include('order.order_view')
            </div>
            @if( hasMenuRoute('order.update') || $a==1 || isAdmin() )
             <div class="@if($t != 'edit') hidden @endif panel panel-default panel-edit">
                 {{--@include('order.order_edit')--}}
             </div>
            @endif
            <!-- end: TEXT FIELDS PANEL -->
        </div>
        {!! Form::close() !!}
    </div>
</div>
</div>
<!-- /#page-wrapper -->
@include('order.js-script')
@stop
