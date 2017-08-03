<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\NotificationLog;
use App\User;
use Illuminate\Http\Request;
use League\Flysystem\Exception;


class NotificationController extends Controller
{
    /**
     * @param Request $request
     * @param int $page
     * @return \Illuminate\Http\JsonResponse
     */
    public function listNotifications(Request $request, $page = 1)
    {
        try {
            $result['notifications'] = [];
            $inputs = $request->all();
            $perPage = 20;
            $start = ($page - 1) * $perPage;
            $notifications = (new NotificationLog)->getNotifications($inputs, $start, $perPage);
            if(count($notifications) > 0) {
                foreach($notifications as $data) {
                    $date = convertToLocal($data->created_at, 'd-m-Y');
                    $result['notifications'][]  = [
                        'id'         => $data->id,
                        'message'   => $data->message,
                        'type'      => $data->type,
                        'date'     => $date,
                        'time'     => convertToLocal($data->created_at, 'h:i A')
                       ];
                }
            }
            return apiResponse(true, 200 , null, [], $result);
        } catch (Exception $exception) {
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }

    /**
     * @param null $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function notificationSeen($id = null)
    {
        try {
            $result = User::find($id);
            if(!$result) {
                return apiResponse(false, 404, lang('messages.not_found', lang('user.user')));
            }

            \DB::beginTransaction();
                (new NotificationLog)->updateIsSeenByUser($id, ['is_seen' => 1]);
            \DB::commit();
             return apiResponse(true, 200, lang('alternate.notification_seen'));
        }
        catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }
}
