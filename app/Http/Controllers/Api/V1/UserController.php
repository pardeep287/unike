<?php

/**
 * @Author Pardeep Verma
 * @Created_at 28/7/2017
 */
namespace App\Http\Controllers\Api\V1;
use App\Customer;
use App\Http\Controllers\Controller;
use App\NotificationLog;
use App\User;
use Illuminate\Http\Request;
use League\Flysystem\Exception;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Console\Input\Input;

class UserController extends Controller
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
   public function listUser(){
       try {

            $result = [];
           $inputs= \Input::all();


            $users = (new User)->getUsersFilters($inputs, 0, 500);
           //dd($users);
            if(count($users) == 0) {
                return apiResponse(false, 404, lang('messages.not_found', lang('user.user')));
            }

            foreach ($users as $user) {
                if($user->role_id !=1  && $user->role_id !=3) {

                    $result[] = [
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'username' => $user->username,
                        'role_id' => $user->role_id,
                        /*'email' => $user->email,
                        'dob' => ($user->dob == '')?null:dateFormat('d-m-Y', $user->dob),
                        'address' => $user->address,
                        'mobile' => $user->mobile,
                        'phone' => $user->phone,
                        'is_approved' => ($user->status == 0)?'Not approved':'Approved'*/
                    ];
                }
            }

           return apiResponse(true, 200 , null, [], $result);
       }
       catch (Exception $exception) {
           \DB::rollBack();
           return apiResponse(false, 500, lang('messages.server_error'));
       }
   }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store( Request $request )
    {
        try{

            \DB::beginTransaction();
            $inputs = $request->all();

            $validator = ( new Customer )->validateCustomer($inputs,null,null,true);
            if( $validator->fails() ) {
                return apiResponse(false, 406, "", errorMessages($validator->messages()));
            }
            /*Hash Password*/
            $password = \Hash::make($inputs['password']);

            /* setting up the default role to 2 [ Customer ] */
            $role_id = 2;

            $userArray=[
                'name'       => $inputs['customer_name'],
                'username'   => $inputs['username'],
                'password'   => $password,
                'email'      => $inputs['email'],
                'company_id' => 1,
                'role_id'    => $role_id,
                'status'     => 0,
                'created_by' => 0,

            ];
            //$user_id=1;
            $user_id=(new User)->store($userArray);
            $code= (new Customer)->getCustomerCode();
            $customerArray=[
                'customer_name' => $inputs['customer_name'],
                'customer_code' => $code,
                'mobile_no'     => $inputs['mobile_no'],
                'email'         => $inputs['email'],
                'gst_number'    => $inputs['gst_number'],
                'address'       => $inputs['address'],
                'country'       => $inputs['country'],
                'state_id'      => $inputs['state_id'],
                'city'          => $inputs['city'],
                'pin_code'      => $inputs['pin_code'],
                'user_id'       => $user_id,
                'company_id'    => 1,
                'created_by'    => 0,
            ];
            //dd($customerArray);
            (new Customer)->store($customerArray);

            \DB::commit();
            return apiResponse(true, 200, lang('user.registered'));
        }
        catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, $id )
    {
        try {
            \DB::beginTransaction();
            /* FIND WHETHER THE USER EXISTS OR NOT */
            $user = User::find($id);
            if(!$user) {
                return apiResponse(false, 404, lang('messages.not_found', lang('user.user')));
            }

            if($user->id == authUserId() ||  isAdmin()) {

                $inputs = $request->all();

                if(array_key_exists('password', $inputs)) {
                    if($inputs['password'] == '') {
                        unset($inputs['password']);
                    }
                    else {
                        $password = \Hash::make($inputs['password']);
                        unset($inputs['password']);
                        $inputs = $inputs + ['password' => $password];
                    }
                }

                if(array_key_exists('phone', $inputs)) {
                    $phone = ( empty( $inputs['phone'] ) )?null:$inputs['phone'];
                    $inputs = $inputs + ['phone' => $phone];
                }

                if( array_key_exists('dob', $inputs)) {
                    $dob = (empty( $inputs['dob'] ) )?null:dateFormat('Y-m-d', $inputs['dob']);
                    unset($inputs['dob']);
                    $inputs = $inputs + ['dob' => $dob];
                }

                $validator = ( new User )->validate( $inputs, $id );
                if( $validator->fails() ) {
                    return apiResponse(false, 406, "", errorMessages($validator->messages()));
                }

                (new User)->store($inputs, $id);
                \DB::commit();
                return apiResponse(true, 200, lang('messages.updated', lang('user.user')));
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
    public function drop($id) {
        try {

            \DB::beginTransaction();
            /* FIND WHETHER THE USER EXISTS OR NOT */
            $user = User::find($id);
            if(!$user) {
                return apiResponse(false, 404, lang('messages.not_found', lang('user.user')));
            }
            if(isAdmin()) {
                if($id == 1) {
                    return apiResponse(false, 406, lang('user.admin_restrict'));
                }
                (new User)->drop($id);
                \DB::commit();
                return apiResponse(true, 200, lang('messages.deleted', lang('user.user')));
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
    public function activateAccount($id) {
        try {

            \DB::beginTransaction();

            if($id == authUserId() || isAdmin()) {
                /* FIND WHETHER THE USER EXISTS OR NOT */
                $user = User::find($id);
                if(!$user) {
                    return apiResponse(false, 404, lang('messages.not_found', lang('user.user')));
                }
                if($user->status != 1) {
                    (new User)->activateAccount($id);

                    /*Mail::send(lang('email.email_template'), ['user' => $user], function ($m) use ($user) {
                        $m->from(lang('email.from'), lang('email.from_title'));
                        $m->to($user->email, $user->username)->subject(lang('email.subject'));
                    });*/

                    \DB::commit();
                    return apiResponse(true, 200, lang('user.account_activated'));
                }
                return apiResponse(false, 404, lang('auth.already_activated'));
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
    public function getUserDetail( $id ) {
        try {
            if($id == authUserId() || isAdmin()) {

                $user = (new User)->getUsers([], 0,50);
                if( count($user) > 0) {
                    $result = [
                        'user_id'   => $user->id,
                        'name'      => $user->name,
                        'username'  => $user->username,
                        'email'     => $user->email,
                        'dob'       => ($user->dob !='')?dateFormat('d-m-Y', $user->dob):null,
                        'address'   => $user->address,
                        'mobile'    => $user->mobile,
                        'phone'     => $user->phone,
                        'status'     => $user->status,
                        'approve_text' => ($user->status == 0)?'Approve':'Approved'
                    ];
                    return apiResponse(true, 200 , null, [], $result);
                }
                return apiResponse(false, 404, lang('messages.not_found', lang('user.user')));
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function listMR(){
        try {

            $result = [];
           /* if(!isAdmin()){
                return apiResponse(false, 404,  lang('user.user_not_allowed'));
            }*/
            $inputs= \Input::all();

            $mrData = (new User)->getMRs($inputs,0,500);
            if(count($mrData) == 0) {
                return apiResponse(false, 404, lang('messages.not_found', lang('user.mr')));
            }

            foreach ($mrData as $user) {


                    $result[] = [
                        'mr_id' => $user->id,
                        'mr_name' => $user->name,
                        'username' => $user->username,
                        'role_id' => $user->role_id,
                        /*'email' => $user->email,
                        'dob' => ($user->dob == '')?null:dateFormat('d-m-Y', $user->dob),
                        'address' => $user->address,
                        'mobile' => $user->mobile,
                        'phone' => $user->phone,
                        'is_approved' => ($user->status == 0)?'Not approved':'Approved'*/
                    ];
                }


            return apiResponse(true, 200 , null, [], $result);
        }
        catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }
}