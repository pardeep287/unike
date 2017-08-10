<div class="panel-heading">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <i class="fa fa-external-link-square"></i> &nbsp;
        {!! lang('order.order_detail') !!}
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding0" id="right-actionbtn-list">
        @if( hasMenuRoute('customer-order.drop') || isAdmin() )
        <a title="{!! lang('common.delete') !!}" data-redirect="{!! route('order.index') !!}" class="btn btn-xs pull-right hidden btn-danger __drop" data-route="{!! route('order.drop', [$result->id]) !!}" data-message="{!! lang('messages.sure_delete', string_manip(lang('order.order'))) !!}" href="javascript:void(0)">
            <i class="fa fa-trash"></i>
            {!! lang('order.delete') !!}
        </a>
        @endif
        {{--@if(hasMenuRoute('order.update') || isAdmin())
        <a title="{!! lang('common.modify') !!}" href="javascript:void(0)" class="btn btn-xs btn-primary pull-right _modify">
            <i class="fa fa-edit"></i>
            {!! lang('common.modify') !!}
        </a>
        @endif--}}
        @if( hasMenuRoute('order.order-print') || isAdmin() )
        <a title="{!! lang('common.print') !!}" class="btn btn-xs pull-right marginright10 btn-success" href="{{ route('order.order-print', [$result->id]) }}">
            <i class="fa fa-print"></i>
            {!! lang('order.print') !!}
        </a>
        @endif
       {{-- @if( hasMenuRoute('order.order-pdf') || isAdmin() )
        <a title="{!! lang('common.generate_pdf') !!}" class="btn btn-xs pull-right marginright10 btn-info" target="_blank" href="{{ route('order.order-pdf', [$result->id]) }}">
            <i class="fa fa-file-pdf-o"></i>
            {!! lang('order.pdf') !!}
        </a>
        @endif--}}

       {{-- @if( hasMenuRoute('order.send-email') || isAdmin() )
            <a data-title="{!! lang('common.send_email') !!}" class="btn btn-xs pull-right dEdit marginright10 btn-success" href="javascript:void(0)" data-route="{!! route('order.send-email', [$result->id]) !!}">
                <i class="fa fa-send"></i>
                {!! lang('common.send_email') !!}
            </a>
        @endif--}}
    </div>
    <div class="clearfix"></div>
</div>
<div class="panel-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group marginbottom20">
                {!! Form::label('customer_name', lang('customer.customer_name'), array('class' => 'col-sm-2 control-label')) !!}
                <div class="col-sm-2 paddingtop10">
                    <p>{!! $result->customer_name !!}</p>
                </div>

               {{-- {!! Form::label('order_number', lang('order.order_number'), array('class' => 'col-sm-2 control-label')) !!}
                <div class="col-sm-1 paddingtop10">
                    <p>{!! $result->order_number !!}</p>
                </div>--}}
                {!! Form::label('order_number', lang('order.order_number'), array('class' => 'col-sm-2 control-label')) !!}
                <div class="col-sm-1 paddingtop10">
                    <p>{!! 'UNK - '. $result->order_number !!}</p>
                </div>

                {{--{!! Form::label('order_date', lang('order.order_date'), array('class' => 'col-sm-2 control-label')) !!}
                <div class="col-sm-2 paddingtop10">
                    <p>
                        {!! convertToLocal($result->order_date, 'd-m-Y') !!}
                        <span class="hidden">{!! convertToLocal($result->order_date, 'd-m-Y H:i:s') !!}</span>
                    </p>
                </div>--}}

                {!! Form::label('order_date', lang('order.order_date'), array('class' => 'col-sm-2 control-label')) !!}
                <div class="col-sm-2 paddingtop10">
                    <p>{!! dateFormat('d-m-Y', $result->order_date) !!}</p>
                </div>
            </div>
        </div>

        <div class="col-md-12 padding0">
            <div class="table-responsive">
                <table class="table table-bordered table-condensed">
                    <tr>
                        <th width="10%" class="active">{!! lang('order.product') !!}</th>
                        <th width="5%">{!! lang('size.normal_size') !!}</th>
                        <th width="5%">{!! lang('order.hsn_code') !!}</th>
                       {{-- <th width="10%">{!! lang('order.unit') !!}</th>--}}
                        <th width="5%">{!! lang('order.gst') !!}</th>
                        {{--<th width="10%">{!! lang('order.mrp') !!}</th>--}}
                        <th width="5%">{!! lang('order.quantity') !!}</th>
                        <th width="5%">{!! lang('order.price') !!}</th>
                        <th width="5%">{!! lang('order.amount') !!}</th>


                        {{--<th >Size</th>
                        <th>{!! lang('order.hsn_code') !!}</th>
                        <th>{!! lang('order.unit') !!}</th>
                        <th>{!! lang('order.gst') !!}</th>
                        <th width="10%">{!! lang('order.mrp') !!}</th>
                        <th>{!! lang('order.quantity') !!}</th>--}}
                    </tr>
                    {{--<tr>
                        <td rowspan="2">s44</td>
                        <td>s1</td>
                        <td>s2</td>
                        <td>s3</td>
                        <td>s4</td>
                        <td>s5</td>
                        <td>s5</td>
                    </tr>
                    <tr>
                        <td>s44</td>
                        <td>s1</td>
                        <td>s2</td>
                        <td>s3</td>
                        <td>s4</td>
                        <td>s5</td>
                    </tr>
                    <tr>
                        <td rowspan="1">s44</td>
                        <td>s1</td>
                        <td>s2</td>
                        <td>s3</td>
                        <td>s4</td>
                        <td>s5</td>
                    </tr>--}}

                    @foreach($products as $pKey => $product)
                        <?php $count=1; ?>

                        @foreach($items as $ikey => $item)
                            @if($item['product_id']==$product['product_id'])
                                @if($count==1)
                                    <tr>
                                        <td rowspan="{!! $itemCountProductWise[$product['product_id']]  !!}">{!! $product->name !!}</td>
                                        <td>{!! $item->normal_size !!}</td>
                                        <td>{!! $item->hsn_code !!}</td>
                                        <td>{!! $item->tax_group !!}</td>
                                        <td>{!! $item->quantity !!}</td>
                                        <td>{!! $item->price !!}</td>
                                        <td>{!! $item->quantity*$item->price !!}</td>
                                    </tr>
                                        <?php $count++; ?>

                            @else
                                <tr>

                                    <td>{!! $item->normal_size !!}</td>
                                    <td>{!! $item->hsn_code !!}</td>
                                    <td>{!! $item->tax_group !!}</td>
                                    <td>{!! $item->quantity !!}</td>
                                    <td>{!! $item->price !!}</td>
                                    <td>{!! $item->quantity*$item->price !!}</td>

                                </tr>
                                @endif
                            @endif
                            @endforeach





                    @endforeach
                    <tr>
                        <td colspan="5">&nbsp;</td>
                        <th>Sub Total:</th>
                        <td>{!! $result->gross_amount !!}</td>
                    </tr>
                    <tr>
                        <td colspan="5">&nbsp;</td>
                        <th>Other Charges:</th>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td colspan="5">&nbsp;</td>
                        <th>Total Sale Amount:</th>
                        <td>{!! $result->gross_amount !!}</td>
                    </tr>







                </table>
            </div>
        </div>
    </div>
</div>