<?php 
namespace App\Http\Controllers;
/**
 * :: Tax Controller ::
 * To manage tax.
 *
 **/

use App\TaxRates;
use App\Http\Controllers\Controller;
use App\Tax;

class TaxController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('tax.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('tax.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$inputs = \Input::all();
		$validator = (new Tax)->validateTax($inputs);
		if ($validator->fails()) {
			return redirect()->route('tax.create')
				->withInput()
				->withErrors($validator);
		}
		try {
			\DB::beginTransaction();
			$inputs = $inputs + [
				'created_by' => authUserId(),
				'company_id' => loggedInCompanyId()
			];

			$inputs['wef'] = convertToUtc();
			(new Tax)->store($inputs);
			\DB::commit();
			return redirect()->route('tax.index')
				->with('success', lang('messages.created', lang('tax.tax')));
		} catch (\Exception $exception) {
			\DB::rollBack();
			return redirect()->route('tax.create')
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
		$result = Tax::find($id);
		if (!$result) {
			abort(404);
		}
		$check = (new Tax)->taxExists($id);

		if($check) {
			//$response = ['status' => 1, 'message' => lang('size.size_in_use')];
			return redirect()->route('tax.index')
				->with('error', lang('tax.noteditable', string_manip(lang('tax.tax_in_use'))));
		}



		return view('tax.edit', compact('result', 'id'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function update($id = null)
	{
		$result = Tax::find($id);
		if (!$result) {
			return redirect()->route('tax.index')
				->with('error', lang('messages.invalid_id', string_manip(lang('tax.tax'))));
		}

		$inputs = \Input::all();
		$validator = (new Tax)->validateTax($inputs, $id);
		if ($validator->fails()) {
			return redirect()->route('tax.edit', ['id' => $id])
				->withInput()
				->withErrors($validator);
		}

		try {
			\DB::beginTransaction();
			$inputs = $inputs + [
				'updated_by' => authUserId()
			];
			$inputs['wef'] = convertToUtc();
			(new Tax)->store($inputs, $id);
			\DB::commit();
			return redirect()->route('tax.index')
				->with('success', lang('messages.updated', lang('tax.tax')));
		} catch (\Exception $exception) {
			\DB::rollBack();
			return redirect()->route('tax.edit', ['id' => $id])
				->with('error', $exception->getMessage() . lang('messages.server_error'));
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
	 * Used to update tax active status.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function taxToggle($id = null)
	{
		if (!\Request::ajax()) {
			return lang('messages.server_error');
		}

		try {
            // get the tax w.r.t id
            $result = Tax::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('tax.tax')));
        }

		$result->update(['status' => !$result->status]);
        $response = ['status' => 1, 'data' => (int)$result->status . '.gif'];
        // return json response
        return json_encode($response);
	}

	/**
	 * Used to load more records and render to view.
	 *
	 * @param int $pageNumber
	 *
	 * @return Response
	 */
	public function taxPaginate($pageNumber = null)
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

			$data = (new Tax)->getTax($inputs, $start, $perPage);
			$totalTax = (new Tax)->totalTax($inputs);
			$total = $totalTax->total;
		} else {

			$data = (new Tax)->getTax($inputs, $start, $perPage);
			$totalTax = (new Tax)->totalTax($inputs);
			$total = $totalTax->total;
		}


		return view('tax.load_data', compact('data', 'total', 'page', 'perPage','inputs'));
	}
}