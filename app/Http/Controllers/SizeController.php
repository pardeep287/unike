<?php

namespace App\Http\Controllers;

use App\Size;
use App\ProductSizes;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('size.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('size.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $inputs = \Input::all();


        $validator = (new Size)->validateSize($inputs);
        if ($validator->fails()) {

            return validationResponse(false, 206, "", "", $validator->messages());
        }


        try {
            \DB::beginTransaction();
            $inputs = $inputs + [
                    'company_id'    => loggedInCompanyId(),
                    'created_by'    => authUserId(),
                ];

            (new Size)->store($inputs);
            \DB::commit();
            $route = route('size.index');
            $lang = lang('messages.updated', lang('size.size'));
            return validationResponse(true, 201, $lang, $route);
        } catch (\Exception $exception) {

            \DB::rollBack();
           /* return redirect()->route('hsn.create')
                ->withInput()
                ->with('error', $exception->getMessage() . lang('messages.server_error'));*/
            return validationResponse(false, 207, lang('messages.server_error'));
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id = null)
    {
        $result = Size::find($id);

        if (!$result) {
            abort(404);
        }
        //$company = (new Company)->all()->first();
        return view('size.edit', compact('result', 'id'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {

        $size = Size::find($id);
        $inputs = \Input::all();

        if (!$size) {
            return redirect()->route('size.edit', ['id' => $id])
                ->with('error', lang('messages.invalid_id', string_manip(lang('size.size'))));
        }


        $validator = (new Size)->validateSize($inputs,$id);
        if ($validator->fails()) {

            return validationResponse(false, 206, "", "", $validator->messages());
        }

        try {
            \DB::beginTransaction();
            if(!array_key_exists('updated_by', $inputs)) {
                $inputs = $inputs + ['updated_by' => authUser()->id ];
            }

            (new Size)->store($inputs, $id);
            \DB::commit();
            $route = route('size.index');
            $lang = lang('messages.updated', lang('size.size'));
            return validationResponse(true, 201, $lang, $route);
            /*return redirect()->route('size.index')
                ->with(['success' => lang('messages.updated', lang('size.size'))]);*/

        } catch (Exception $e) {
            \DB::rollback();
            /*return redirect()->back()
                ->withInput($inputs)
                ->with('error', lang('messages.server_error'));*/
            return validationResponse(false, 207, lang('messages.server_error'));
        }
    }
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function drop($id)
    {
        if (!\Request::ajax()) {
            return lang('messages.server_error');
        }

        try {
            $check = (new Size)->sizeExists($id);

            if($check) {
                $response = ['status' => 1, 'message' => lang('size.size_in_use')];
            }
            else {
                (new Size)->drop($id);
                $response = ['status' => 1, 'message' => lang('messages.deleted', lang('size.size'))];
            }

        } catch (Exception $exception) {
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }

        return json_encode($response);
    }

    /**
     * Used to update company active status.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function sizeToggle($id)
    {
        if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }


        try {
            $size = Size::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('size.size')));
        }

        $size->update(['status' => !$size->status]);
        $response = ['status' => 1, 'data' => (int)$size->status . '.gif'];
        // return json response
        return json_encode($response);
    }

    /**
     * Used to load more records and render to view.
     *
     * @param int $pageNumber
     * @return \Illuminate\Http\Response
     */
    public function sizePaginate(Request $request, $pageNumber = null)
    {

        if (!\Request::isMethod('post') && !\Request::ajax()) {

            return lang('messages.server_error');
        }

        try {


            $inputs = $request->all();

            // dd($inputs);

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

                $data = (new Size)->getSize($inputs, $start, $perPage);
                $total = (new Size)->totalSize($inputs);
                $total = $total->total;
            } else {
                $data = (new Size)->getSize($inputs, $start, $perPage);
                $total = (new Size)->totalSize($inputs);
                $total = $total->total;
            }


            return view('size.load_data', compact('data', 'total', 'page', 'perPage', 'inputs'));
        }
        catch (\Exception $exception) {

            echo 'Error'. $exception->getMessage();
        }
    }

    /**
     * Method is used to update status of group enable/disable
     *
     * @return \Illuminate\Http\Response
     */
    public function sizeAction()
    {
        $inputs = \Input::all();

        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('hsn.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('hsn.hsn'))));
        }

        $ids = '';
        foreach ($inputs['tick'] as $key => $value) {
            $ids .= $value . ',';
        }

        $ids = rtrim($ids, ',');
        $status = 0;
        if (isset($inputs['active'])) {
            $status = 1;
        }

        Company::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('hsn.index')
            ->with('success', lang('messages.updated', lang('hsn.hsn_status')));
    }
}
