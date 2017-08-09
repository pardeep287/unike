<?php

/**
 * @Author Inderjit Singh
 * @Created_at 14/4/2017
 */
namespace App\Http\Controllers\Api\V1;
use App\Cart;
use App\CartProducts;
use App\Http\Controllers\Controller;
use App\Customer;

use App\Product;
use App\ProductCost;
use App\ProductDimensions;
use App\ProductSizeDimensionsValue;
use App\ProductSizes;
use App\User;
use Illuminate\Http\Request;
use League\Flysystem\Exception;

class ApiProductController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function customerDashboard($id = null)
    {

        try{
            //$inputs = $request->all();
            $UserID = (new User)->find($id)['id'];

            if(!$UserID || $UserID!=authUserId()){
                return apiResponse(false, 404, lang('user.user_not'));
            }
            $result = $slideProducts= [];

            $cartID = (new Cart)->findByUserId($id)['id'];
            if($cartID) {
                $cartCount = CartProducts::where('cart_id', $cartID)->count();
            }


            $products = (new Product)->getProduct([],0,20,true);

            if(count($products) > 0) {
                foreach( $products as $product ) {
                    $dirName = ROOT . \Config::get('constants.UPLOADS-PRODUCT').$product->id.'/';
                    $urlName = url(\Config::get('constants.UPLOADS-PRODUCT').$product->id.'/'.$product->p_image);
                    $slideProducts[] = [
                        'id'             => $product->id,
                        'name'           => $product->name,
                        'p_image'        => file_exists($dirName.$product->p_image)?$product->p_image:null,
                        'path'           => $urlName,
                    ];
                }
                $result=[
                    'cart_Count'      => isset($cartCount)?$cartCount:null,
                    'slider_products' => isset($slideProducts)?$slideProducts:null,
                    'top_Selling'     => null,
                ];
                return apiResponse(true, 200 , null, [], $result);
            }
            else {
                return apiResponse(false, 404, lang('common.no_result'));
            }
        }
        catch (Exception $exception) {
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductDetail($id = null)
    {
        try{

            //$product = (new Product)->find($id);
            //dd($id);
            $productsWithImage= (new Product)->findById($id);
                if(!$productsWithImage){
                    return apiResponse(false, 404, lang('common.no_result'));
                }
            //dd($productsWithImage->toArray());
            $productSizePrice = (new ProductSizes)->getPriceListProductSize($id);
            $productDimensions = (new ProductDimensions)->getProductDimension($id);
            //dd($productDimensions->toArray());
            $sizeDimensionValue = (new ProductSizeDimensionsValue)-> getProductDimensionValue($id);

            $thumbsImages=(explode(',',$productsWithImage->images));
            $dirName = ROOT . \Config::get('constants.UPLOADS-PRODUCT') . $productsWithImage->id . '/';
            $urlName = url(\Config::get('constants.UPLOADS-PRODUCT').$productsWithImage->id.'/'.$productsWithImage->p_image);
            $urlNameImages = url(\Config::get('constants.UPLOADS-PRODUCT').$productsWithImage->id.'/' );
            foreach($thumbsImages as $key=>$thumbImages){
                $imagesThumb[] = [
                    'images' => file_exists($dirName . $thumbImages) ? $urlNameImages.'/'.$thumbImages : null,
                ];

                   }


            //dd($productSizePrice->toArray());
            if(count($productsWithImage) > 0) {
                //$images=$productsWithImage->images;
//                dd($productsWithImage[0]['images']);
                /*$dirName = ROOT . \Config::get('constants.UPLOADS-PRODUCT') . $productsWithImage->id . '/';
                $urlName = url(\Config::get('constants.UPLOADS-PRODUCT').$productsWithImage->id.'/'.$productsWithImage->p_image);
                $urlNameImages = url(\Config::get('constants.UPLOADS-PRODUCT').$productsWithImage->id.'/' );*/
                $productDetail = [
                    'id' => $productsWithImage->id,
                    'name' => $productsWithImage->name,
                    'description' => $productsWithImage->description,
                    'p_image' => file_exists($dirName . $productsWithImage->p_image) ? $productsWithImage->p_image : null,
                    //'images' => isset($productsWithImage->images) ? preg_filter('/^/', $urlNameImages.'/', explode(',',$productsWithImage->images)) : null,
                    'path' => $urlName,
                    'thumb_images' => $imagesThumb,


                ];
                $productSizePriceArray=[];
                if(count($productSizePrice)>0){
                    foreach ($productSizePrice as $key=>$sizePrice) {
                        $dimValues=[];
                        foreach ($sizeDimensionValue as $dimValue) {
                            if($dimValue['size_id'] == $sizePrice->product_sizes_id )
                            {
                                foreach ($productDimensions as $dimension){
                                    if($dimension['product_dimension_id'] ==$dimValue['dimension_id'] ){
                                        $dimValues[] = [
                                            'product_size_dimensions_id' => $dimValue->product_size_dimensions_id,
                                           // 'dimension_id' => $dimValue->dimension_id,
                                            'dimension_name' => $dimension->dimension_name,
                                            'dimension_value' => $dimValue->dimension_value,
                                        ];
                                    }
                                }

                            }

                        }
                        $productSizePriceArray[] = [
                            'product_sizes_id' => $sizePrice->product_sizes_id,
                            'normal_size' => $sizePrice->normal_size,
                            'price' => $sizePrice->price,
                            'dimension_value' => $dimValues,
                        ];

                    } //foreach Ends $productSizePrice
                }

                $result = [
                    'product_detail' => $productDetail,
                    'product_dimension' => $productDimensions,
                   // 'product_size_dimension_value' => $sizeDimensionValue,
                    'product_size_price' => $productSizePriceArray,


                ];

                return apiResponse(true, 200, null, [], $result);


            }
            else {
                return apiResponse(false, 404, lang('common.no_result'));
            }
        }
        catch (Exception $exception) {
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }



    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store( Request $request )
    {
        try {
            \DB::beginTransaction();
            $inputs = $request->all();

            $validator = ( new Customer )->validate( $inputs );
            if( $validator->fails() ) {
                return apiResponse(false, 406, "", errorMessages($validator->messages()));
            }

            $userId = authUserId();
            $inputs = $inputs + ['user_id' => $userId];
            $id = ( new Customer )->store( $inputs );
            \DB::commit();
            return apiResponse(true, 200, lang('messages.created', lang('customer.customer')),
                [], ['id' => $id, 'name' => $inputs['customer_name']]);
        }
        catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit( Request $request, $id )
    {
        try {
            \DB::beginTransaction();
            $inputs = $request->all();
                /* Check if the customer exists or not */
                $customer = Customer::find( $id );
                if(!$customer) {
                    return apiResponse(false, 404, lang('messages.not_found', lang('customer.customer')));
                }

                if($customer->user_id == authUserId() || isAdmin()) {

                    $validator = ( new Customer )->validate( $inputs, $id);
                    if( $validator->fails() ) {
                        return apiResponse(false, 406, "", errorMessages($validator->messages()));
                    }
                    ( new Customer )->store( $inputs, $id);
                    \DB::commit();
                    return apiResponse(true, 200, lang('messages.updated', lang('customer.customer')));
                }
                else {
                    return apiResponse(false, 404, lang('auth.customer_not_accessible'));
                }
        }
        catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function drop($id)
    {
        try {
            \DB::beginTransaction();
            /* Check if the customer exists or not */
            $customer = Customer::find( $id );
            if(!$customer) {
                return apiResponse(false, 404, lang('messages.not_found', lang('customer.customer')));
            }

            if($customer->user_id == authUserId() || isAdmin()) {
                (new Customer)->deleteCustomer($id);
                \DB::commit();
                return apiResponse(true, 200, lang('messages.deleted', lang('customer.customer')));
            }
            else {
                return apiResponse(false, 404, lang('auth.customer_not_accessible'));
            }
        }
        catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }
}