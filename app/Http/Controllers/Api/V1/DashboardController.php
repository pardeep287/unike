<?php
/**
 * Created by PhpStorm.
 * User: Inderjit Singh
 * Date: 4/17/2017
 * Time: 5:21 PM
 */
namespace App\Http\Controllers\Api\V1;
use App\CustomerVisit;
use App\Http\Controllers\Controller;
use League\Flysystem\Exception;
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $customerVisits = $this->getMeeting();
            $feedback = $this->getFeedback();
            $nextMeetings =  count($this->getNextWeekMeetings());

            $result = [
                'today_meetings' => $customerVisits,
                'today_meetings_count' => count($customerVisits),
                'overall_feedback'  => $feedback,
                'next_meetings' => $nextMeetings,
                'notification_count' => getUnseenNotification(),
            ];
            return apiResponse(true, 200 , null, [], $result);
        }
        catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }

    }
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMeeting()
    {
        try {
        $result = [];
        $currentDate = date('Y-m-d');
        $meetings = (new CustomerVisit)->getCustomerVisit([ 'visit_date' => $currentDate ]);

        foreach( $meetings as $meeting ) {
            $date = convertToLocal($meeting->visit_date, 'd-m-Y H:i:s');
            $result[] = [
                'customer_id' => $meeting->customer_id,
                'customer_name' => $meeting->customer_name,
                'user_id' => $meeting->user_id,
                'user_name' => $meeting->username,
                'name'      => $meeting->name,
                'time'          => date('g:i A', strtotime($date))
            ];
        }
        return $result;
    }
        catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }

    /**
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function getFeedback()
    {
        try{
           $result = [];
           $feedbackResult = getOverAllFeedback();
           if( count($feedbackResult) > 0) {
                foreach( $feedbackResult as $key => $value ) {
                    $result[] = [
                        'id' => $key,
                        'name' => $value
                    ];
                }
           }
           return $result;
        }
        catch (Exception $exception) {
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }

    /**
     * @return array
     */
    public function getNextWeekMeetings()
    {
        $result = [];
        $meetings  =  (new CustomerVisit)->getNextMeetings();
        if(count($meetings) > 0) {
            foreach( $meetings as $meeting ) {
                $date = convertToLocal($meeting->next_followup_date, 'd-m-Y H:i:s');
                $result[] = [
                    'id' => $meeting->id,
                    'next_followup_date' => date('d M g:i A', strtotime($date)),
                    'customer_name' => $meeting->customer_name,
                    'user' => $meeting->username,
                ];
            }

            return $result;
        }
    }
}
