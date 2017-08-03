@extends('layouts.admin')
@section('content')
<div id="page-wrapper">
    <!-- start: PAGE HEADER -->
    <div class="row topheading-row">
        <div class="col-lg-6 col-md-6 col-sm-9 col-xs-12">
            <h1 class="page-header margintop10">{!! lang('common.create_heading', lang('products.product')) !!}</h1>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-3 col-xs-12">
            <a class="btn btn-sm btn-danger pull-right margintop10 _back" href="javascript:void(0)"> <i class="fa fa-arrow-left fa-fw"></i> {!! lang('common.back') !!} </a>
        </div>
        <div class="clearfix"></div>
    </div>

    @include('layouts.messages')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12  col-xs-12 padding0">
            {!! Form::open(array('route' => array('product.store'), 'method' => 'POST', 'files' => true , 'id' => 'ajaxSave' , 'class' => 'form-horizontal')) !!}
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-external-link-square"></i>
                        {!! lang('products.product_detail') !!}
                    </div>
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-md-6 " style="border-right: 1px solid #D3D3D3;">
                                <div class="form-group">
                                    <div class="col-sm-4 col-sm-offset-4"><h3>Product Info</h3></div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('name', lang('products.product_name'), array('class' => 'col-sm-3 control-label')) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('name', null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('code', lang('products.product_code'), array('class' => 'col-sm-3 control-label')) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('code', null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>


                                <div class="form-group">
                                    {!! Form::label('hsn_id', lang('hsn.hsn'), array('class' => 'col-sm-3 control-label')) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('hsn_id', $hsn, null, array('class' => 'form-control select2' )) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('description', lang('products.description'), array('class' => 'col-sm-3 control-label')) !!}
                                    <div class="col-sm-8">
                                        {!! Form::textarea('description', null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    {!! Form::label('tax_id', lang('tax.tax_group'), array('class' => 'col-sm-3 control-label')) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('tax_id', $tax , null, array('class' => 'form-control select2 ')) !!}
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
                                    $fullPath = ROOT . \Config::get('constants.UPLOADS') . $product->p_image;
                                    $filePath = \Config::get('constants.UPLOADS') . $product->p_image;
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
                                    {!! Form::label('dim-image', lang('products.product_dim_image'), array('class' => 'col-sm-3 control-label')) !!}
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
                                        $fullPath = ROOT . \Config::get('constants.UPLOADS') . $product->p_image;
                                        $filePath = \Config::get('constants.UPLOADS') . $product->p_image;
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
                                    {!! Form::label('status', lang('common.active') . '&nbsp;', array('class' => 'col-sm-3 control-label')) !!}
                                    <div class="col-sm-8">
                                        <label class="checkbox col-sm-4">
                                            {!! Form::checkbox('status', '1', true) !!}
                                        </label>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <div class="col-sm-3 col-sm-offset-3"><h3>Add Size</h3></div>
                                    <div class="col-sm-3  pull-right"><button type="button" name="imgSU" class="btn btn-danger" id="add-new">Add New</button> </div>

                                </div>
                                <div class="form-group cloneDiv" id="clone-size">
                                    {!! Form::label('size_master_id', lang('size.master_size'), array('class' => 'col-sm-2 control-label')) !!}
                                    <div class="col-sm-4">
                                        {!! Form::select('size_master_id[]', $size, null, array('class' => 'form-control ' )) !!}
                                    </div>

                                    {!! Form::label('price', lang('products.price'), array('class' => 'col-sm-1 control-label')) !!}
                                    <div class="col-sm-3">
                                        {!! Form::text('price[]', null, array('class' => 'form-control')) !!}
                                    </div>
                                </div>

                                        <div class="form-group" style="border-top: 1px solid lightgrey;">
                                            <div class="col-sm-6 col-sm-offset-2"><h3>Add Dimension</h3></div>
                                            <div class="col-sm-3 pull-right  margintop20"><button type="button" class="btn btn-danger" id="add-new-dim">Add More</button> </div>

                                        </div>
                                        <div class="form-group">
                                            <div id="dimension" class="col-md-4">
                                                {!! Form::label('dimension_id', lang('size.dim'), array('class' => ' control-label')) !!}
                                                <div class="">
                                                    {!! Form::select('dimension_id[]', $dimension, null, array('class' => 'form-control  ' )) !!}
                                                </div>

                                            </div>



                                        </div>



                            </div>
                        </div>



                        <div class="col-sm-12 margintop10 clearfix text-center">
                                <div class="form-group">
                                    {!! Form::hidden('company_id', loggedInCompanyId()) !!}
                                    {!! Form::hidden('tab_handler', 1, ['id' => 'tab_handler']) !!}
                                    {!! Form::submit(lang('common.save'), array('class' => 'btn btn-danger btn-lg')) !!}
                                    {!! Form::submit(lang('common.save_edit'), array('name' => 'save_edit', 'class' => 'btn btn-danger btn-lg')) !!}
                                </div>
                            </div>


                    </div> <!--panel-body-->
                </div><!-- end: PANEL -->

            </div>
            {!! Form::close() !!}
        </div>    
    </div>
</div><!--page-wrapper END -->
    <script type="text/javascript">
        $(document).ready(function(){

            //clone table
            $("#add-new").on("click", function() {
                $("#clone-size").clone().insertAfter("div.cloneDiv:last").find(":text").val("");

            });

            /*$('.select2').click(function () {
                $(this).trigger('select2:updated');
            });*/
            //clone table
            $("#add-new-dim").on("click", function() {
                $("#dimension").clone().insertAfter("div#dimension:last");
                /*var content = $("#dimension").html();
                $(this).html(content);*/
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
