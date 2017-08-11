@extends('layouts.admin')
@section('content')
    <div id="page-wrapper">
        <!-- start: PAGE HEADER -->
        <div class="row topheading-row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

                <h1 class="page-header margintop10">
                    {!! lang('company.company_edit') !!}
                </h1>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <a class="btn btn-sm btn-default pull-right margintop10 marginbottom10 _back" href="javascript:void(0)"> <i class="fa fa-arrow-left fa-fw"></i> {!! lang('common.back') !!} </a>
            </div>
        </div>

        @include('layouts.messages')
        <?php $tab = Session::has('tab') ? Session::get('tab') : 1; ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding0">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div>
                                <ul class="nav nav-tabs" role="tablist">
                                    <li data-tab = '1' role="presentation" class="@if($tab == 1) active @endif">
                                        <a href="#company_detail" aria-controls="home" role="tab" data-toggle="tab">
                                            {!! lang('setting.company_detail') !!}
                                        </a>
                                    </li>
                                    <li data-tab = '2' role="presentation" class="@if($tab == 2) active @endif">
                                        <a href="#logo_tab" aria-controls="tab" role="tab" data-toggle="tab">
                                            {!! lang('setting.company_logo') !!}
                                        </a>
                                    </li>
                                    <li data-tab = '3' role="presentation" class="@if($tab == 3) active @endif hidden">
                                        <a href="#company_setting" aria-controls="tab" role="tab" data-toggle="tab">
                                            {!! lang('setting.company_settings') !!}
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div data-tab = '1' role="tabpanel" class="tab-pane @if($tab == 1) active @endif" id="company_detail">
                                        <div class="col-md-12 margintop20">
                                            {!! Form::open(array('route' => array('company.update', $company->id), 'method' => 'PATCH', 'files' => true ,  'class' => 'form-horizontal')) !!}
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            {!! Form::label('company_name', lang('setting.company_name'), array('class' => 'col-sm-4 control-label')) !!}
                                                            <div class="col-sm-8">
                                                                {!! Form::text('company_name', $company->company_name, array('class' => 'form-control')) !!}
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            {!! Form::label('contact_person', lang('setting.contact_person'), array('class' => 'col-sm-4 control-label')) !!}
                                                            <div class="col-sm-8">
                                                                {!! Form::text('contact_person', $company->contact_person, array('class' => 'form-control')) !!}
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            {!! Form::label('tin_number', lang('company.tin_number'), array('class' => 'col-sm-4 control-label')) !!}
                                                            <div class="col-sm-8">
                                                                {!! Form::text('tin_number', $company->tin_number, array('class' => 'form-control')) !!}
                                                            </div>
                                                        </div>


                                                        <div class="form-group">
                                                            {!! Form::label('pan_number', lang('company.pan_number'), array('class' => 'col-sm-4 control-label')) !!}
                                                            <div class="col-sm-8">
                                                                {!! Form::text('pan_number', $company->pan_number, array('class' => 'form-control')) !!}
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            {!! Form::label('gst_number', lang('setting.gst_number'), array('class' => 'col-sm-4 control-label')) !!}
                                                            <div class="col-sm-8">
                                                                {!! Form::text('gst_number', $company->gst_number, array('class' => 'form-control')) !!}
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            {!! Form::label('email1', lang('setting.email1'), array('class' => 'col-sm-4 control-label')) !!}
                                                            <div class="col-sm-8">
                                                                {!! Form::text('email1', $company->email1, array('class' => 'form-control')) !!}
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            {!! Form::label('email2', lang('setting.email2'), array('class' => 'col-sm-4 control-label')) !!}
                                                            <div class="col-sm-8">
                                                                {!! Form::text('email2', $company->email2, array('class' => 'form-control')) !!}
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            {!! Form::label('mobile1', lang('setting.mobile1'), array('class' => 'col-sm-4 control-label')) !!}
                                                            <div class="col-sm-8">
                                                                {!! Form::text('mobile1', $company->mobile1, array('class' => 'form-control')) !!}
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            {!! Form::label('mobile2', lang('setting.mobile2'), array('class' => 'col-sm-4 control-label')) !!}
                                                            <div class="col-sm-8">
                                                                {!! Form::text('mobile2', $company->mobile2, array('class' => 'form-control')) !!}
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            {!! Form::label('phone', lang('setting.phone'), array('class' => 'col-sm-4 control-label')) !!}
                                                            <div class="col-sm-8">
                                                                {!! Form::text('phone', $company->phone, array('class' => 'form-control')) !!}
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            {!! Form::label('permanent_address', lang('setting.permanent_address'), array('class' => 'col-sm-4 control-label')) !!}
                                                            <div class="col-sm-8">
                                                                {!! Form::textarea('permanent_address', $company->permanent_address, array('class' => 'form-control', 'rows' => '3')) !!}
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            {!! Form::label('correspondence_address', lang('setting.correspondence_address'), array('class' => 'col-sm-4 control-label')) !!}
                                                            <div class="col-sm-8">
                                                                {!! Form::textarea('correspondence_address', $company->correspondence_address, array('class' => 'form-control' ,'rows' => '3')) !!}
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            {!! Form::label('website', lang('setting.website'), array('class' => 'col-sm-4 control-label')) !!}
                                                            <div class="col-sm-8">
                                                                {!! Form::text('website', $company->website, array('class' => 'form-control')) !!}
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            {!! Form::label('city', lang('setting.city'), array('class' => 'col-sm-4 control-label')) !!}
                                                            <div class="col-sm-8">
                                                                {!! Form::text('city', $company->city, array('class' => 'form-control')) !!}
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            {!! Form::label('state', lang('setting.state'), array('class' => 'col-sm-4 control-label')) !!}
                                                            <div class="col-sm-8">
                                                                {!! Form::text('state', $company->state, array('class' => 'form-control')) !!}
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            {!! Form::label('country', lang('setting.country'), array('class' => 'col-sm-4 control-label')) !!}
                                                            <div class="col-sm-8">
                                                                {!! Form::text('country', $company->country, array('class' => 'form-control')) !!}
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            {!! Form::label('pincode', lang('setting.pincode'), array('class' => 'col-sm-4 control-label')) !!}
                                                            <div class="col-sm-8">
                                                                {!! Form::text('pincode', $company->pincode, array('class' => 'form-control')) !!}
                                                            </div>
                                                        </div>

                                                        <!--<div class="form-group">
                                                            {{--{!! Form::label('is_full_version', lang('company.is_full_version') . '&nbsp;', array('class' => 'col-sm-4 control-label')) !!}--}}
                                                            <div class="col-sm-8">
                                                                <label class="checkbox col-sm-3">
                                                                    <?php //$isFullVersion = (isset($company->is_full_version) && $company->is_full_version == 1) ? true : false; ?>
                                                                    {{--{!! Form::checkbox('is_full_version', '1', $isFullVersion) !!}--}}
                                                                </label>
                                                            </div>
                                                        </div>
                                                        -->


                                                    </div>

                                                    <div class="col-sm-12 margintop10 text-center">
                                                        <div class="form-group">
                                                            {!! Form::submit(lang('common.save'), array('class' => 'btn btn-danger btn-lg')) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                {!! Form::hidden('tab', 1) !!}
                                            {!! Form::close() !!}
                                        </div>
                                    </div>

                                    <div data-tab = '2' role="tabpanel" class="tab-pane @if($tab == 2) active @endif" id="logo_tab">
                                        <div class="col-md-8 margintop20">
                                            {!! Form::open(array('route' => array('company.update', $company->id), 'method' => 'PATCH', 'files' => true ,  'class' => 'form-horizontal')) !!}
                                                <div class="row">
                                                    <div class="col-sm-10">
                                                        <div class="row">
                                                            <div class="col-sm-7">
                                                                <div class="form-group">
                                                                    {!! Form::label('logo', lang('company.company_logo'), array('class' => 'col-sm-4 control-label')) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::label('company_logo', lang('common.choose_image'), array('class' => 'col-sm-8 control-label', 'id' => 'img-label')) !!}
                                                                        {!! Form::file('company_logo', null) !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-5 showCompanyLogo">
                                                                <?php
                                                                $fullPath = ROOT . \Config::get('constants.UPLOADS') . $company->company_logo;
                                                                $filePath = \Config::get('constants.UPLOADS') . $company->company_logo;
                                                                $image = (!empty($company->company_logo) && file_exists($fullPath))?asset($filePath):asset('assets/images/no_image.gif');
                                                                ?>
                                                                <img src="{!! $image !!}" alt="{!! $image !!}" class="img-responsive thumbnail">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12 margintop10 text-center">
                                                        <div class="form-group">
                                                            {!! Form::submit(lang('common.save'), array('class' => 'btn btn-danger btn-lg')) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                {!! Form::hidden('tab', 2) !!}
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                    <div data-tab = '3'  role="tabpanel" class="tab-pane hidden  @if($tab == 3) active @endif" id="company_setting">
                                        <div class="col-md-12 margintop20">
                                            {!! Form::open(array('route' => array('company.update', $company->id), 'method' => 'PATCH', 'files' => true ,  'class' => 'form-horizontal')) !!}
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            {!! Form::label('currency', lang('setting.currency'), array('class' => 'col-sm-4 control-label')) !!}
                                                            <div class="col-sm-8 rupess-sybl">
                                                                {!! Form::select('currency', $currency, isset($setting->currency_id)?$setting->currency_id:null, ['class' => 'form-control select2',  "style" => "font-family: 'FontAwesome', Helvetica !important;"]) !!}
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            {!! Form::label('datetime_format', lang('setting.datetime_format'), array('class' => 'col-sm-4 control-label')) !!}
                                                            <div class="col-sm-8">
                                                                {!! Form::select('datetime_format', $dateTimeFormat, isset($setting->datetime_format_id)?$setting->datetime_format_id:null, ['class' => 'form-control select2']) !!}
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            {!! Form::label('timezone', lang('setting.timezone'), array('class' => 'col-sm-4 control-label')) !!}
                                                            <div class="col-sm-8">
                                                                {!! Form::select('timezone', $timezone, isset($setting->timezone_id)?$setting->timezone_id:null, ['class' => 'form-control select2']) !!}
                                                            </div>
                                                        </div>

                                                        {{--<div class="form-group">
                                                            {!! Form::label('theme', lang('setting.theme'), array('class' => 'col-sm-4 control-label')) !!}
                                                            <div class="col-sm-8">
                                                                {!! Form::select('theme', $theme, isset($setting->theme_id)?$setting->theme_id:null, ['class' => 'form-control select2']) !!}
                                                            </div>
                                                        </div>--}}

                                                        <div class="form-group">
                                                            {!! Form::label('is_email_enable', lang('setting.is_email_enable'), array('class' => 'col-sm-4 control-label')) !!}
                                                            <div class="col-sm-8 margintop8">
                                                                <?php $emailChecked = (isset($setting->is_email_enable) && $setting->is_email_enable == 1) ? true : false; ?>
                                                                {!! Form::checkbox('is_email_enable', '1', $emailChecked) !!}
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            {!! Form::label('is_sms_enable', lang('setting.is_sms_enable'), array('class' => 'col-sm-4 control-label')) !!}
                                                            <div class="col-sm-8 margintop8">
                                                                <?php $smsChecked = (isset($setting->is_sms_enable) && $setting->is_sms_enable == 1) ? true : false; ?>
                                                                {!! Form::checkbox('is_sms_enable', '1', $smsChecked) !!}
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            {!! Form::label('status', lang('setting.status'), array('class' => 'col-sm-4 control-label')) !!}
                                                            <div class="col-sm-8 margintop8">
                                                                <?php $status = (isset($setting->status) && $setting->status == 1) ? true : false; ?>
                                                                {!! Form::checkbox('status', '1', $status) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-8 margintop10 text-center">
                                                        <div class="form-group">
                                                            {!! Form::submit(lang('common.save'), array('class' => 'btn btn-primary btn-lg')) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            {!! Form::hidden('tab', 3) !!}
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- /#page-wrapper -->
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#company_logo').on('change',function(){
                var filename = $(this)[0].files[0]['name'];
                var fileExtension = filename.substr(filename.lastIndexOf('.') + 1);
                fileExtension = fileExtension.toLowerCase();
                var validExtension = ['jpg', 'jpeg', 'png', 'gif'];
                if($.inArray(fileExtension, validExtension) >= 0){
                    $('#img-label').css('border', '1px dashed #ccc');
                }else {
                    filename = 'No File Selected';
                    $('#company_logo').val('');
                    $('#img-label').css('border', '1px dashed red');
                    alert('Please select an image');
                }
                readURL(this);
                $('#img-label').text(filename);
            });

            /* Setting up the tab */

            $('li[role="presentation"]').click(function(){
                $tab = $(this).data('tab');
                var hidden = $('#tab-container');
                hidden.val($tab);
                console.log(hidden);
            });
        });

        function readURL(input)
        {
            var html = '';
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                $(".backDrop").fadeIn( 100, "linear" );
                $(".loader").fadeIn( 100, "linear" );
                reader.onload = function (e) {
                    html = "<img class='img-responsive thumbnail' src='"+ e.target.result +"'>";
                    $('.showCompanyLogo').html(html);
                    $(".backDrop").fadeOut( 100, "linear" );
                    $(".loader").fadeOut( 100, "linear" );
                }
                reader.readAsDataURL(input.files[0]);
            }else{
                $('.showCompanyLogo').html(html);
            }
        }
    </script>
@stop