@extends('layouts.admin')

@section('script')
    <script src="https://cdn.anychart.com/js/7.13.0/anychart-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.anychart.com/css/latest/anychart-ui.min.css">
@endsection


@section('content')
<div id="page-wrapper">
    <div class="row">
        {{--<div class="col-lg-12">
            <h1 class="page-header">Dashboard</h1>
        </div>--}}
        <div class="col-md-12">
            <div class="page-header margintop8">
                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                    <h1 class=" margintop10">
                        {!! lang('common.dashboard') !!}
                    </h1>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                    <h1 class=" margintop10">
                        <div class="pull-right headind-right top-time-heading">
                            <i class="fa fa-clock-o"></i> <span id="time"></span>
                        </div>
                    </h1>
                </div>
                <div class="clearfix"></div>
            </div>

        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.igw -->
    <div class="row ">
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-tasks fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{!! count($totalOrderMonthWise) !!}</div>
                            <div>Monthly Orders!</div>
                        </div>
                    </div>
                </div>
                <a href="{!! route('order.index') !!}">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        {{--<div class="col-xs-1">
                            <i class="fa fa-tasks fa-2x"></i>
                        </div>--}}
                        <div class="col-xs-12 text-right">
                            <div class="huge">{!! isset($grossTotal)?number_format($grossTotal,0):null !!}</div>
                            <div>Monthly Amount!</div>
                        </div>
                    </div>
                </div>
                <a href="{!! route('order.index') !!}">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 hide">
            <div class="panel panel-yellow">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-shopping-cart fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">124</div>
                            <div>New Orders!</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 hide">
            <div class="panel panel-red">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-support fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">13</div>
                            <div>Support Tickets!</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!-- /.row -->
    <div class="row">

            <div class="col-md-6 col-xs-12">
                <?php $count = 1; ?>
                <h4><b>Latest Invoices</b></h4>
                <table class="table table-bordered" style="margin-top:10px;background: #FFFFFF;">
                    <tr>
                        <th>{!! lang('common.id') !!}</th>
                        <th width="20%">{!! lang('customer.customer') !!}</th>
                        <th width="20%">{!! lang('user.mr_name') !!}</th>
                        <th>{!! lang('order.ord_number') !!}</th>
                        <th>{!! lang('order.ord_date') !!}</th>
                        <th>{!! lang('order.amount') !!}</th>
                    </tr>
                    @if(count($monthWiseLatestOrder) > 0)
                        @foreach($monthWiseLatestOrder  as $order)
                            <tr>
                                <td>{!! $count !!}</td>
                                <td>{!! isset($order->customer_name)?$order->customer_name:$order->mr_name  !!}</td>
                                <td>{!! isset($order->customer_name)?$order->mr_name:'-'  !!}</td>
                                <td class="text-center">{!! 'UNK - '.$order->order_number !!}</td>
                                <td>{!! convertToLocal($order->order_date, 'd.m.Y')  !!}</td>
                                <td>{!! numberFormat($order->gross_amount) !!}</td>
                            </tr>
                            <?php $count++; ?>
                        @endforeach
                        <tr>
                            <td colspan="6">
                                <a href="{!! route('order.index') !!}" class="btn btn-block btn-primary">View All</a>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>

            <div class="col-md-6 col-xs-12">
                <?php $sNumber = 1; ?>
                <h4><b>MR Orders</b></h4>
                <table class="table table-bordered" style="margin-top:10px;background: #FFFFFF;">
                    <tr>
                        <th>{!! lang('common.id') !!}</th>
                        <th width="30%">{!! lang('user.mr_name') !!}</th>
                        <th>{!! lang('order.gross_amount') !!}</th>
                       {{-- <th>{!! lang('order.order_date') !!}</th>--}}
                        <th>{!! lang('order.ord_count') !!}</th>
                    </tr>
                    @if(isset($monthWiseTotalOrderMrAgent)&& count($monthWiseTotalOrderMrAgent) > 0)
                        @foreach($monthWiseTotalOrderMrAgent  as $detail)
                            <tr>
                                <td>{!! $sNumber !!}</td>
                                <td>
                                    {{--<a title="{!! lang('common.edit') !!}" href="{{ route('order.edit', [$detail['id']]) }}">
                                        {!! $detail['user_name'] !!}
                                    </a>--}}
                                    {!! $detail['user_name'] !!}
                                </td>
                                <td class="text-center">{!! $detail['total_amount'] !!}</td>
                                {{--<td>{!! convertToLocal($detail->order_date, 'd.m.Y')  !!}</td>--}}
                                <td>{!! $detail['count'] !!}</td>
                            </tr>
                            <?php $sNumber++; ?>
                        @endforeach
                        <tr>
                            <td colspan="6">
                                <a href="{!! route('order.index', ['s' => 0]) !!}" class="btn btn-block btn-primary">View All</a>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>

<div class="paddingt"></div>
    </div>
</div>
<!-- /#page-wrapper -->
@endsection
