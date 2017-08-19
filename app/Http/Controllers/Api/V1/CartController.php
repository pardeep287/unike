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

class CartController extends Controller
{
    public function userCartDetail($id = null)
    {
        try{
            //$inputs = $request->all();
            $result = [];
            $UserID = (new User)->find($id)['id'];

            if(!$UserID || $UserID!=authUserId()){
                return apiResponse(false, 404, lang('user.user_not'));
            }
            //dd($UserID);

            $cartID = (new Cart)->findByUserId($id)['id'];
            //$cartID=null;
            if($cartID) {
                $cartPData=CartProducts::where('cart_id',$cartID)->where('status',1)->get(['id','product_id']);

                $ProductDetailsArray=[];
                $ProductDetailsArrayNew=[];
                $total_price =  $Subtotal= $Subtotal_cgst= $Subtotal_sgst= $Subtotal_igst=0;
                foreach ($cartPData as $key=>$cpData)
                {
                    $productId=$cpData['product_id'];
                    //get Cart Product Details Like name and image
                    $ProductDetailsArray=(new Product)->getProductDetailOnly($productId);
                    //dd($ProductDetailsArray);
                    //get size price and quantity based on CartId and ProductID
                    $allCartProductSize=(new CartProductSizes())->getCartProductAllSize($cartID,$productId);
                       //dd($allCartProductSize->toArray());
                    $finalSizeData=[];
                    $tax_Detail='';
                    $total_amount =$total_cgst_amount = $total_sgst_amount= $total_igst_amount=0;
                    if($allCartProductSize){
                        $tax_Detail=getTaxPercentage($ProductDetailsArray->tax_id);
                       // dd($tax_Detail);
                        $cgst=$tax_Detail['cgst_rate'];
                        $sgst=$tax_Detail['sgst_rate'];
                        $igst=$tax_Detail['igst_rate'];

                        $allSizeData=[];

                        foreach ($allCartProductSize as $allSizeData){
                          //  dump( $allSizeData->toArray(),$allCartProductSize->toArray());
                            $quantity=$allSizeData->quantity;
                            $price=$allSizeData->price;
                            $total_price=$quantity*$price;
                            $cgst_price=($cgst / 100) * $total_price;
                            $sgst_price=($sgst / 100) * $total_price;
                            $igst_price=($igst / 100) * $total_price;




                            $finalSizeData[] = [
                                'cart_size_id'  => $allSizeData->id,
                                'normal_size'   => getSizeName($allSizeData->size_id),
                                'quantity'      => $quantity,
                                'per_quantity_price'         => $price,
                                'total_price'   => $total_price,
                               // 'cgst'   => $cgst_price,
                               // 'sgst'   => $sgst_price,
                                //'igst'   => $igst_price,
                                //'price_with_tax'=> $total_price+$cgst_price+$sgst_price,
                            ];
                            $total_amount += $total_price;
                            $total_cgst_amount += $cgst_price;
                            $total_sgst_amount += $sgst_price;
                            $total_igst_amount += $igst_price;
                        }//foreach ends allSize
                    }//if ends
                    // dd($finalSizeData);

                    //check if exists in folder
                    $Subtotal += $total_amount;
                    $Subtotal_cgst += $total_cgst_amount;
                    $Subtotal_sgst += $total_sgst_amount;
                    $Subtotal_igst += $total_igst_amount;

                    $dirName = ROOT . \Config::get('constants.UPLOADS-PRODUCT').$ProductDetailsArray->product_id.'/';
                    $urlName = url(\Config::get('constants.UPLOADS-PRODUCT').$ProductDetailsArray->product_id.'/'.$ProductDetailsArray->p_image);
                    $ProductDetailsArrayNew[] = [
                        'cart_product_id'=> $cpData['id'],
                        'product_name'   => $ProductDetailsArray->name,
                        //'product_tax'    => $tax_Detail,
                        'p_image'        => file_exists($dirName.$ProductDetailsArray->p_image)?$ProductDetailsArray->p_image:null,
                        'path'           => $urlName,
                        'size_data'      => $finalSizeData,

                    ];
                }
                $SubtotalN= number_format(round($Subtotal* 2, 0)/2,2);
                $round_off_value= $SubtotalN-$Subtotal;
                $cartID = (new Cart)->findByUserId($id)['id'];
                $cartCount='';
                if($cartID) {
                    //$cartCount = CartProducts::where('cart_id', $cartID)->count();
                    $cartCount = (new CartProducts)->getCartProductsCount($cartID);
                }
                $result = [
                    'cart_id' => $cartID,
                    'cart_Count' => $cartCount,
                    'cart_product_details' => $ProductDetailsArrayNew,
                    'subtotal'          => $SubtotalN,
                    'round_off_value'   => number_format($round_off_value,2),
                    //'subtotal_cgst'   => $Subtotal_cgst,
                    //'subtotal_sgst'   => $Subtotal_sgst,
                   // 'subtotal_igst'   => $Subtotal_igst,
                   // 'nettotal'   => $Subtotal+$Subtotal_cgst+$Subtotal_sgst,

                ];
                //dd($cartPData->toArray(),$cartID,$UserID,$ProductDetailsarray);
                /*foreach( $products as $product ) {
                    $dirName = ROOT . \Config::get('constants.UPLOADS-PRODUCT').$product->id.'/';
                    $urlName = url(\Config::get('constants.UPLOADS-PRODUCT').$product->id.'/'.$product->p_image);
                    $result[] = [
                        'id'             => $product->id,
                        'name'           => $product->name,
                        'p_image'        => file_exists($dirName.$product->p_image)?$product->p_image:null,
                        'path'           => $urlName,
                    ];
                }*/
                return apiResponse(true, 200 , null, [], $result);
            }
            else {
                return apiResponse(false, 404, lang('cart.no_items'));
            }
        }
        catch (Exception $exception) {
            return apiResponse(false, 500, lang('messages.server_error'));
        }

    }

    public function addToCart(Request $request)
    {
        try {
            \DB::beginTransaction();
                $inputs = $request->all();
            $result = [];
            //dd($inputs,authUser());


            $validator = ( new Cart)->validate($inputs);
            //$validator = (new SaleOrder)->validateSaleOrder($inputs, null, true, true);
            if ($validator->fails()) {
                return apiResponse(false, 406, errorMessages($validator->messages()));
            }

            $UserID = (new User)->find($inputs['user_id'])['id'];
            //dd($inputs,$UserDetails);

            if (!$UserID || $UserID != authUserId()) {
                //Check for MR Agent
                if(authUser()->role_id== 3){
                    $mrId=authUserId();
                }
                else {
                    return apiResponse(false, 404, lang('user.user_not'));
                }
            }
            $ProductID = (new Product)->find($inputs['product_id'])['id'];
            if (!$ProductID) {
                return apiResponse(false, 404, lang('products.product_not'));
            }
            //validate if size_id array == price array and quantity
            $sizeCount=count($inputs['size_id']);
            //$priceCount=count($inputs['price']);
            $quantityCount=count($inputs['quantity']);
            if($sizeCount!=$quantityCount){
                return apiResponse(false, 404, lang('cart.error_count'));
            }

            //check user id exists in cart table with status 0
            $cartDetails= (new Cart)->findByUserId($UserID);


            if(!$cartDetails){
                $cartMasterData = [
                    'user_id'   => $inputs['user_id'],
                    'cart_date' => currentDate(true),
                    'status'    => 0,
                    'created_by'=> authUserId(),
                ];
                //dd($cartMasterData);
                $cartId = (new Cart)->store($cartMasterData);
            }else{
                $cartId = $cartDetails->id;
            }



            //check if cartProduct has already Same Product
            $cartProductId='';
            if($cartId)
            {
                $cartProductIdArray = CartProducts::where('product_id', $ProductID)->where('cart_id',$cartId)->where('status',1)->get(['id','product_id']);
                //$cartProductIdArray=(new CartProducts);
                //dd($cartProductIdArray,$cartId,$ProductID);
                if(count($cartProductIdArray)>0){
                    foreach ($cartProductIdArray as $key=>$cartPData)
                    {
                        if($cartPData['product_id'] == $ProductID ) {
                            $cartProductId = $cartPData['id'];
                        }
                        else{
                            $cartProductData = [
                                'product_id' => $ProductID,
                                'cart_id' => $cartId,
                                'created_by' => authUserId(),
                            ];
                            $cartProductId = (new CartProducts)->store($cartProductData);
                        }
                    }// for ends
                }//if ends
                else{
                    $cartProductData = [
                        'product_id' => $ProductID,
                        'cart_id' => $cartId,
                        'created_by' => authUserId(),
                    ];
                    $cartProductId = (new CartProducts)->store($cartProductData);
                }
            }//if cart ends

            //check if any product size has been selected
            if (isset($inputs['size_id']) && count($inputs['size_id']) > 0)
            {
                $cartPSizeData=[];
                foreach($inputs['size_id'] as $key=>$sizeID) {
                    $sizeExist=(new ProductSizes)->find($sizeID);
                    //dd($sizeExist);

                    if($sizeExist && $sizeExist->product_id == $ProductID) {
                        //check size exixts or not
                        $cartPSizeArray = CartProductSizes::where('size_id', $sizeID)->where('cart_id', $cartId)->where('product_id', $ProductID)->first(['id', 'size_id']);
                        //dd($cartId,$cartProductId, $inputs,$sizeID,$cartPSizeArray,$inputs['size_id'][$key]);
                        if (!$cartPSizeArray) {
                            //if( $cartPSizeArray['size_id']!=$sizeID) {
                            $cartPSizeData = [
                                'cart_id' => $cartId,
                                'product_id' => $ProductID,
                                'size_id' => $inputs['size_id'][$key],
                                'quantity' => $inputs['quantity'][$key],
                                'price' => getSizePrice($inputs['size_id'][$key]),
                                //'price' => $inputs['price'][$key],
                                'created_by' => authUserId(),
                            ];
                            (new CartProductSizes)->store($cartPSizeData);
                            //}
                        } else {
                            //product size alreadt in cart
                            return apiResponse(false, 404, lang('cart.item_in_cart'));
                        }
                    }
                    else{
                        return apiResponse(false, 404, lang('cart.size_not_exist'));
                    }
                }// foreach ends
                //dd($cartId,$cartProductId, $inputs,$sizeID,$cartPSizeArray,$cartPSizeData);
                \DB::commit();
                $cartID = (new Cart)->findByUserId(authUserId())['id'];
                $cartCount=[];
                if($cartID) {
                    //$cartCount = CartProducts::where('cart_id', $cartID)->count();
                    $cartCount = (new CartProducts)->getCartProductsCount($cartID);
                }
                return apiResponse(true, 200, lang('cart.added', lang('cart.item')),[],$cartCount);
                //return apiResponse(true, 200 , null, [], $result);
            }
            else {
                return apiResponse(false, 404, lang('common.no_size_select'));
            }
        }
        catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }

    }

    public function deleteFromCart(Request $request)
    {
        try {
            \DB::beginTransaction();
            $inputs = $request->all();
            $result = [];

            $validator = ( new Cart)->validateCartDeleteItems($inputs);
            //$validator = (new SaleOrder)->validateSaleOrder($inputs, null, true, true);
            if ($validator->fails()) {
                return apiResponse(false, 406, errorMessages($validator->messages()));
            }
            $UserID = (new User)->find($inputs['user_id'])['id'];
            //dd($inputs);
            if (!$UserID || $UserID != authUserId()) {
                return apiResponse(false, 404, lang('user.user_not'));
            }
            $CartID = (new Cart)->find($inputs['cart_id'])['id'];
            if (!$CartID) {
                //return apiResponse(false, 404, lang('cart.cart'));
                return apiResponse(false, 404, lang('messages.not_found', lang('cart.cart')));
            }
            if(isset($inputs['cart_product_id']) && is_array($inputs['cart_product_id'])) {
                $ProductDetails = [];
                foreach ($inputs['cart_product_id'] as $productIds) {
                    $ProductDetails[] = (new CartProducts())->find($productIds);
                    //$ProductDetails[] = (new CartProducts())->getCartProduct($CartID, $productIds);
                }
                //dd($ProductDetails);
                foreach ($ProductDetails as $productData) {
                    if (!$productData) {
                        return apiResponse(false, 404, lang('messages.not_found', lang('products.product')));
                    }
                }
            }

            if(isset($inputs['cart_size_id']) && is_array($inputs['cart_size_id'])) {
                $cartSizeDetails = [];
                foreach ($inputs['cart_size_id'] as $cartSizeIDs) {
                    $cartSizeDetails[] = (new CartProductSizes)->find($cartSizeIDs);
                    //$ProductDetails[] = (new CartProducts())->getCartProduct($CartID, $productIds);
                }

                foreach ($cartSizeDetails as $cartSizeData) {
                    if (!$cartSizeData) {
                        return apiResponse(false, 404, lang('messages.not_found', lang('size.size')));
                    }
                }
            }


            //dd(count($ProductDetails));
            //check if Product Delete request only
            if (isset($ProductDetails) && count($ProductDetails)>0) {
                foreach ($ProductDetails as $productData) {

                    $ProductID = $productData->product_id;
                    //dd($ProductID);
                    $allCartPSizes = (new CartProductSizes)->getCartProductAllSize($CartID, $ProductID);
                    //dd($inputs,$allCartPSizes->toArray(),count($allCartPSizes));
                    if (isset($allCartPSizes) && count($allCartPSizes) > 0) {
                        //$deletedItems = [];
                        foreach ($allCartPSizes as $cpSize) {
                            $deletedItems = [
                                'status'     => 0,
                                'deleted_at' => convertToUtc(),
                                'deleted_by' => $UserID
                            ];
                            //dump($deletedItems,$cpSize->id);
                            (new CartProductSizes())->store($deletedItems, $cpSize->id);
                        }
                        //dd('i ma ');
                        //delete product (same cart id)on success of deletion of sizes
                        $deletedProduct = [
                            'status' => 0,
                            'deleted_at' => convertToUtc(),
                            'deleted_by' => $UserID
                        ];
                        //dd($deletedItems,$productData->id);
                        (new CartProducts)->store($deletedProduct, $productData->id);
                        //check Product of same cart has Products AND Update the CartMaster
                        /*if($CartID){
                            $bit=(new CartProducts)->getCartProductsCount($CartID);
                            //If cartProduct has no product,then update status to 2(deleted)
                            if($bit <= 0){
                                $cartUpdate = [
                                    'status' => 2,
                                    'updated_by' => $UserID,
                                    'updated_at' => convertToUtc(),
                                ];
                                (new Cart())->store($cartUpdate,$CartID);
                               // dd($bit,'else');
                            }//if ends()
                        }*/
                    }
                    else {
                        return apiResponse(true, 200, lang('messages.invalid_id', lang('products.product')));
                    }
                }// first-forloop($ProductDetails) Ends
            }//first-if($ProductDetails) ENDS

            //check if Product Size Delete Request only
            if(isset($cartSizeDetails) && count($cartSizeDetails)>0){

                foreach ($cartSizeDetails as $pSizeData) {

                    $ProductID = $pSizeData->product_id;
                    $cart_id = $pSizeData->cart_id;
                    //dd($pSizeData->toArray(),$ProductID,$cart_id);
                    $deleteItemsArray = [
                        'status'     => 0,
                        'deleted_at' => convertToUtc(),
                        'deleted_by' => $UserID
                    ];
                    //dd($deletedItems,$cpSize->id);
                    (new CartProductSizes)->store($deleteItemsArray, $pSizeData->id);

                    //check if same productId and Cart has items or not
                    $countSizeofSameProduct=(new CartProductSizes())->countSizeofSameProduct($CartID,$ProductID);
                    //dd($countSizeofSameProduct);
                    if($countSizeofSameProduct <= 0){
                        $cartProductId=(new CartProducts)->getCartProduct($cart_id,$ProductID)['id'];
                        //dd($cartProductId);
                        $cartProductUpdate = [
                            'status'     => 0,
                            'deleted_at' => convertToUtc(),
                            'deleted_by' => $UserID
                        ];
                        (new CartProducts)->store($cartProductUpdate,$cartProductId);
                    }
                /*else {
                    return apiResponse(true, 200, lang('messages.invalid_id', lang('products.product')));
                }*/
                }// first-forLoop($ProductDetails) Ends
            }//first-if($ProductDetails) ENDS

            //check Product of same cart has Products AND Update the CartMaster
            if($CartID){
                $bit=(new CartProducts)->getCartProductsCount($CartID);
                //If cartProduct has no product,then update status to 2(deleted)
                if($bit <= 0){
                    $cartUpdate = [
                        'status' => 2,
                        'updated_by' => $UserID,
                        'updated_at' => convertToUtc(),
                    ];
                    (new Cart())->store($cartUpdate,$CartID);
                    // dd($bit,'else');
                }//if ends()
            }


            \DB::commit();
            $cartID = (new Cart)->findByUserId(authUserId())['id'];
            $cartCount=[];
            if($cartID) {
                //$cartCount = CartProducts::where('cart_id', $cartID)->count();
                $cartCount = (new CartProducts)->getCartProductsCount($cartID);
            }
            return apiResponse(true, 200, lang('messages.deleted', lang('products.product')),[],$cartCount);
            //return apiResponse(true, 200 , null, [], $result);

        /*else {
                return apiResponse(false, 404, lang('common.no_size_select'));
            }*/
        }
        catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }

    }

    public function editCart(Request $request){
        try {
            \DB::beginTransaction();
            $inputs = $request->all();
            $result = [];

            $validator = ( new Cart)->validateCartEditItems($inputs);

            if ($validator->fails()) {
                return apiResponse(false, 406, errorMessages($validator->messages()));
            }
            $UserID = (new User)->find($inputs['user_id'])['id'];
            //dd($inputs);
            if (!$UserID || $UserID != authUserId()) {
                return apiResponse(false, 404, lang('user.user_not'));
            }
            $CartID = (new Cart)->find($inputs['cart_id'])['id'];
            if (!$CartID) {
                //return apiResponse(false, 404, lang('cart.cart'));
                return apiResponse(false, 404, lang('messages.not_found', lang('cart.cart')));
            }

            $sizeCount=count($inputs['cart_size_id']);
            //$priceCount=count($inputs['price']);
            $quantityCount=count($inputs['quantity']);
            if($sizeCount!=$quantityCount){
                return apiResponse(false, 404, lang('cart.error_count'));
            }

            if(isset($inputs['cart_size_id'])) {
                $cartSizeDetails = [];
                foreach ($inputs['cart_size_id'] as $cartSizeIDs) {
                    $cartSizeDetails[] = (new CartProductSizes)->find($cartSizeIDs);
                    //$ProductDetails[] = (new CartProducts())->getCartProduct($CartID, $productIds);
                }

                foreach ($cartSizeDetails as $cartSizeData) {
                    if (!$cartSizeData) {
                        return apiResponse(false, 404, lang('messages.not_found', lang('size.size')));
                    }
                }
            }


            //check if Product Size Delete Request only
            if(isset($cartSizeDetails) && count($cartSizeDetails)>0){
                foreach ($cartSizeDetails as $key=>$pSizeData) {
                    //$ProductID = $pSizeData->product_id;
                    //$cart_id = $pSizeData->cart_id;
                    $cartQuantity = $pSizeData->quantity;
                    $inputQuantity=$inputs['quantity'][$key];
                    if($inputQuantity != $cartQuantity) {
                        $updatePSizeArray = [
                            'quantity'   => $inputQuantity,
                            'updated_by' => $UserID
                        ];
                        (new CartProductSizes)->store($updatePSizeArray, $pSizeData->id);
                    }
                }// first-forLoop($ProductDetails) Ends
            }//first-if($ProductDetails) ENDS


            \DB::commit();
            $cartID = (new Cart)->findByUserId(authUserId())['id'];
            $cartCount=[];
            if($cartID) {
                //$cartCount = CartProducts::where('cart_id', $cartID)->count();
                $cartCount = (new CartProducts)->getCartProductsCount($cartID);
            }
            return apiResponse(true, 200, lang('messages.updated', lang('products.product')),[],$cartCount);
            //return apiResponse(true, 200 , null, [], $result);

            /*else {
                    return apiResponse(false, 404, lang('common.no_size_select'));
                }*/
        }
        catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }

    }

    public function checkOutCart(Request $request){
        try {
            \DB::beginTransaction();
            $inputs = $request->all();
            $result = [];
            $validator = ( new Order)->validate($inputs);
            //$validator = (new SaleOrder)->validateSaleOrder($inputs, null, true, true);
            if ($validator->fails()) {
                return apiResponse(false, 406, errorMessages($validator->messages()));
            }

            $UserID = (new User)->find($inputs['user_id'])['id'];
            //dd($inputs);
            if (!$UserID || $UserID != authUserId()) {
                return apiResponse(false, 404, lang('user.user_not'));
            }
            if(authUser()->role_id==3){

                if(!isset($inputs['user_buyer_id']) || $inputs['user_buyer_id']== ""){

                    return apiResponse(false, 404, lang('cart.no_buyer_id'));
                }
            }

            $cartDetails = (new Cart)->findByUserId($UserID);
            //$cartDetails = (new Cart)->findByUserId($inputs['cart_id']);
            //dd($cartDetails);

            if (!$cartDetails) {
                //return apiResponse(false, 404, lang('cart.cart'));
                return apiResponse(false, 404, lang('messages.not_found', lang('cart.cart')));
            }
            if($cartDetails->status==1 ){
                return apiResponse(false, 404, lang('cart.already_ordered', lang('cart.cart')));
            }
            $CartID=$cartDetails->id;


            //fill orderMaster
            $orderNumber=(new Order())->getOrderNumber();
            //if($orderNumber) {
                $cartMasterData = [
                    'user_id' => $UserID,
                    'user_buyer_id' => isset($inputs['user_buyer_id'])?$inputs['user_buyer_id']:null,
                    'cart_id' => $CartID,
                    'company_id' => loggedInCompanyId(),
                    'financial_year_id' => financialYearId(),
                    'order_number' => $orderNumber,
                    'order_date' => currentDate(true),
                    //'gross_amount' => '',
                    //'net_amount'=> '',
                    //'round_off' => '',
                    //'status'    => 1,
                    //'remarks'   => '',
                    'created_by' => authUserId(),
                ];
                //dd($cartMasterData);
                $orderId = (new Order)->store($cartMasterData);

            //}


            if($CartID && $orderId)
            {


                //get cart Products where status 1
                $cartProductIdArray=(new CartProducts)->getCartProducts($CartID);
                //dd($cartProductIdArray);
                //insert to Order Products
                $Subtotal=$Subtotal_cGst= $Subtotal_sGst= $Subtotal_iGst=0;
                if(count($cartProductIdArray)>0){
                    foreach ($cartProductIdArray as $key=>$cartPData)
                    {
                        $cGst = $sGst = $iGst = '';
                        $ProductID = $cartPData->product_id;
                        $orderProductData = [
                            'product_id'=> $ProductID,
                            'order_id'  => $orderId,
                            'created_by'=> authUserId(),
                        ];
                        $orderProductId = (new OrderProducts)->store($orderProductData);

                        //get Tax Percentage
                        $ProductDetailsArray=(new Product)->getProductDetailOnly($ProductID);
                        if($ProductDetailsArray){
                            $tax_Detail=getTaxPercentage($ProductDetailsArray->tax_id);
                            // dd($tax_Detail);
                            $cGst=$tax_Detail['cgst_rate'];
                            $sGst=$tax_Detail['sgst_rate'];
                            $iGst=$tax_Detail['igst_rate'];

                        }
                        //get cart Product Sizes where status 1
                        $cartPSizeArray=(new CartProductSizes)->getCartProductAllSize($CartID,$ProductID);
                        //insert to Order Products

                        $total_amount =$total_cgst_amount = $total_sgst_amount= $total_igst_amount=0;
                        if(count($cartPSizeArray)>0 && isset($orderProductId)){
                            foreach ($cartPSizeArray as $key2=>$cartSizeData)
                            {
                                //dd($cartSizeData->toArray());
                                $total_price = $cGst_price = $sGst_price = $iGst_price = 0;
                                $quantity=$cartSizeData->quantity;
                                $price=$cartSizeData->price;
                                $total_price = $quantity*$price;
                                $cGst_price  = ($cGst / 100) * $total_price;
                                $sGst_price  = ($sGst / 100) * $total_price;
                                $iGst_price  = ($iGst / 100) * $total_price;
                                $ProductID   = $cartSizeData->product_id;
                                $orderSizeData = [
                                    'order_id'   => $orderId,
                                    'product_id' => $ProductID,
                                    'order_product_id' => $orderProductId,
                                    'cgst'       => $cGst,
                                    'cgst_amount'=> round($cGst_price,2),
                                    'sgst'       => $sGst,
                                    'sgst_amount'=> round($sGst_price,2),
                                    'igst'       => $iGst,
                                    'igst_amount'=> round($iGst_price,2),
                                    'size_id'    => $cartSizeData->size_id,
                                    'quantity'   => $quantity,
                                    'price'      => $price,
                                    //'status'     => $ProductID,
                                    'total_price'=> $total_price,
                                    'created_by' => authUserId(),
                                ];
                                $total_amount += $total_price;
                                $total_cgst_amount += $cGst_price;
                                $total_sgst_amount += $sGst_price;
                                $total_igst_amount += $iGst_price;
                                //dd($orderSizeData);
                                $orderProductId = (new OrderProductSizes)->store($orderSizeData);
                            }// for ends
                        }//if ends

                        $Subtotal += $total_amount;
                        $Subtotal_cGst += $total_cgst_amount;
                        $Subtotal_sGst += $total_sgst_amount;
                        $Subtotal_iGst += $total_igst_amount;

                    }// for ends
                }//if ends
                else{
                    dd('No Product Found in Cart');
                }

                //update the Order Master With Total
                $SubtotalN= round($Subtotal* 2, 0)/2;
                $round_off_value= round($Subtotal-$SubtotalN,2);
                //dd($SubtotalN,$Subtotal,$round_off_value,);


                $OrderUpdateArray=[
                    'gross_amount'   => $SubtotalN,
                    'round_off'      => $round_off_value,
                    //'subtotal_cgst'   => $Subtotal_cgst,
                    //'subtotal_sgst'   => $Subtotal_sgst,
                    // 'subtotal_igst'   => $Subtotal_igst,
                    'net_amount'     => round(($Subtotal+$Subtotal_cGst+$Subtotal_sGst),2),
                    //'net_amount'     => number_format($Subtotal+$Subtotal_cGst+$Subtotal_sGst,2),
                ];
                //dd($OrderUpdateArray,$orderId,$cartMasterData);
                (new Order)->store($OrderUpdateArray,$orderId);

                //change Status in CartMaster to 1 of that CartID and Set Mr Id
                $updateCartMaster=[
                    'status'        => 1,
                    'user_buyer_id' => isset($inputs['user_buyer_id'])?$inputs['user_buyer_id']:null,
                ];
                //dd($updateCartMaster,$CartID);
                (new Cart)->store($updateCartMaster,$CartID);
            }//if cart ends

            \DB::commit();
            $cartID = (new Cart)->findByUserId(authUserId())['id'];
            $cartCount=[];
            if($cartID) {
                //$cartCount = CartProducts::where('cart_id', $cartID)->count();
                $cartCount = (new CartProducts)->getCartProductsCount($cartID);
            }
            return apiResponse(true, 200, lang('cart.ordered', lang('cart.cart')),[],$cartCount);
            //return apiResponse(true, 200 , null, [], $result);

            /*else {
                    return apiResponse(false, 404, lang('common.no_size_select'));
                }*/
        }
        catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }

    }


}
