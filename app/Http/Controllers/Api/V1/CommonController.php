<?php

namespace App\Http\Controllers\Api\V1;

use App\Company;
use App\CompanyPlan;
use App\Doctor;
use App\Http\Controllers\Controller;
use App\Product;
use App\Qualification;
use App\Specialization;
use App\User;
use Illuminate\Http\Request;
use App\City;
use Illuminate\Support\Facades\Input;
use Psy\Exception\Exception;

class CommonController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $result= [
                'cities' => $this->getCities(true),
            ];
            return apiResponse(true, 200 , null, [], $result);
        }
        catch (Exception $exception) {
            return apiResponse(false, 500, $exception->getMessage());
        }
    }

    /**
     * @param bool $isCommon
     * @return array
     */
    public function getCities($isCommon = false)
    {
        $result = [];
        $cities = (new City)->getCities();
        if(count($cities) > 0) {
            foreach($cities as $city) {
                $result[] = [
                   'id' => $city->id,
                    'name' => $city->city_name
                ];
            }

        }

        if ($isCommon) {
            return $result;
        } else {
            return apiResponse(true, 200, null, [], $result);
        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDeviceCode(Request $request)
    {
        try {
            $inputs = $request->all();
            if (isset($inputs['user_id']) && $inputs['user_id'] != "" && isset($inputs['token']) && $inputs['token'] != "") {
                (new User)->updateDeviceToken($inputs['user_id'], $inputs['token']);
                return apiResponse(true, 200, lang('user.token_updated'));
            }
            return apiResponse(false, 404, lang('user.invalid_request'));
        } catch (Exception $exception) {
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendPush(Request $request)
    {
        try {
            $inputs = $request->all();
            if (isset($inputs['device_id']) && $inputs['device_id'] != "" && isset($inputs['message']) && $inputs['message'] != "") {
                $result = sendPush([$inputs['device_id']], ["message" => $inputs['message'], 'type' => 1]);
                print_r($result);
                return apiResponse(true, 200, lang('user.push_sent'));
            }
            return apiResponse(false, 404, lang('user.invalid_request'));
        } catch (Exception $exception) {
            return apiResponse(false, 500, $exception->getMessage() . lang('messages.server_error'));
        }
    }
}
