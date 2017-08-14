<?php

namespace App\Http\Controllers;

use App\Order;
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
        $totalOrderMonthWise=(new Order)->getOrders([], 0, 200);
        $grossTotal=0;
        foreach ($totalOrderMonthWise as $amount){
            $grossTotal += $amount->gross_amount;
        }
        $currentMonth=date("m",strtotime(currentDate()));
        $monthWiseLatestOrder=(new Order)->monthWiseLatestOrder(['month' => $currentMonth],10);
        //dd($monthWiseLatestOrder, $currentMonth);
        $final=(new Order)->monthWiseMrOrder(['month' => $currentMonth]);
        //dd($final);
        $monthWiseTotalOrderMrAgent=[];
        foreach ($final as $order){
            $monthWiseTotalOrderMrAgent[]=[
                'id'       => $order->user_id,
                'user_name'     => getUsername($order->user_id),
                'total_amount'  => $order->total_amount,
                'count'         => $order->count,
            ];
        }
       // dd($monthWiseTotalOrderMrAgent);
        return view('dashboard',compact('totalOrderMonthWise','grossTotal','monthWiseLatestOrder','monthWiseTotalOrderMrAgent'));
    }

    /**
     * used to display change password form
     * @return \Illuminate\View\View
     */
    /*public function changePasswordForm()
    {
        return view('changepassword');
    }*/

    /**
     * update password
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    /*public function changePassword()
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
    }*/


}
