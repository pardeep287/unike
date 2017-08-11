@extends('layouts.admin')
@section('content')
<div id="page-wrapper">
    <!-- start: PAGE HEADER -->
    <div class="row">
        <div class="col-lg-12">
            <a class="btn btn-sm btn-default pull-right margintop10 _back" href="javascript:void(0)"> <i class="fa fa-arrow-left fa-fw"></i> {!! lang('common.back') !!} </a>
            <h1 class="page-header margintop10"> {!! lang('common.edit_heading', lang('menu.menu')) !!} #{{ $result->name }}</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- end: PAGE HEADER -->
    <!-- start: PAGE CONTENT -->
    
    {{-- for message rendering --}}
    @include('layouts.messages')
    <div class="row">
        <div class="col-md-12 padding0">
            {!! Form::model($result, array('route' => array('menu.update', $result->id), 'method' => 'PATCH', 'id' => 'carriage-form', 'class' => 'form-horizontal')) !!}
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-external-link-square"></i> &nbsp;
                        {!! lang('menu.menu_detail') !!}
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('display_name', lang('menu.display_name'), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('display_name', $result->name, array('class' => 'display_name form-control')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('route_name', lang('menu.route_name'), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('route_name', $result->route, array('class' => 'route_name form-control')) !!}
                                </div>
                            </div>                           

                            <div class="form-group">
                                {!! Form::label('icon', lang('menu.icon'), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('icon', null, array('class' => 'menuicon form-control')) !!}
                                </div>
                            </div>

                            
                            <div class="form-group">
                                {!! Form::label('parent_menu', lang('menu.parent_menu'), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('parent_menu', $parentdata, $result->parent_id, array('class' => 'select2 parent_menu form-control')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('depend_routes', lang('menu.depend_routes'), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    {!! Form::textarea('dependent_routes', null, array('class' => 'depend_routes form-control', 'rows'=>'3')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('status', lang('common.active') . '&nbsp;', array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    <label class="checkbox col-sm-3">
                                        {!! Form::checkbox('status', '1', $result->status) !!}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('order', lang('menu.order'), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-3">
                                     {!! Form::text('order', $result->_order, array('class' => 'order form-control')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('for_devs', lang('menu.for_devs'), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    <label class="checkbox col-sm-3">
                                        {!! Form::checkbox('for_devs', '1', $result->for_devs,array('class'=>'for_devs')) !!}
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('is_in_menu', lang('menu.is_in_menu'), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    <label class="checkbox col-sm-3">
                                        {!! Form::checkbox('is_in_menu', '1', $result->is_in_menu, array('class'=>'is_in_menu')) !!}
                                    </label>
                                </div>
                           </div>
                           <div class="form-group">
                                {!! Form::label('quick_menu', lang('menu.quick_menu'), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    <label class="checkbox col-sm-3">
                                        {!! Form::checkbox('quick_menu', '1', $result->quick_menu, array('class'=>'quick_menu')) !!}
                                    </label>
                                </div>
                           </div>
                           <div class="form-group">
                                {!! Form::label('is_common', lang('menu.is_common'), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    <label class="checkbox col-sm-3">
                                        {!! Form::checkbox('is_common', '1', $result->is_common, array('class'=>'is_common')) !!}
                                    </label>
                                </div>
                           </div>
                           <div class="form-group">
                                {!! Form::label('has_child', lang('menu.has_child'), array('class' => 'col-sm-4 control-label')) !!}
                                <div class="col-sm-8">
                                    <label class="checkbox col-sm-3">
                                        {!! Form::checkbox('has_child', '1', $result->has_child, array('class'=>'has_child')) !!}
                                    </label>
                                </div>
                           </div>

                         </div>
                        <div class="col-sm-12 margintop10 clearfix text-center">
                            <div class="form-group">
                                {!! Form::submit(lang('common.update'), array('class' => 'btn btn-danger btn-lg', 'id' => 'menu_submit')) !!}
                            </div>
                        </div>

                    </div>
                    </div>
                </div>
                <!-- end: TEXT FIELDS PANEL -->
            </div>
            {!! Form::close() !!}
        </div>    
    </div>
</div>
<!-- /#page-wrapper -->
@stop