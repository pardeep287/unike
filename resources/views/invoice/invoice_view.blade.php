<div class="panel-heading">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <i class="fa fa-external-link-square"></i> &nbsp;
        {!! lang('invoice.invoice_detail') !!}
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding0" id="right-actionbtn-list">
        @if( hasMenuRoute('customer-invoice.drop') || isAdmin() )
        <a title="{!! lang('common.delete') !!}" data-redirect="{!! route('invoice.index') !!}" class="btn btn-sm pull-right hidden btn-danger __drop" data-route="{!! route('invoice.drop', [$result->id]) !!}" data-message="{!! lang('messages.sure_delete', string_manip(lang('invoice.invoice'))) !!}" href="javascript:void(0)">
            <i class="fa fa-trash"></i>
            {!! lang('invoice.delete') !!}
        </a>
        @endif
        @if(hasMenuRoute('invoice.update') || isAdmin())
        <a title="{!! lang('common.modify') !!}" href="javascript:void(0)" class="btn btn-sm btn-primary pull-right _modify">
            <i class="fa fa-edit"></i>
            {!! lang('common.modify') !!}
        </a>
        @endif
        @if( hasMenuRoute('invoice.invoice-print') || isAdmin() )
        <a title="{!! lang('common.print') !!}" class="btn btn-sm pull-right marginright10 btn-danger" href="{{ route('invoice.invoice-print', [$result->id]) }}">
            <i class="fa fa-print"></i>
            {!! lang('invoice.print') !!}
        </a>
        @endif
        @if( hasMenuRoute('invoice.invoice-pdf') || isAdmin() )
        <a title="{!! lang('common.generate_pdf') !!}" class="btn btn-sm pull-right marginright10 btn-info" target="_blank" href="{{ route('invoice.invoice-pdf', [$result->id]) }}">
            <i class="fa fa-file-pdf-o"></i>
            {!! lang('invoice.pdf') !!}
        </a>
        @endif

        @if( hasMenuRoute('invoice.send-email') || isAdmin() )
            <a data-title="{!! lang('common.send_email') !!}" class="btn btn-sm pull-right dEdit marginright10 btn-success" href="javascript:void(0)" data-route="{!! route('invoice.send-email', [$result->id]) !!}">
                <i class="fa fa-send"></i>
                {!! lang('common.send_email') !!}
            </a>
        @endif
    </div>
    <div class="clearfix"></div>
</div>
<div class="panel-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group marginbottom20">
                {!! Form::label('customer_name', lang('invoice.customer_name'), array('class' => 'col-sm-2 control-label')) !!}
                <div class="col-sm-2 paddingtop10">
                    <p>{!! $result->customer_name !!}</p>
                </div>

               {{-- {!! Form::label('order_number', lang('invoice.invoice_number'), array('class' => 'col-sm-2 control-label')) !!}
                <div class="col-sm-1 paddingtop10">
                    <p>{!! $result->invoice_number !!}</p>
                </div>--}}
                {!! Form::label('order_number', lang('invoice.order_number'), array('class' => 'col-sm-2 control-label')) !!}
                <div class="col-sm-1 paddingtop10">
                    <p>{!! $result->order_number !!}</p>
                </div>

                {{--{!! Form::label('invoice_date', lang('invoice.invoice_date'), array('class' => 'col-sm-2 control-label')) !!}
                <div class="col-sm-2 paddingtop10">
                    <p>
                        {!! convertToLocal($result->order_date, 'd-m-Y') !!}
                        <span class="hidden">{!! convertToLocal($result->order_date, 'd-m-Y H:i:s') !!}</span>
                    </p>
                </div>--}}

                {!! Form::label('order_date', lang('invoice.order_date'), array('class' => 'col-sm-2 control-label')) !!}
                <div class="col-sm-2 paddingtop10">
                    <p>{!! dateFormat('d-m-Y', $result->order_date) !!}</p>
                </div>
            </div>
        </div>

        <div class="col-md-12 padding0">
            <div class="table-responsive">
                <table class="table table-bordered table-condensed">
                    <tr>
                        <th width="18%" class="active">{!! lang('invoice.product') !!}</th>
                        <th width="10%">{!! lang('invoice.size') !!}</th>
                        <th width="10%">{!! lang('invoice.hsn_code') !!}</th>
                       {{-- <th width="10%">{!! lang('invoice.unit') !!}</th>--}}
                        <th width="10%">{!! lang('invoice.gst') !!}</th>
                        {{--<th width="10%">{!! lang('invoice.mrp') !!}</th>--}}
                        <th width="10%">{!! lang('invoice.quantity') !!}</th>
                        <th width="10%">{!! lang('invoice.price') !!}</th>
                        <th width="10%">{!! lang('invoice.amount') !!}</th>


                        {{--<th >Size</th>
                        <th>{!! lang('invoice.hsn_code') !!}</th>
                        <th>{!! lang('invoice.unit') !!}</th>
                        <th>{!! lang('invoice.gst') !!}</th>
                        <th width="10%">{!! lang('invoice.mrp') !!}</th>
                        <th>{!! lang('invoice.quantity') !!}</th>--}}
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

                        @foreach($items as $ikey => $item)
                            @if($item['product_id']==$product['product_id'])
                                @if($ikey==0)
                                    <tr>
                                        <td rowspan="{!! $itemCountProductWise[$product['product_id']]  !!}">{!! $product->product_id !!}</td>
                                        <td>{!! $item->normal_size !!}</td>
                                        <td>{!! $item->hsn_code !!}</td>
                                        <td>{!! $item->tax_group !!}</td>
                                        <td>{!! $item->quantity !!}</td>
                                        <td>{!! $ikey !!}</td>
                                        <td>{!! $ikey !!}</td>
                                    </tr>

                            @else
                                <tr>
                                    {{--<td>&nbsp;</td>--}}
                                    <td>{!! $item->normal_size !!}</td>
                                    <td>{!! $item->hsn_code !!}</td>
                                    <td>{!! $item->tax_group !!}</td>
                                    <td>{!! $item->quantity !!}</td>
                                    <td>{!! $item->price !!}</td>
                                    <td>{!! $ikey !!}</td>
                                </tr>
                                @endif
                            @endif
                            @endforeach





                    @endforeach
                    <tr>
                        <td colspan="5">&nbsp;</td>
                        <th>Total Sale Amount</th>
                        <td>1000</td>
                    </tr>







                </table>
            </div>
        </div>
    </div>
</div>