<?php 
namespace App\Http\Controllers;
/**
 * :: Role Controller :: 
 * To manage roles.
 *
 **/

use App\Http\Controllers\Controller;
use App\Role;

class RoleController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('role.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('role.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$inputs = \Input::all();

		$validator = (new Role)->validateRole($inputs);
		if ($validator->fails()) {
			return redirect()->route('role.create')
				->withInput()
				->withErrors($validator);
		}
		try {
			\DB::beginTransaction();
			$inputs = $inputs + [
				'created_by' => authUserId(),
				'company_id' => loggedInCompanyId()
			];
			//dd($inputs);
			(new Role)->store($inputs);
			\DB::commit();
			return redirect()->route('role.index')
				->with('success', lang('messages.created', lang('role.role')));
		} catch (\Exception $exception) {
			dd($exception->getMessage());
			\DB::rollBack();
			return redirect()->route('role.create')
				->withInput()
				->with('error', lang('messages.server_error'));
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function edit($id = null)
	{
		$role = (new Role)->company()->find($id);
		if (!$role) {
			abort(401);
		}

		if ($role->isdefault == 1) {
			return redirect()->route('role.index')
				->with('error', lang('messages.isdefault', string_manip(lang('role.role'))));
		}

		return view('role.edit', compact('role'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function update($id = null)
	{
		$role = (new Role)->company()->find($id);
		if (!$role) {
			return redirect()->route('role.index')
				->with('error', lang('messages.invalid_id', string_manip(lang('role.role'))));
		}

		$inputs = \Input::all();
		$validator = (new Role)->validateRole($inputs, $id);
		if ($validator->fails()) {
			return redirect()->route('role.edit', ['id' => $id])
				->withInput()
				->withErrors($validator);
		}

		try {
			\DB::beginTransaction();
			$inputs = $inputs + [
				'updated_by' => authUserId()
			];
			(new Role)->store($inputs, $id);
			\DB::commit();
			return redirect()->route('role.index')
				->with('success', lang('messages.updated', lang('role.role')));
		} catch (\Exception $exception) {
			\DB::rollBack();
			return redirect()->route('role.create')
				->with('error', lang('messages.server_error'));
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function drop($id)
	{
		return "In Progress";
	}

	/**
	 * Used to update role active status.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function roleToggle($id = null)
	{
		if (!\Request::ajax()) {
			return lang('messages.server_error');
		}

		try {
			$role = (new Role)->company()->find($id);
        } catch (Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('role.role')));
        }

        $role->update(['status' => !$role->status]);
        $response = ['status' => 1, 'data' => (int)$role->status . '.gif'];
        return json_encode($response);
	}

	/**
	 * Used to load more records and render to view.
	 *
	 * @param int $pageNumber
	 *
	 * @return Response
	 */
	public function rolePaginate($pageNumber = null)
	{
		if (!\Request::isMethod('post') && !\Request::ajax()) { //
			return lang('messages.server_error');
		}

		$inputs = \Input::all();

		$page = 1;
		if (isset($inputs['page']) && (int)$inputs['page'] > 0) {
			$page = $inputs['page'];
		}

		$perPage = 20;
		if (isset($inputs['perpage']) && (int)$inputs['perpage'] > 0) {
			$perPage = $inputs['perpage'];
		}

		$start = ($page - 1) * $perPage;
		if (isset($inputs['form-search']) && $inputs['form-search'] != '') {
			$inputs = array_filter($inputs);
			unset($inputs['_token']);

			$data = (new Role)->getRoles($inputs, $start, $perPage);
			$totalRole = (new Role)->totalRoles($inputs);
			$total = $totalRole->total;
		} else {
			
			$data = (new Role)->getRoles($inputs, $start, $perPage);
			$totalRole = (new Role)->totalRoles();
			$total = $totalRole->total;
		}
		//dd($data);
		return view('role.load_data', compact('data', 'total', 'page', 'perPage'));
	}
}