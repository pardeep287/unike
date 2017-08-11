<?php
/**
 * Author Inderjit Singh
 */
namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use League\Flysystem\Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        
        return true;
    }

    /**
     * @return bool
     */
    public function logout()
    {
        return true;
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(Request $request)
    {
        try {
            $inputs = $request->all();

            $validator = (new User)->validateResetPassword($inputs);
            if ($validator->fails()) {
                return apiResponse(false, 406, "", errorMessages($validator->messages()));
            }

            $email = $inputs['email'];
            $user = (new User)->getCustomerByEmail($email);

            if (!$user) {
                return apiResponse(false, 406, lang('email.email_not_found'));
            }

            /*if($user->status == 0) {
                return apiResponse(false, 406, lang('auth.account_not_activated'));
            }*/

            $random = mt_rand(000000, 999999);
            //dd($random);
            $inputs['password'] = \Hash::make($random);
            $inputs['is_reset_password'] = '1';
            (new User)->store($inputs, $user->id);


            Mail::send(lang('email.forgot_password_email'), ['password' => $random], function ($m) use ($user) {
                $m->from(lang('email.from'), lang('email.from_title'));
                $m->to($user->email, $user->username)->subject(lang('email.reset_password'));
            });

            return apiResponse(false, 200, lang('email.password_changed'));
        }
        catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, $exception->getMessage());
        }
    }
}