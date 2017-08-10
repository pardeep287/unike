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
            $orderDetails = (new Order)->findByOrderId($order_id);
            //dd($orderDetails,$order_id,authUser());
            //$orders = (new Order)->findByUserId(authUserId(),8,8);
            
            if (!$orderDetails) {
                return apiResponse(false, 404, lang('messages.not_found', lang('order.order')));
            }
            $UserID = $orderDetails['user_id'];
            $cartID = $orderDetails['cart_id'];
            $user_buyer_id = $orderDetails->user_buyer_id;
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
                    $finalSizeDataProductWise=[];
                    if($allCartProductSize){
                        foreach ($allCartProductSize as $allSizeData){
                            //dd($allSizeData);
                            if($allSizeData->product_id == $productId) {

                                $finalSizeDataProductWise[] = [
                                    'order_size_id' => $allSizeData->id,
                                    'normal_size' => getSizeName($allSizeData->size_id),
                                    'quantity' => $allSizeData->quantity,
                                    'hsn_code' => '2525',
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
                dd('No Product Found');
            }

            $result = [
                'order_id' => $order_id,
                //'cart_id' => $cartID,
                'user_id'       => $orderDetails['user_id'],
                'user_name' => $orderDetails['mr_name'],
                'user_buyer_id' => $orderDetails['user_buyer_id'],
                'buyer_name'    => $orderDetails['customer_name'],
                'order_number'  => $orderDetails['order_number'],
                'order_date'    => ($orderDetails['order_date'] == '')?null:dateFormat('d-m-Y', $orderDetails['order_date']),
                'order_product_details' => $ProductDetailsArrayNew,
                'subtotal'          => $orderDetails['gross_amount'],
                //'round_off_value'   => number_format($round_off_value,2),
                //'subtotal_cgst'   => $Subtotal_cgst,
                //'subtotal_sgst'   => $Subtotal_sgst,
                // 'subtotal_igst'   => $Subtotal_igst,
                // 'nettotal'   => $Subtotal+$Subtotal_cgst+$Subtotal_sgst,

            ];

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

            $start = ($page - 1) * $perPage;
            $orders = (new Order)->findByUserId(authUserId(), $start, $perPage);
            if(count($orders) == 0) {
                return apiResponse(false, 404, lang('order.no_order', lang('order.order')));
            }
            //dd($orders->toArray());

            foreach ($orders as $order) {
                $result[] = [
                    'order_id'      => $order['id'],
                    'user_id'       => $order['user_id'],
                    'user_name' => $order['mr_name'],
                    'user_buyer_id' => $order['user_buyer_id'],
                    'buyer_name'    => $order['customer_name'],
                    'order_number'  => $order['order_number'],
                    'order_date'    => ($order['order_date'] == '')?null:dateFormat('d-m-Y', $order['order_date']),
                    'gross_amount'  => $order['gross_amount'],
                    //'status'        => $order['status'],
                ];
            }
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
            foreach ($mrWiseOrder as $order){
                $final[]=[
                    'user_id'       => $order->user_id,
                    'user_name'     => getUsername($order->user_id),
                    'total_amount'  => $order->total_amount,
                    'count'         => $order->count,
                ];
            }
            //dd($mrWiseOrder->toArray(),$final);

            $result=[
                'total_amount' =>$orders['total_amount'],
                'orders_count' =>$orders['orders_count'],
                'current_month_mr_orders' =>$final,
            ];
            return apiResponse(true, 200 , null, [], $result);
        }
        catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }


}
