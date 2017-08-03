<?php

namespace App\Http\Controllers;

use App\Hsn;
use App\Company;
use Illuminate\Http\Request;

class HsnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('hsn.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('hsn.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $inputs = \Input::all();


        $validator = (new Hsn)->validateHsn($inputs);
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }


        try {
            \DB::beginTransaction();
            $inputs = $inputs + [
                    'created_by'    => authUserId()
                ];

            (new Hsn())->store($inputs);
            \DB::commit();
            return redirect()->route('hsn.index')
                ->with('success', lang('messages.created', lang('hsn.hsn')));
        } catch (\Exception $exception) {

            \DB::rollBack();
            return redirect()->route('hsn.create')
                ->withInput()
                ->with('error', $exception->getMessage() . lang('messages.server_error'));
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
        $hsn = Hsn::find($id);

        if (!$hsn) {
            abort(404);
        }
        //$company = (new Company)->all()->first();
        return view('hsn.edit', compact('hsn', 'id'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $hsn = Hsn::find($id);
        $inputs = \Input::all();

        if (!$hsn) {
            return redirect()->route('hsn.edit', ['id' => $id])
                ->with('error', lang('messages.invalid_id', string_manip(lang('hsn.hsn'))));
        }


        $validator = (new Hsn)->validateHsn($inputs,$id);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)->withInput($inputs);
        }

        try {
            \DB::beginTransaction();
            if(!array_key_exists('updated_by', $inputs)) {
                $inputs = $inputs + ['updated_by' => authUser()->id ];
            }

            (new Hsn)->store($inputs, $id);
            \DB::commit();
            return redirect()->route('hsn.index')
                ->with(['success' => lang('messages.updated', lang('hsn.hsn'))]);

        } catch (Exception $e) {
            \DB::rollback();
            return redirect()->back()
                ->withInput($inputs)
                ->with('error', lang('messages.server_error'));
        }
    }

    /**
     * Used to update company active status.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function hsnToggle($id)
    {
        if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }


        try {
            $hsn = Hsn::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('hsn.hsn')));
        }

        $hsn->update(['status' => !$hsn->status]);
        $response = ['status' => 1, 'data' => (int)$hsn->status . '.gif'];
        // return json response
        return json_encode($response);
    }

    /**
     * Used to load more records and render to view.
     *
     * @param int $pageNumber
     * @return \Illuminate\Http\Response
     */
    public function hsnPaginate(Request $request, $pageNumber = null)
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

                $data = (new Hsn)->getHsn($inputs, $start, $perPage);
                $total = (new Hsn)->totalHsn($inputs);
                $total = $total->total;
            } else {
                $data = (new Hsn())->getHsn($inputs, $start, $perPage);
                $total = (new Hsn())->totalHsn($inputs);
                $total = $total->total;
            }


            return view('hsn.load_data', compact('data', 'total', 'page', 'perPage', 'inputs'));
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
    public function hsnAction()
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
