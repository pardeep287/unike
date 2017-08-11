<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class ResetPasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('reset-password.index');
    }

    /**
     * reset the form for reset password
     *
     * @return \Illuminate\Http\Response
     */
    public function resetPassword()
    {
        $inputs = \Input::all();
        //$validator = (new User)->validateResetPassword($inputs);
        $validator = (new ResetPassword)->validateResetPassword($inputs);
        if ($validator->fails()) {
            return redirect()->route('reset-password.index')
                ->withInput($inputs)
                ->withErrors($validator);
        }

        $detail = $inputs['email'];
        //$result = (new User)->getCustomerByEmail($email);
        $result = (new ResetPassword)->getUserDetail($detail);

        if (count($result) > 0) {
            $random = mt_rand(000000, 999999);
            $inputs['password'] = \Hash::make($random);
            $inputs['is_reset_password'] = '1';
            (new ResetPassword)->updatePassword($inputs, $result->id);
            try {
                /* Sending mail to the user with password */
                Mail::send('emails.reset-password', ['password' => $random ], function ($m) use ($result) {
                    $m->from(\Config::get('constants.EMAIL'), lang('emails.sir_ltd'));
                    $m->to($result->email, $result->username)->subject(lang('reset_password.reset_password_subject'));

                });
            } catch (\Exception $e) {
                
            }
            return redirect()->to('/')
                ->with("success", lang('messages.reset_password_successfully'));
        }
        return redirect()->route('reset-password.index')
            ->withErrors(lang('messages.invalid_credentials'));
    }
}
