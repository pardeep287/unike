<?php

namespace App\Http\Controllers\Api\V1;

use App\RequestSample;
use App\RequestSampleProducts;
use App\TrackingLog;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TrackingLogController extends Controller
{
	/**
	 * @param Request $request
	 * @param int $page
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index(Request $request, $page = 1)
	{
		$inputs = $request->all();
		//dd($inputs);
		if (isset($inputs['user_id']) && (int)$inputs['user_id'] > 1) {
			$response = [];
			$perPage = 20;
			$start = ($page - 1) * $perPage;
			$result = (new TrackingLog)->getTrackingLog($inputs, $start, $perPage);
			if (count($result) > 0) {
				foreach ($result as $detail) {
					$response[] = [
						'id' => $detail->id,
						'latitude' => $detail->latitude,
						'longitude' => $detail->longitude,
						'time' => convertToLocal($detail->server_time, 'h:i A')
					];
				}
				return apiResponse(true, 200, null, [], $response);
			}
		}
		return apiResponse(false, 404, lang('user.no_mr_found'));
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function store(Request $request)
	{

		try{
			\DB::beginTransaction();
			$inputs = $request->all();
			$validator = (new TrackingLog)->validate($inputs);
			if ($validator->fails()) {
				return apiResponse(false, 406, "", errorMessages($validator->messages()));
			}
			$data = [];
			$users = isset($inputs['user_id']) ? $inputs['user_id'] : [];

			foreach($users as $key => $values) {
				$latitude = (isset($inputs['latitude'][$key]) && $inputs['latitude'][$key] !="") ?
					$inputs['latitude'][$key] : null;

				$longitude = (isset($inputs['longitude'][$key]) && $inputs['longitude'][$key] !="") ?
					$inputs['longitude'][$key] : null;

				$address = (isset($inputs['address'][$key]) && $inputs['address'][$key] !="") ?
					$inputs['address'][$key] : null;

				$currentTime = (isset($inputs['current_time'][$key]) && $inputs['current_time'][$key] !="") ?
					$inputs['current_time'][$key] : null;

				$internetAccess = (isset($inputs['internet_access'][$key]) && $inputs['internet_access'][$key] !="") ?
					$inputs['internet_access'][$key] : null;

				if ($latitude != "" && $longitude != "" && $address != ""
					&& $currentTime != "" && $internetAccess != "") {
					$data[] = [
						'latitude' => $latitude,
						'longitude' => $longitude,
						'address' => $address,
						'internet_access' => $internetAccess,
						'local_time' => dateFormat('Y-m-d H:i:s', $currentTime),
						'server_time' => date('Y-m-d H:i:s'),
						'track_date' => date('Y-m-d'),
						'ip_address' => $request->ip(),
						'company_id' => loggedInCompanyId(),
						'user_id'	 => authUserId()
					];
				}
			}
			/*$inputs = $inputs + [
				'local_time' => dateFormat('Y-m-d H:i:s', $inputs['current_time']),
				'server_time' => date('Y-m-d H:i:s'),
				'track_date' => date('Y-m-d'),
				'ip_address' => $request->ip(),
				'company_id' => loggedInCompanyId(),
				'user_id'	 => authUserId()
			];*/
			if(count($data) > 0) {
				(new TrackingLog)->store($data);
			}
			\DB::commit();
			return apiResponse(true, 200, lang('messages.created', lang('tracking_log.tracking_log')));
		}
		catch (\Exception $exception) {
			\DB::rollBack();
			return apiResponse(false, 500, $exception->getMessage() . $exception->getLine() . lang('messages.server_error'));
		}
		
	}

	/**
	 * @param int $page
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function trackUsersList($page = 1) {

		try {
			$perPage = 20;
			$start = ($page - 1) * $perPage;
			$result = [];
			$data = (new TrackingLog)->getTrackingList($start, $perPage);
			if(count($data) > 0) {
				foreach($data as $row) {
					$result[] = [
						'id' => $row->id,
						'username' => $row->name,
						'status' => ($row->server_time != '')?1:0,
						'server_time'	=> ($row->server_time != '')?convertToLocal($row->server_time, 'h:i A'):null
					];
				}
				return apiResponse(true, 200, null, [], $result);
			}
			return apiResponse(false, 404, lang('user.no_mr_found'));
		}
		catch (\Exception $exception) {
			\DB::rollBack();
			return apiResponse(false, 500, lang('messages.server_error'));
		}


	}
}
