@extends('layouts.admin-new')
@section('content')
<div id="page-wrapper">
    <!-- start: PAGE HEADER -->
    <div class="row topheading-row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h1 class="page-header margintop10">{!! lang('setting.manage_profile') !!}</h1>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <a class="btn btn-sm btn-default pull-right margintop10 marginbottom10 _back" href="javascript:void(0)"> <i class="fa fa-arrow-left fa-fw"></i> {!! lang('common.back') !!} </a>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <!-- end: PAGE HEADER -->
    <!-- start: PAGE CONTENT -->
    
    {{-- for message rendering --}}
    @include('layouts.messages')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding0">
            {!! Form::model($profile, array('route' => array('setting.myprofile'), 'id' => 'setting-myprofile', 'class' => 'form-horizontal')) !!}
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-external-link-square"></i> &nbsp;
                        {!! lang('setting.profile_detail') !!}
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('complete_name', lang('common.name'), array('class' => 'col-sm-3 control-label')) !!}
                                    <div class="col-sm-8">
                                        {!! Form::textarea('complete_name', is_object($profileDetail) ? $profileDetail->complete_name : null, array('class' => 'form-control', 'size' => '5x4')) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('logo', lang('setting.logo'), array('class' => 'col-sm-3 control-label')) !!}
                                    <div class="col-sm-8">
                                        {!! Form::file('logo', null) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('email', lang('laboratory.email'), array('class' => 'col-sm-4 control-label')) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('email', null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('phone', lang('laboratory.phone'), array('class' => 'col-sm-4 control-label')) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('phone', null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('fax', lang('laboratory.fax'), array('class' => 'col-sm-4 control-label')) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('fax', null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('address', lang('setting.address'), array('class' => 'col-sm-4 control-label')) !!}
                                    <div class="col-sm-8">
                                        {!! Form::textarea('address', $profile->address1, array('class' => 'form-control', 'size' => '5x4')) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 margintop10 text-center">
                                <div class="form-group marginright15">
                                    {!! Form::submit(lang('common.update'), array('class' => 'btn btn-primary btn-lg')) !!}
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
@section('script')
<script>
function valueChanged() {
    if ($('#more').is(':checked')) {
        $("#more-detail").fadeIn(500, "linear").removeClass('hidden');
    } else {
        $("#more-detail").fadeOut(500, "linear").addClass('hidden');
    } 
}
</script>
@stop