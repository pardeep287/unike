<?php

namespace App\Http\Controllers\Api\V1;

use App\Cart;
use App\CartProducts;
use App\CartProductSizes;
use App\Order;
use App\OrderProducts;
use App\OrderProductSizes;
use App\Product;
use App\ProductCost;
use App\ProductSizes;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiOrderController extends Controller
{
    public function getOrderDetails($order_id)
    {
        try{

            $result = [];
            //$orderDetails = (new Order)->find($order_id);
            $orderDetails = (new Order)->findByOrderIdNew($order_id);
            //dd($orderDetails->toArray(),$order_id,authUser());
            //$orders = (new Order)->findByUserId(authUserId(),8,8);
            
            if (!$orderDetails) {
                return apiResponse(false, 404, lang('messages.not_found', lang('order.order')));
            }
            $UserID = $orderDetails['mr_id'];
            $cartID = $orderDetails['cart_id'];
            $user_buyer_id = $orderDetails->customer_id;
            //dd($orderDetails->toArray(),$UserID,$cartID,$user_buyer_id);

            
            $orderPData=(new OrderProducts())->getProductsByOrderId($order_id);
            //dd($orderPData->toArray());
            if(count($orderPData)>0) {
                foreach ($orderPData as $key=>$opData) {
                    $productId=$opData['product_id'];
                    //get Cart Product Details Like name and image
                    $ProductDetailsArray=(new Product)->getProductDetailOnly($productId);
                    //dd($ProductDetailsArray);
                    //get size price and quantity based on CartId and ProductID
                    $allCartProductSize=(new OrderProductSizes)->getInvoiceItems(['order_id' => $order_id]);
                   // dd($allCartProductSize);
                    $finalSizeDataProductWise=[];
                    if($allCartProductSize){
                        foreach ($allCartProductSize as $allSizeData){
                            //dd($allSizeData);
                            if($allSizeData->product_id == $productId) {

                                $finalSizeDataProductWise[] = [
                                    'order_size_id' => $allSizeData->id,
                                    'normal_size' => getSizeName($allSizeData->size_id),
                                    'quantity' => $allSizeData->quantity,
                                    'hsn_code' => $allSizeData->hsn_code,
                                    'per_quantity_price' => $allSizeData->price,
                                    'total_price' => $allSizeData->total_price,
                                     //'cgst'   => $allSizeData->cgst_amount,
                                     //'sgst'   => $allSizeData->sgst_amount,
                                    //'igst'   => $allSizeData->igst_amount,
                                   // 'price_with_tax'=> $total_price+$cgst_price+$sgst_price,
                                ];
                            }//if ends
                        }//foreach Ends
                    }//if ends

                    $dirName = ROOT . \Config::get('constants.UPLOADS-PRODUCT').$ProductDetailsArray->product_id.'/';
                    $urlName = url(\Config::get('constants.UPLOADS-PRODUCT').$ProductDetailsArray->product_id.'/'.$ProductDetailsArray->p_image);
                    $ProductDetailsArrayNew[] = [
                        'order_product_id'=> $opData['id'],
                        'product_name'   => $ProductDetailsArray->name,
                        //'product_tax'    => $tax_Detail,
                        'p_image'        => file_exists($dirName.$ProductDetailsArray->p_image)?$ProductDetailsArray->p_image:null,
                        'path'           => $urlName,
                        'size_data'      => $finalSizeDataProductWise,

                    ];

                    //$allCartProductSize=(new OrderProductSizes)->getCartProductAllSize($cartID,$productId);
                    //dd($orderPData->toArray(),$allCartProductSize->toArray());
                }


            }
            else{
                return apiResponse(false, 404, lang('order.no_order', lang('product.product')));
            }
            //dd($orderDetails->toArray());
            if($orderDetails['customer_id']){
                $result = [
                    'order_id' => $order_id,
                    //'cart_id' => $cartID,
                    'customer_id' => $orderDetails['customer_id'],
                    'customer_name' => $orderDetails['customer_name'],
                    'mr_id' => $orderDetails['mr_id'],
                    'mr_name' => $orderDetails['mr_name'],
                    'order_number' => $orderDetails['order_number'],
                    'order_date' => ($orderDetails['order_date'] == '') ? null : dateFormat('d-m-Y', $orderDetails['order_date']),
                    'order_product_details' => $ProductDetailsArrayNew,
                    'subtotal' => $orderDetails['gross_amount'],
                    //'round_off_value'   => number_format($round_off_value,2),
                    //'subtotal_cgst'   => $Subtotal_cgst,
                    //'subtotal_sgst'   => $Subtotal_sgst,
                    // 'subtotal_igst'   => $Subtotal_igst,
                    // 'nettotal'   => $Subtotal+$Subtotal_cgst+$Subtotal_sgst,

                ];


            }
            else {
                $result = [
                    'order_id' => $order_id,
                    //'cart_id' => $cartID,
                    'customer_id' => $orderDetails['mr_id'],
                    'customer_name' => $orderDetails['mr_name'],
                    'mr_id' => $orderDetails['customer_id'],
                    'mr_name' => $orderDetails['customer_name'],
                    'order_number' => $orderDetails['order_number'],
                    'order_date' => ($orderDetails['order_date'] == '') ? null : dateFormat('d-m-Y', $orderDetails['order_date']),
                    'order_product_details' => $ProductDetailsArrayNew,
                    'subtotal' => $orderDetails['gross_amount'],
                    //'round_off_value'   => number_format($round_off_value,2),
                    //'subtotal_cgst'   => $Subtotal_cgst,
                    //'subtotal_sgst'   => $Subtotal_sgst,
                    // 'subtotal_igst'   => $Subtotal_igst,
                    // 'nettotal'   => $Subtotal+$Subtotal_cgst+$Subtotal_sgst,

                ];

            }

                return apiResponse(true, 200 , null, [], $result);
            
        }
        catch (Exception $exception) {
            return apiResponse(false, 500, lang('messages.server_error'));
        }

    }


    public function getAllOrders($page){
        try {
                        
            $result = [];
            $inputs = \Input::all();
            //$page = 1;
            if (isset($inputs['page']) && (int)$inputs['page'] > 0) {
                $page = $inputs['page'];
            }

            $perPage = 20;
            if (isset($inputs['perpage']) && (int)$inputs['perpage'] > 0) {
                $perPage = $inputs['perpage'];
            }
            $orders=[];
            $start = ($page - 1) * $perPage;
            //for admin view
            if(authUser()->role_id == 1){
                $orders = (new Order)->getOrdersNew([], $start, $perPage);
                //dd($orders->toArray());
                if(count($orders) == 0) {
                    return apiResponse(false, 404, lang('order.no_order', lang('order.order')));
                }
                foreach ($orders as $order) {

                        $result[] = [
                            'order_id'      => $order['id'],
                            'customer_id'   => isset($order['customer_id'])?$order['customer_id']:$order['mr_id'],
                            'customer_name' => isset($order['customer_name'])?$order['customer_name']:$order['mr_name'],
                            'mr_id'         => isset($order['customer_id'])?$order['mr_id']:null,
                            'mr_name'       => isset($order['customer_name'])?$order['mr_name']:null,
                            'order_number'  => $order['order_number'],
                            'order_date'    => ($order['order_date'] == '')?null:dateFormat('d-m-Y', $order['order_date']),
                            'gross_amount'  => $order['gross_amount'],
                            //'status'        => $order['status'],
                        ];


                }
            }
            //for agent view
            else if (authUser()->role_id == 3){
                $orders = (new Order)->findByUserIdNew(authUserId(), $start, $perPage);
                //dd($orders->toArray());
                if(count($orders) == 0) {
                    return apiResponse(false, 404, lang('order.no_order', lang('order.order')));
                }
                foreach ($orders as $order) {

                    $result[] = [
                        'order_id'      => $order['id'],
                        'customer_id'   => $order['customer_id'],
                        'customer_name' => $order['customer_name'],
                        'mr_id'         => $order['mr_id'],
                        'mr_name'       => $order['mr_name'],
                        'order_number'  => $order['order_number'],
                        'order_date'    => ($order['order_date'] == '')?null:dateFormat('d-m-Y', $order['order_date']),
                        'gross_amount'  => $order['gross_amount'],
                        //'status'        => $order['status'],
                    ];


                }
            }

            else {
                $orders = (new Order)->findByUserIdNew(authUserId(), $start, $perPage);
                //dd($orders->toArray());

                if(count($orders) == 0) {
                    return apiResponse(false, 404, lang('order.no_order', lang('order.order')));
                }


                foreach ($orders as $order) {
                    $result[] = [
                        'order_id'      => $order['id'],
                        'customer_id'   => $order['mr_id'],
                        'customer_name' => $order['mr_name'],
                        'mr_id'         => $order['customer_name'],
                        'mr_name'       => $order['customer_id'],
                        'order_number'  => $order['order_number'],
                        'order_date'    => ($order['order_date'] == '')?null:dateFormat('d-m-Y', $order['order_date']),
                        'gross_amount'  => $order['gross_amount'],
                        //'status'        => $order['status'],
                    ];
                }
            }

            //dd($orders->toArray());

            return apiResponse(true, 200 , null, [], $result);
        }
        catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }

    public function totalOrderCount(){
        try {

            $result = [];
            $inputs = \Input::all();
            if(authUser()->role_id != 1)
            {
                return apiResponse(false, 404, lang('order.admin_access'));
            }

            $currentMonth=date("m",strtotime(currentDate()));
            //dd($currentMonth);
            $orders = (new Order)->monthWiseMrOrderCount(['month'=>$currentMonth]);
            $mrWiseOrder = (new Order)->monthWiseMrOrder(['month'=>$currentMonth]);
            //dd($mrWiseOrder->toArray());
            $final=[];
            foreach ($mrWiseOrder as $order){
                $final[]=[
                    'user_id'       => $order->user_id,
                    'user_name'     => $order->username,
                   // 'user_name'     => getUsername($order->user_id),
                    'total_amount'  => $order->total_amount,
                    'count'         => $order->count,
                ];
            }
            //dd($mrWiseOrder->toArray(),$final);

            $result=[
                'total_amount' => isset($orders['total_amount'])?$orders['total_amount']:0,
                'orders_count' => $orders['orders_count'],
                'current_month_mr_orders' => $final,
            ];
            return apiResponse(true, 200 , null, [], $result);
        }
        catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }

    public function filterOrder($page){
        try {

            $result = [];
            $inputs = \Input::all();


            //$page = 1;
            if (isset($inputs['page']) && (int)$inputs['page'] > 0) {
                $page = $inputs['page'];
            }

            $perPage = 20;
            if (isset($inputs['perpage']) && (int)$inputs['perpage'] > 0) {
                $perPage = $inputs['perpage'];
            }

            $start = ($page - 1) * $perPage;

            if(authUser()->role_id == 2){
                return apiResponse(false, 404, 'User no filters set');
            }
            if(authUser()->role_id == 3){
                $inputs['mr_id'] =$inputs['customer_id'];
                $inputs['customer_id']=authUserId();
                //unset($inputs['mr_id']);
            }
            if(authUser()->role_id == 1){
                $value=$inputs['mr_id'];
                $inputs['mr_id'] =$inputs['customer_id'];
                $inputs['customer_id']=$value;
                //unset($inputs['mr_id']);
            }

            $data = (new Order)->getOrdersNew($inputs, $start,  $perPage);
            //dd($inputs,$data->toArray());

            if(isset($data) && count($data) > 0) {

                foreach ($data as $order) {
                    if(!$order['customer_id']){
                        $result[] = [
                            'order_id' => $order['id'],
                            'customer_id' => $order['mr_id'],
                            'customer_name' => $order['mr_name'],
                            'mr_id' => $order['customer_id'],
                            'mr_name' => $order['customer_name'],
                            'order_number' => 'UNK-' . $order['order_number'],
                            'order_date' => ($order['order_date'] == '') ? null : dateFormat('d-m-Y', $order['order_date']),
                            'gross_amount' => $order['gross_amount'],
                            //'status'        => $order['status'],
                        ];
                    }
                    else {
                        $result[] = [
                            'order_id' => $order['id'],
                            'customer_id' => $order['customer_id'],
                            'customer_name' => $order['customer_name'],
                            'mr_id' => $order['mr_id'],
                            'mr_name' => $order['mr_name'],
                            'order_number' => 'UNK-' . $order['order_number'],
                            'order_date' => ($order['order_date'] == '') ? null : dateFormat('d-m-Y', $order['order_date']),
                            'gross_amount' => $order['gross_amount'],
                            //'status'        => $order['status'],
                        ];

                    }
                }
                return apiResponse(true, 200 , null, [], $result);
            }
            else{
                return apiResponse(false, 404, lang('order.no_order', lang('order.order')));
            }


            //$result[]=count($data)>0?$data:'';

        }
        catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }


}
