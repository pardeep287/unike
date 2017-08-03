<?php

/**
 * @Author Inderjit Singh
 * @Created_at 15/4/2017
 */
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\CustomerVisit;
use App\Customer;
use App\NotificationLog;
use App\User;
use Illuminate\Http\Request;
use League\Flysystem\Exception;

class CustomerVisitController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomerVisitList(Request $request)
    {
        try{
            $result = [];
            $inputs = $request->all();
            $customerVisits = (new CustomerVisit)->getCustomerVisit($inputs);

            if( count($customerVisits) > 0 ) {
                foreach( $customerVisits as $visit ) {
                    $visitDate = convertToLocal($visit->visit_date, 'd-m-Y h:i A');
                    $nextFollowupDate = ($visit->next_followup_date != "") ? convertToLocal($visit->next_followup_date, 'd-m-Y h:i A') : null;
                    $result[] = [
                        'id'                    => $visit->id,
                        'customer_name'         => $visit->customer_name,
                        'name'                  => $visit->name,
                        'visit_date'            => $visitDate,
                        'next_followup_date'    => $nextFollowupDate,
                        'overall_feedback'      => getOverAllFeedback($visit->overall_feedback)
                    ];
                }
                return apiResponse(true, 200 , null, [], $result);
            }
            else {
                return apiResponse(false, 404, lang('messages.not_found', lang('customer.customer_visit')));
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
    public function getCustomerVisitDetail( $id )
    {
        try{
            $customerDetail = (new CustomerVisit)->getCustomerVisit([], $id);
            if( count($customerDetail) > 0 ) {
                if($customerDetail->user_id == authUserId() || isAdmin()) {

                    $visitDate = convertToLocal($customerDetail->visit_date, 'd-m-Y h:i A');
                     $nextFollowupDate = ($customerDetail->next_followup_date != "") ? convertToLocal($customerDetail->next_followup_date, 'd-m-Y h:i A') : null;
                    $result= [
                        'id'                    => $customerDetail->id,
                        'customer_id'           => $customerDetail->customer_id,
                        'customer_name'         => $customerDetail->customer_name,
                        'user_id'               => $customerDetail->user_id,
                        'name'                  => $customerDetail->name,
                        'username'              => $customerDetail->username,
                        'visit_date'            => $visitDate,
                        'next_followup_date'    => $nextFollowupDate,
                        'discussion'            => $customerDetail->discussion,
                        'overall_feedback'      => ['id' => $customerDetail->overall_feedback, 'name' => getOverAllFeedback($customerDetail->overall_feedback)]
                    ];
                    return apiResponse(true, 200 , null, [], $result);
                }
                else {
                    return apiResponse(false, 404, lang('auth.customer_not_accessible'));
                }
            }
            else {
                return apiResponse(false, 404, lang('messages.not_found', lang('customer.customer_visit')));
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
    public function store(Request $request) {
            try {
                \DB::beginTransaction();
                $inputs = $request->all();                

                $validator = ( new CustomerVisit)->validate( $inputs );
                if( $validator->fails() ) {
                    return apiResponse(false, 406, "", errorMessages($validator->messages()));
                }

                /* Check whether the customer exists or not */
                $customerId = $inputs['customer_id'];
                $customer = Customer::find( $customerId );
                if(!$customer) {
                    return apiResponse(false, 404, lang('messages.not_found', lang('customer.customer')));
                }

                $visitDate = dateFormat('Y-m-d H:i:s', $inputs['visit_date']);
                $visitDateV = dateFormat('Y-m-d', $inputs['visit_date']);
                $nextFollowUpDate = null;
                if ($inputs['next_followup_date'] != "") {
                    $nextFollowUpDate = dateFormat('Y-m-d H:i:s', $inputs['next_followup_date']);
                    unset($inputs['next_followup_date']);
                }
                $serverTime = convertToLocal(date('Y-m-d H:i'), 'Y-m-d H:i');
                $serverDate = date('Y-m-d');
                $restricedTime = date('Y-m-d') . ' 9:00';

                if(!isAdmin()) {
                    if ($visitDateV < $serverDate) {
                        if (strtotime($serverTime) > strtotime($restricedTime)) {
                            return apiResponse(false, 406, lang('customer.time_expired'));
                        }
                    } 
                }

                unset($inputs['visit_date']);
                $userId = authUserId();
                if (isAdmin() && isset($inputs['user_id']) && $inputs['user_id'] != "")  {
                    $userId = $inputs['user_id'];
                }

                $inputs = $inputs + [
                        'visit_date' => convertToUtc($visitDate),
                        'next_followup_date' => ($nextFollowUpDate != "") ? convertToUtc($nextFollowUpDate) : null,
                        'user_id' => $userId
                   ]; 
                ( new CustomerVisit )->store($inputs);
                $userData = User::find($userId);
                $customerData = ( new Customer)->getCustomer([], $customerId);
                if($userData && $customerData) {
                    $message = "$userData->name has visited to $customerData->customer_name on (". convertToLocal($inputs['visit_date'], 'd M h:i') . ")";
                    $userToken = (new User)->getDeviceToken();
                    $response = sendPush([$userToken->android_device_token], array("message" => $message, 'type' => 1));
                    $response = json_decode($response);
                    $status = $response->success;
                    $data = (new User)->getCompanyAdmin();
                    $notificationArr = [];
                    $isSent = 0;
                    if($status == 1) {
                        $isSent = 1;
                    }
                    if(count($data) > 0) {
                        foreach($data as $row) {
                            $notificationArr[] = [
                                'message' => $message,
                                'user_id' => $row->id,
                                'company_id' => loggedInCompanyId(),
                                'is_sent'   => $isSent,
                            ];
                        }
                    }
                    (new NotificationLog)->store($notificationArr);
                }
                \DB::commit();
                return apiResponse(true, 200, lang('messages.created', lang('customer.customer_visit')));
            }
        catch( Exception $exception ) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, $id) {
        try {
            \DB::beginTransaction();

            $customerVisit = CustomerVisit::find($id);

            if(!$customerVisit){
                return apiResponse(false, 404, "", lang('messages.not_found', lang('customer.customer_visit')));
            }

            if($customerVisit->user_id == authUserId() || isAdmin()) {
                $inputs = $request->all();
                $validator = ( new CustomerVisit)->validate( $inputs );
                if( $validator->fails() ) {
                    return apiResponse(false, 406, errorMessages($validator->messages()));
                }

                /* Check whether the customer exists or not */
                $customerId = $inputs['customer_id'];
                $customer = Customer::find( $customerId );


                if(!$customer) {
                    return apiResponse(false, 404, lang('messages.not_found', lang('customer.customer')));
                }

                //$visitDate = dateFormat('Y-m-d H:i:s', $inputs['visit_date']);
                $nextFollowUpDate = null;
                if ($inputs['next_followup_date'] != "") {
                    $nextFollowUpDate = dateFormat('Y-m-d H:i:s', $inputs['next_followup_date']);
                    unset($inputs['next_followup_date']);
                }
                
                $visitDate = dateFormat('Y-m-d H:i:s', $inputs['visit_date']);
                $visitDateV = dateFormat('Y-m-d', $inputs['visit_date']);
                $serverTime = convertToLocal(date('Y-m-d H:i'), 'Y-m-d H:i');
                $serverDate = date('Y-m-d');
                $restricedTime = date('Y-m-d') . ' 9:00';

                if(!isAdmin()) {
                    if ($visitDateV < $serverDate) {
                        if (strtotime($serverTime) > strtotime($restricedTime)) {
                            return apiResponse(false, 406, lang('customer.time_expired'));
                        }
                    } 
                }

                unset($inputs['visit_date']);
                $userId = \Auth::user()->id; 
                
                if (isAdmin() && isset($inputs['user_id']) && $inputs['user_id'] !="")  {
                    $userId = $inputs['user_id'];
                }

                $inputs = $inputs + [
                    'visit_date' => convertToUtc($visitDate),
                    'next_followup_date' => ($nextFollowUpDate != "") ? convertToUtc($nextFollowUpDate) : null,
                    'user_id' => $userId 
                  ];

                (new CustomerVisit)->store($inputs, $id);
                \DB::commit();
                return apiResponse(true, 200, lang('messages.updated', lang('customer.customer_visit')));
            }
            else {
                return apiResponse(false, 404, lang('auth.customer_not_accessible'));
            }
        }
        catch( Exception $exception ) {
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
            $customerVisit = CustomerVisit::find($id);
            if(!$customerVisit){
                return apiResponse(false, 404, lang('messages.not_found', lang('customer.customer_visit')));
            }

            if($customerVisit->user_id == authUserId() ||  isAdmin()) {
                (new CustomerVisit)->deleteCustomerVisit($id);
                \DB::commit();
                return apiResponse(true, 200, lang('messages.deleted', lang('customer.customer_visit')));
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
    public function getNextFollowupMeetingList()
    {
        try {
            $result = [];
            $meetings  =  (new CustomerVisit)->getNextMeetings();
            if(count($meetings) > 0) {
                foreach( $meetings as $meeting ) {
                    $date = convertToLocal($meeting->next_followup_date, 'd M g:i A');
                    $dateOriginal = convertToLocal($meeting->next_followup_date, 'd-m-Y h:i A');
                    $result[] = [
                        'id' => $meeting->id,
                        'next_followup_date' => $date,
                        'next_followup_date_original' => $dateOriginal,
                        'customer_name' => $meeting->customer_name,
                        'user' => $meeting->name,
                    ];
                }
                return apiResponse(true, 200 , null, [], $result);
            }
            else {
                return apiResponse(false, 404, lang('messages.not_found', lang('customer.no_followup_meetings')));
            }
        }
        catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }
}
