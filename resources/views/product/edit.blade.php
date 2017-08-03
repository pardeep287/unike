@extends('layouts.admin')
@section('content')
<div id="page-wrapper">
    <!-- start: PAGE HEADER -->
    <div class="row topheading-row">
        <div class="col-lg-6 col-md-6 col-sm-9 col-xs-12">
            <h1 class="page-header margintop10">{!! lang('common.edit_heading', lang('products.product')) !!}   #{{ $product->name }}</h1>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-3 col-xs-12">
            <a class="btn btn-sm btn-danger pull-right margintop10 marginbottom10" href="{!! route('product.index') !!}"> <i class="fa fa-arrow-left fa-fw"></i> {!! lang('common.back') !!} </a>
        </div>

        <!-- /.col-lg-12 -->
    </div>
    <!-- end: PAGE HEADER -->
    <!-- start: PAGE CONTENT -->

    {{-- for message rendering --}}
    @include('layouts.messages')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding0">

            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-external-link-square"></i>
                        {!! lang('products.product_detail') !!}


                    </div>
                    <div class="panel-body">
                        <ul class="nav nav-tabs" role="tablist">
                            <li data-tab = '1' role="presentation" class="@if($tab == 1) active @endif">
                                <a href="#product" aria-controls="home" role="tab" data-toggle="tab">
                                    {!! lang('products.product_detail') !!}
                                </a>
                            </li>
                            <li data-tab = '2' role="presentation" class="@if($tab == 2) active @endif">
                                <a href="#sizes" aria-controls="tab" role="tab" data-toggle="tab">
                                    {!! lang('products.product_size') !!}

                                </a>
                            </li>

                        </ul>
                        <div class="tab-content">
                            <div data-tab = '1' role="tabpanel" class="tab-pane @if($tab == 1) active @endif" id="product">

                            <div class="row ">

                                {!! Form::model($product, array('route' => array('product.update', $product->id), 'method' => 'PATCH', 'files' => true, 'id' => 'ajaxSave', 'class' => 'form-horizontal')) !!}
                                <div class="col-md-6 margintop20" style="border-right: 1px solid #ccc;">
                                    <div class="form-group">
                                        {!! Form::label('name', lang('products.product'), array('class' => 'col-sm-3 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('name', $product->name , array('class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('code', lang('products.product_code'), array('class' => 'col-sm-3 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('code', $product->code , array('class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('hsn_id', lang('hsn.hsn'), array('class' => 'col-sm-3 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::select('hsn_id', $hsn,  $product->hsn_id, array('class' => 'form-control select2' )) !!}

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('description', lang('products.description'), array('class' => 'col-sm-3 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::textarea('description', $product->description, array('class' => 'form-control','rows' => 2,)) !!}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('tax_id', lang('tax.tax_group'), array('class' => 'col-sm-3 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::select('tax_id', $tax ,  $product->tax_id, array('class' => 'form-control select2 ')) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('productImage', lang('products.product_image'), array('class' => 'col-sm-3 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::label('product_image', lang('common.choose_image'), array('class' => 'col-sm-8 control-label', 'id' => 'img-label')) !!}
                                            {!! Form::file('product_image', null) !!}
                                            {{--{!! Form::label('product_image', lang('common.choose_image'), array('class' => 'col-sm-8 control-label', 'id' => 'img-label')) !!}
                                            {!! Form::file('product_image',array('class' => '', 'id' => 'company_logo'), null) !!}--}}
                                        </div>
                                    </div>

                                    <div class="form-group showCompanyLogo">
                                        <?php
                                        if(isset($product)){
                                            $fullPath = ROOT . \Config::get('constants.UPLOADS-PRODUCT') .$product->id.'/' . $product->p_image;
                                            $filePath = \Config::get('constants.UPLOADS-PRODUCT') .$product->id .'/'. $product->p_image;
                                            $image = (!empty($product->p_image) && file_exists($fullPath))?asset($filePath):asset('assets/images/no_image.gif');
                                        }
                                        else{
                                            $image = asset('assets/images/no_image.gif');

                                        }
                                        ?>
                                        <div class="col-sm-4 col-sm-offset-4 ">
                                            <img src="{!! $image !!}" alt="{!! $image !!}" class="img-responsive thumbnail">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('DimImage', lang('products.product_dim_image'), array('class' => 'col-sm-3 control-label')) !!}
                                        <div class="col-sm-8">
                                            {!! Form::label('product_dim_image', lang('common.choose_image'), array('class' => 'col-sm-8 control-label', 'id' => 'img-label-dim')) !!}
                                            {!! Form::file('product_dim_image', null) !!}
                                            {{--{!! Form::label('product_image', lang('common.choose_image'), array('class' => 'col-sm-8 control-label', 'id' => 'img-label')) !!}
                                            {!! Form::file('product_image',array('class' => '', 'id' => 'company_logo'), null) !!}--}}
                                        </div>
                                    </div>

                                    <div class="form-group showDimImage">
                                        <?php
                                        if(isset($product)){
                                            $fullPathd = ROOT . \Config::get('constants.UPLOADS-PRODUCT') .$product->id.'/' . $product->d_image;
                                            $filePathd = \Config::get('constants.UPLOADS-PRODUCT') .$product->id .'/'. $product->d_image;
                                            $dimage = (!empty($product->d_image) && file_exists($fullPathd))?asset($filePathd):asset('assets/images/no_image.gif');
                                        }
                                        else{
                                            $image = asset('assets/images/no_image.gif');

                                        }
                                        ?>
                                        <div class="col-sm-4 col-sm-offset-4 ">
                                            <img src="{!! $dimage !!}" alt="{!! $dimage !!}" class="img-responsive thumbnail">
                                        </div>
                                    </div>




                                    <div class="form-group">
                                        {!! Form::label('status', lang('common.active') . '&nbsp;', array('class' => 'col-sm-4 control-label')) !!}
                                        <div class="col-sm-8">
                                            <label class="checkbox col-sm-4">
                                                {!! Form::checkbox('status', '1' , ($product->status == '1') ? true : false) !!}
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 margintop5 clearfix text-center">
                                        <div class="form-group margin0">
                                            {{--{!! Form::hidden('pemission_id', ($userPermissions == null)?"":$userPermissions->permission_id) !!}--}}
                                            {{--{!! Form::hidden('tab', 1) !!}--}}
                                            <input type="hidden" name="tab" value="1">
                                            {!! Form::hidden('company_id', loggedInCompanyId()) !!}
                                            {!! Form::submit(lang('common.update'), array('class' => 'btn btn-danger')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 margintop20">

                                    <div class="form-group">
                                        {!! Form::label('thumbImage', lang('products.thumb_image'), array('class' => 'col-sm-3 control-label')) !!}
                                        <div class="col-sm-8">
                                            {{--{!! Form::label('thumb_images', lang('common.choose_image'), array('class' => 'col-sm-8 control-label', 'id' => 'img-label-thumbs')) !!}--}}
                                            {!! Form::file('thumb_image[]', array('class'=>'multipleInput', 'multiple')) !!}

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <?php
                                        if(isset($thumbImages) && count($thumbImages)>0){
                                            foreach ($thumbImages as $image){
                                            $fullPathd = ROOT . \Config::get('constants.UPLOADS-PRODUCT') .$product->id.'/' . $image->image_name;
                                            $filePathd = \Config::get('constants.UPLOADS-PRODUCT') .$product->id .'/'. $image->image_name;
                                            $dimage = (!empty($image->image_name) && file_exists($fullPathd))?asset($filePathd):asset('assets/images/no_image.gif');

                                        ?>


                                            <div class="col-md-4 col-sm-6 col-xs-6">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <img src="{!! $dimage !!}" alt="{!! $dimage !!}" class="img-responsive ">

                                                    </div>
                                                    <div class="panel-body" style="padding: 2px;">
                                                        <!-- Watch Out: Here We must use the effect name in the data tag-->
                                                       <a class="clickable __drop" data-effect="slideUp" data-route="{!! route('product.deleteImage', ['id'=>$image->product_image_thumb_id]) !!}" style="display: table; margin: 0px auto;" data-message="{!! lang('messages.sure_delete', string_manip(lang('products.thumb_image'))) !!}" href="javascript:void(0)"><i class="fa fa-times"></i>
                                                       </a>

                                                        {{--<a title="{!! lang('common.delete') !!}" class="btn btn-xs btn-danger __drop" data-route="{!! route('bank.drop', [$detail->id]) !!}" data-message="{!! lang('messages.sure_delete', string_manip(lang('bank.bank'))) !!}" href="javascript:void(0)"><i class="fa fa-times"></i></a>--}}
                                                    </div>


                                                </div>
                                            </div>
                                        <?php
                                            }
                                            }
                                            ?>
                                    </div>



                                </div>
                                {!! Form::close() !!}
                            </div> <!--row ends-->

                            </div> <!--tabs1 end-->

                            <div data-tab = '2' role="tabpanel" class="tab-pane @if($tab == 2) active @endif" id="sizes">
                                <div class="row">
                                    <button class="btn btn-sm btn-success pull-right" id="addSize" style="position: relative; right: 39px; bottom: -6px;">Add Size</button>

                                    <div class="col-md-12 margintop20" id="addSizeDiv" style="display:none;">

                                        {!! Form::open(array('route' => array('product.storeSize'), 'method' => 'POST' , 'id' => 'ajaxSavesss' , 'class' => 'form-horizontal ajaxSaveAll')) !!}
                                        <div class="form-group cloneDiv" id="clone-size">

                                            {!! Form::label('size_master_id', lang('size.master_size'), array('class' => 'col-sm-2 control-label')) !!}
                                            <div class="col-sm-3">
                                                {!! Form::select('size_master_id', $NotSelectedSize, null, array('class' => 'form-control select2 padding0' )) !!}
                                            </div>

                                            {!! Form::label('price', lang('products.price'), array('class' => 'col-sm-1 control-label')) !!}
                                            <div class="col-sm-3">
                                                {!! Form::text('price', null, array('class' => 'form-control')) !!}
                                            </div>
                                            <div class="col-sm-3">
                                                {{--<br/>--}}
                                                <input type="hidden" name="tab" value="2">
                                                <input type="hidden" id="dimension_name" name="dimension_name" />
                                                {!! Form::hidden('product_id', $id) !!}
                                                {!! Form::submit(lang('common.add'), array('class' => 'btn btn-sm btn-danger control-label')) !!}
                                            </div>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                    <div class="col-md-12 margintop20" id="chngPriceDiv" style="display:none;">

                                        {!! Form::open(array('route' => array('product.storeDimValue', 'id' => $id), 'method' => 'POST' , 'id' => 'ajaxSave' ,'class' => 'form-horizontal')) !!}
                                        <div class="form-group cloneDiv" id="clone-size">

                                            {!! Form::label('price', lang('products.price'), array('class' => 'col-sm-1 control-label')) !!}
                                            <div class="col-sm-3">
                                                {{--{!! Form::text('price', null, array('class' => 'form-control')) !!}--}}
                                                <input type="text" class="form-control" id="priceOld" name="price" value="" />
                                            </div>
                                            <div class="col-sm-3">
                                                {{--<br/>--}}
                                                <input type="hidden" name="tab" value="2">
                                                <input type="hidden" id="size_id" name="size_id" />
                                                <input type="hidden" name="change_price" value="1" />
                                                {!! Form::hidden('product_id', $id) !!}
                                                {!! Form::submit(lang('common.update'), array('class' => 'btn btn-sm btn-danger control-label')) !!}
                                            </div>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                    <div class="col-md-12 margintop20">
                                       {{-- {!! Form::model($product, array('route' => array('products.update', $product->id), 'method' => 'PATCH', 'id' => 'products-form', 'class' => 'form-horizontal')) !!}--}}
                                        {!! Form::open(array('route' => array('product.storeDimValue'), 'method' => 'POST' , 'id' => 'ajaxSave' , 'class' => 'form-horizontal')) !!}
                                        <table class="table table-hover clearfix margin0 col-md-12 padding0">
                                            <thead>
                                            <tr>
                                                <th width="5%" class="text-center">{!! lang('common.id') !!}</th>
                                                <th>{!! lang('size.normal_size') !!}</th>

                                                 @foreach($dimension_name as $key => $dim)
                                                    <th>{!! lang('size.dim_value',$dim['dimension_name']) !!}</th>
                                                 @endforeach

                                                <th>{!! lang('products.price') !!}</th>
                                                <th>{!! lang('common.status') !!}</th>

                                                <th >{!! lang('common.action') !!}</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            @if(isset($SizePrice) && count($SizePrice) >0)
                                                @foreach($SizePrice as $key => $detail)
                                                    <tr>
                                                        <td class="text-center">{!! ++$key !!}</td>
                                                        <td>{!! $detail->normal_size !!}</td>
                                                        <?php
                                                        $value=[];
                                                            if(count($dimension_value)>0){
                                                                foreach ($dimension_value as $key=>$data)
                                                                {
                                                                    if($data['size_id']==$detail['product_sizes_id'])
                                                                    $value[]= $data;
                                                                }
                                                            }
                                                        //$value= (count($dimension_value)>0)?$dimension_value:null;
                                                        //$value = null
                                                        ?>

                                                        @foreach($dimension_name as $dimKey=>$dim)
                                                            <td>
                                                                <div class="form-group">

                                                                    <div class="col-sm-8">

                                                                       {{-- {!! Form::text('dim['.$detail->product_sizes_id.']['.$dim->product_dimension_id.']',if(count($value)>0){ foreach($value as $key=>$dimValue) {if($dimValue['dimension_id']==$dim['product_dimension_id']) echo $dimValue['dimension_id'];}}, array('class' => 'form-control')) !!}--}}

        <input type="text"  name="<?php
        if(count($value)>0){
            echo 'dim_id['.$detail->product_sizes_id.']['.$value[$dimKey]['product_size_dimensions_id'].']';
        }
        else{
            echo 'dim['.$detail->product_sizes_id.']['.$dim->product_dimension_id.']';
        }
        ?>" value="<?php

        if(count($value)>0)
        {
            foreach($value as $key=>$dimValue)
            {
                if($dimValue['dimension_id']==$dim['product_dimension_id'])
                    echo $dimValue['dimension_value'];
            }
        }
        ?>" <?php echo $detail->status==0?'disabled ':'';?> class="form-control"/>

                                        {{--{!! Form::text('dim['.$detail->product_sizes_id.'][]', ($value==null) ?null:$value[$dimkey] , array('class' => 'form-control')) !!}--}}
                                                                    </div>
                                                                </div>

                                                            </td>
                                                        @endforeach


                                                        <td>{!! $detail->price !!}</td>
                                                        <td class="text-center">
                                                            <a title="{!! lang('common.status') !!}" href="javascript:void(0);" class="toggle-status" data-message="{!! lang('messages.change_status') !!}" data-route="{!! route('product.toggleSize', $detail->product_sizes_id) !!}" data-realod="{!! route('product.edit', ['id'=>$id,'tab' => 2]) !!}">
                                                                {!! Html::image('assets/images/' . $detail->status . '.gif') !!}
                                                            </a>
                                                        </td>
                                                        <td>

                                                            <a class="btn btn-xs btn-default <?php echo $detail->status==0?'':'priceEdit';?>" id="<?php echo $detail->status==0?'':'priceEdit';?>" data-q_id="{!! $detail->product_sizes_id  !!}" data-q_id2="{!! $detail->price  !!}" href="javascript:void(0)"><i class="fa fa-edit"></i></a>
                                                            {{--<a class="btn btn-xs btn-default" id="priceEdit" data-q_id="{!! $detail->product_sizes_id  !!}" href="{{ route('product.edit', ['id' => $product->id, 'tab' => 2, 'size_id' => $detail->product_sizes_id]) }}"><i class="fa fa-edit"></i></a>--}}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="7">
                                                        <div class="col-md-2 col-md-offset-5">
                                                            <input type="hidden" name="tab" value="2">
                                                    {!! Form::hidden('product_id', $id) !!}
                                                    {!! Form::submit(lang('common.update'), array('class' => 'btn btn-sm btn-danger control-label')) !!}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                            </tbody>


                                            {!! Form::close() !!}
                                        </table>
                                    </div>

                                </div>
                            </div> <!--tabs2 end-->
                        </div> <!--Tabs COntent ends -->
                    </div>
                </div>
                <!-- end: TEXT FIELDS PANEL -->
            </div>

        </div>
    </div>
</div>
<!-- /#page-wrapper -->
<script type="text/javascript">
    $(document).ready(function(){


            /*$('.clickable').on('click',function(){


                //$.post( "ajax/test.html", function( data ) {
                    //$( ".result" ).html( data );

                var effect = $(this).data('effect');
                $(this).closest('.panel')[effect]();
                });

            });*/

        /*$('.select2').click(function () {
         $(this).trigger('select2:updated');
         });*/

        //show add button
        $("#addSize").click(function() {
            $("#addSizeDiv").fadeToggle();
            $("#addSize").hide();
        });

        $(".priceEdit").click(function(e) {
            //var $link = $(this);
            var t2 = $(this).data('q_id');
            var t3 = $(this).data('q_id2');
            $("#chngPriceDiv").fadeToggle();
            $("#size_id").val(t2);
            $("#priceOld").val(t3);
        });

        $('#product_image').on('change',function(){
            var filename = $(this)[0].files[0]['name'];
            var fileExtension = filename.substr(filename.lastIndexOf('.') + 1);
            fileExtension = fileExtension.toLowerCase();
            var validExtension = ['jpg', 'jpeg', 'png', 'gif'];
            if($.inArray(fileExtension, validExtension) >= 0){
                $('#img-label').css('border', '1px dashed #ccc');
            }else {
                filename = 'No File Selected';
                $('#product_image').val('');
                $('#img-label').css('border', '1px dashed red');
                alert('Please select an image');
            }
            readURL(this);
            $('#img-label').text(filename);
        });
        $('#product_dim_image').on('change',function(){
            var filename = $(this)[0].files[0]['name'];
            var fileExtension = filename.substr(filename.lastIndexOf('.') + 1);
            fileExtension = fileExtension.toLowerCase();
            var validExtension = ['jpg', 'jpeg', 'png', 'gif'];
            if($.inArray(fileExtension, validExtension) >= 0){
                $('#img-label-dim').css('border', '1px dashed #ccc');
            }else {
                filename = 'No File Selected';
                $('#product_dim_image').val('');
                $('#img-label-dim').css('border', '1px dashed red');
                alert('Please select an image');
            }
            readURL2(this);
            $('#img-label-dim').text(filename);
        });

       /* $('#thumb_image[]').on('change',function(){
            var filename = $(this)[0].files[0]['name'];
            var fileExtension = filename.substr(filename.lastIndexOf('.') + 1);
            fileExtension = fileExtension.toLowerCase();
            var validExtension = ['jpg', 'jpeg', 'png', 'gif'];
            if($.inArray(fileExtension, validExtension) >= 0){
                $('#img-label-thumb').css('border', '1px dashed #ccc');
            }else {
                filename = 'No File Selected';
                $('#thumb_image[]').val('');
                $('#img-label-thumb').css('border', '1px dashed red');
                alert('Please select an image');
            }
            readURL2(this);
            $('#img-label-thumb').text(filename);
        });*/

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
    function readURL2(input)
    {
        var html = '';
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            $(".backDrop").fadeIn( 100, "linear" );
            $(".loader").fadeIn( 100, "linear" );
            reader.onload = function (e) {
                html = "<img class='img-responsive thumbnail' src='"+ e.target.result +"'>";
                $('.showDimImage').html(html);
                $(".backDrop").fadeOut( 100, "linear" );
                $(".loader").fadeOut( 100, "linear" );
            }
            reader.readAsDataURL(input.files[0]);
        }else{
            $('.showDimImage').html(html);
        }
    }
</script>

@stop
