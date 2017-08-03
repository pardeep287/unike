<?php

/**
 * @Author Inderjit Singh
 * @Created_at 14/4/2017
 */
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Customer;

use Illuminate\Http\Request;
use League\Flysystem\Exception;

class CustomerController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomers(Request $request)
    {
        try{
            $inputs = $request->all();
            $result = [];
            $customers = (new Customer)->getCustomer($inputs);
            if(count($customers) > 0) {
                foreach( $customers as $customer ) {
                    $result[] = [
                        'id'             => $customer->id,
                        'customer_name'  => $customer->customer_name,
                        'contact_person' => $customer->contact_person,
                        'mobile_no'      => $customer->mobile_no,
                        'landline_no'    => $customer->landline_no,
                        'email'          => $customer->email,
                        'address'        => $customer->address,
                        'country'        => $customer->country,
                        'state'          => $customer->state,
                        'city'           => $customer->city
                    ];
                }
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
    public function getCustomerDetail($id = null)
    {
        try{
            $customer = (new Customer)->getCustomer([], $id);
            if(count($customer) > 0) {
                if($customer->user_id == authUserId() || isAdmin()) {
                    $result  = [
                        'id'                  => $customer->id,
                        'customer_name'       => $customer->customer_name,
                        'contact_person'      => $customer->contact_person,
                        'mobile_no'           => $customer->mobile_no,
                        'landline_no'         => $customer->landline_no,
                        'email'               => $customer->email,
                        'address'             => $customer->address,
                        'alternate_address'   => $customer->alternate_address,
                        'country'             => $customer->country,
                        'state'               => $customer->state,
                        'city'                => $customer->city,
                        'pin_code'            => $customer->pin_code
                    ];
                    return apiResponse(true, 200 , null, [], $result);
                }
                else {
                    return apiResponse(false, 404, lang('auth.customer_not_accessible'));
                }
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