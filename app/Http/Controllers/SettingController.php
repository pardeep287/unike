<?php

namespace App\Http\Controllers;



use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Symfony\Component\Console\Input\Input;


class SettingController extends Controller
{



    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function myAccount()
    {
        $inputs = \Input::all();
        if (count($inputs) > 0) {
            $validator = (new User())->validatePassword($inputs);
            if ($validator->fails()) {
                return redirect()->route('setting.manage-account')
                    ->withErrors($validator);
            }

            $password = \Auth::user()->password;
            if (!(\Hash::check($inputs['password'], $password))) {
                return redirect()->route('setting.manage-account')
                    ->with("error", lang('messages.invalid_password'));
            }
            //dd($inputs['new_password']);
            //$i=Hash::
            $inputs['new_password'] = \Hash::make($inputs['new_password']);
            $inputs['is_reset_password'] = '0';
            //print_r(authUser().'updatepassword');
            (new User)->updatePassword($inputs);
            //echo $dd;
            //return redirect()->intended('dashboard');
            return redirect()->route('setting.manage-account')
                ->with('success', lang('messages.password_updated'));
        }
        return view('setting.account');
    }






}
