<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard');
    }

    /**
     * used to display change password form
     * @return \Illuminate\View\View
     */
    public function changePasswordForm()
    {
        return view('changepassword');
    }

    /**
     * update password
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function changePassword()
    {
        $inputs = \Input::all();
        $password = \Auth::user()->password;
        if(!(\Hash::check($inputs['old_password'], $password))){
            return redirect()->route('changepassword')
                ->with("error", "Incorrect Old Password.");
        }

        $validator = (new user)->validatePassword($inputs);
        if ($validator->fails()) {
            return redirect()->route('changepassword')
                ->withErrors($validator);
        }

        if ((new user)->updatePassword(\Hash::make($inputs['new_password']))) {
            return redirect()->route('changepassword')
                ->with("success", "Password Successfully Updated.");
        } else {
            return redirect()->route('changepassword')
                ->withErrors("Internal Server Error");
        }
    }
}
