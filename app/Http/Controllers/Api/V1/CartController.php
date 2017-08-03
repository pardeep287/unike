<?php

namespace App\Http\Controllers\Api\V1;

use App\Cart;
use App\CartProducts;
use App\CartProductSizes;
use App\Product;
use App\ProductCost;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function userCartDetail(Request $request)
    {
        try{
            $inputs = $request->all();
            $result = [];
            $UserID = (new User)->find($inputs['user_id'])['id'];

            if(!$UserID || $UserID!=authUserId()){
                return apiResponse(false, 404, lang('user.user_not'));
            }
            
            $cartID = (new Cart)->findByUserId($inputs['user_id'])['id'];
            //$cartID=null;
            if($cartID) {
                $cartPData=CartProducts::where('cart_id',$cartID)->get(['id','product_id']);

                $ProductDetailsArray=[];
                $ProductDetailsArrayNew=[];
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
                    if($allCartProductSize){
                        $allSizeData=[];
                        foreach ($allCartProductSize as $allSizeData){
                          //  dump( $allSizeData->toArray(),$allCartProductSize->toArray());
                            $finalSizeData[] = [
                                'Cart_size_id'=> $allSizeData->id,
                                'normal_size' => getSizeName($allSizeData->size_id),
                                'quantity'    => $allSizeData->quantity,
                                'price'       => $allSizeData->price,
                            ];
                        }//foreach ends allSize
                    }//if ends
                    // dd($finalSizeData);

                    //check if exists in folder
                    $dirName = ROOT . \Config::get('constants.UPLOADS-PRODUCT').$ProductDetailsArray->product_id.'/';
                    $urlName = url(\Config::get('constants.UPLOADS-PRODUCT').$ProductDetailsArray->product_id.'/'.$ProductDetailsArray->p_image);
                    $ProductDetailsArrayNew[] = [
                        'Cart_Product_id'=> $cpData['id'],
                        'Product_name'   => $ProductDetailsArray->name,
                        'Product_tax'    => getTaxPercentage($ProductDetailsArray->tax_id),
                        'p_image'        => file_exists($dirName.$ProductDetailsArray->p_image)?$ProductDetailsArray->p_image:null,
                        'path'           => $urlName,
                        'Size_Data'      => $finalSizeData,
                    ];
                }
                $result[] = [
                    'cartID' => $cartID,
                    'Cart_Product_Details' => $ProductDetailsArrayNew,

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

            $UserID = (new User)->find($inputs['user_id'])['id'];
            //dd($inputs,$UserDetails);
            if (!$UserID) {
                return apiResponse(false, 404, lang('user.user_not'));
            }
            $ProductID = (new Product)->find($inputs['product_id'])['id'];
            if (!$ProductID) {
                return apiResponse(false, 404, lang('products.product_not'));
            }
            //validate if size_id array == price array and quantity
            $sizeCount=count($inputs['size_id']);
            $priceCount=count($inputs['price']);
            $quantityCount=count($inputs['quantity']);
            if($sizeCount!= $priceCount || $sizeCount!=$quantityCount){
                return apiResponse(false, 404, lang('cart.error_count'));
            }

            //check user id exists in cart table with status 0
            $cartDetails= (new Cart)->findByUserId($UserID);

            if(!$cartDetails){
                $cartMasterData = [
                    'user_id'   => $inputs['user_id'],
                    'cart_date' => currentDate(true),
                    'status'    => 0,
                    'created_by'=> $UserID,
                ];
                $cartId = (new Cart)->store($cartMasterData);
            }
            $cartId = $cartDetails->id;

            //check if cartProduct has already Same Product
            $cartProductId='';
            if($cartId)
            {
                $cartProductIdArray = CartProducts::where('product_id', $ProductID)->where('cart_id',$cartId)->get(['id','product_id']);
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
                                'created_by' => $UserID,
                            ];
                            $cartProductId = (new CartProducts)->store($cartProductData);
                        }
                    }// for ends
                }//if ends
            }//if cart eds

            //check if any product size has been selected
            if (isset($inputs['size_id']) && count($inputs['size_id']) > 0) 
            {
                $cartPSizeData=[];
                foreach($inputs['size_id'] as $key=>$sizeID) {
                    $cartPSizeArray= CartProductSizes::where('size_id',$sizeID)->where('cart_id',$cartId)->where('product_id', $ProductID)->first(['id','size_id']);
                    //dd($cartId,$cartProductId, $inputs,$sizeID,$cartPSizeArray,$inputs['size_id'][$key]);
                    if(!$cartPSizeArray ){
                        //if( $cartPSizeArray['size_id']!=$sizeID) {
                            $cartPSizeData = [
                                'cart_id' => $cartId,
                                'product_id' => $ProductID,
                                'size_id' => $inputs['size_id'][$key],
                                'quantity' => $inputs['quantity'][$key],
                                'price' => $inputs['price'][$key],
                                'created_by' => $UserID,
                            ];
                            (new CartProductSizes)->store($cartPSizeData);
                        //}
                    }
                }// foreach ends
                //dd($cartId,$cartProductId, $inputs,$sizeID,$cartPSizeArray,$cartPSizeData);
                \DB::commit();
                return apiResponse(true, 200, lang('messages.added', lang('cart.items')));
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
                       

            $UserID = (new User)->find($inputs['user_id'])['id'];
            //dd($inputs,$UserDetails);
            if(!$UserID || $UserID!=authUserId()){
                return apiResponse(false, 404, lang('user.user_not'));
            }

            $ProductID = (new Product)->find($inputs['product_id'])['id'];
            if (!$ProductID) {
                return apiResponse(false, 404, lang('messages.not_found', lang('products.product')));
            }
            $CartID = (new Cart)->find($inputs['cart_id'])['id'];
            if (!$CartID) {
                //return apiResponse(false, 404, lang('cart.cart'));
                return apiResponse(false, 404, lang('messages.not_found', lang('cart.cart')));
            }


            //check if Product Delete request
            if(isset($ProductID))
            {
                $allCartPSizes=(new CartProductSizes)->getCartProductAllSize($CartID,$ProductID);
                dd($inputs,$allCartPSizes->toArray());
                if(isset($allCartPSizes) && count($allCartPSizes)>0) {
                    foreach ($allCartPSizes as $cpSize) {
                        

                    }
                }
            }

            //validate if size_id array == price array and quantity
            $sizeCount=count($inputs['size_id']);
            $priceCount=count($inputs['price']);
            $quantityCount=count($inputs['quantity']);
            if($sizeCount!= $priceCount || $sizeCount!=$quantityCount){
                return apiResponse(false, 404, lang('cart.error_count'));
            }

            //check user id exists in cart table with status 0
            $cartDetails= (new Cart)->findByUserId($UserID);

            if(!$cartDetails){
                $cartMasterData = [
                    'user_id'   => $inputs['user_id'],
                    'cart_date' => currentDate(true),
                    'status'    => 0,
                    'created_by'=> $UserID,
                ];
                $cartId = (new Cart)->store($cartMasterData);
            }
            $cartId = $cartDetails->id;

            //check if cartProduct has already Same Product
            $cartProductId='';
            if($cartId)
            {
                $cartProductIdArray = CartProducts::where('product_id', $ProductID)->where('cart_id',$cartId)->get(['id','product_id']);
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
                                'created_by' => $UserID,
                            ];
                            $cartProductId = (new CartProducts)->store($cartProductData);
                        }
                    }// for ends
                }//if ends
            }//if cart eds

            //check if any product size has been selected
            if (isset($inputs['size_id']) && count($inputs['size_id']) > 0)
            {
                $cartPSizeData=[];
                foreach($inputs['size_id'] as $key=>$sizeID) {
                    $cartPSizeArray= CartProductSizes::where('size_id',$sizeID)->where('cart_id',$cartId)->where('product_id', $ProductID)->first(['id','size_id']);
                    //dd($cartId,$cartProductId, $inputs,$sizeID,$cartPSizeArray,$inputs['size_id'][$key]);
                    if(!$cartPSizeArray ){
                        //if( $cartPSizeArray['size_id']!=$sizeID) {
                            $cartPSizeData = [
                                'cart_id' => $cartId,
                                'product_id' => $ProductID,
                                'size_id' => $inputs['size_id'][$key],
                                'quantity' => $inputs['quantity'][$key],
                                'price' => $inputs['price'][$key],
                                'created_by' => $UserID,
                            ];
                            (new CartProductSizes)->store($cartPSizeData);
                        //}
                    }
                }// foreach ends
                //dd($cartId,$cartProductId, $inputs,$sizeID,$cartPSizeArray,$cartPSizeData);
                \DB::commit();
                return apiResponse(true, 200, lang('messages.added', lang('cart.items')));
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
}
