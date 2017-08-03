<?php

namespace App\Http\Controllers;

use App\FinancialYear;

class FinancialYearController  extends  Controller{
    /**
     * Display a listing of resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function  index(){ 
        $t = \Input::get('t');
        return view('financial-year.index', compact('t'));
    }
    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function  create()
    {
        return view('financial-year.create');
    }

    /**
     * Store a newly created resource in storage.
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function  store(){

        $inputs = \Input::all();
        $fromDate = dateFormat('Y-m-d', $inputs['from_date']);
        unset($inputs['from_date']);
        $toDate = dateFormat('Y-m-d', $inputs['to_date']);
        unset($inputs['to_date']);
        $inputs['from_date'] = $fromDate;
        $inputs['to_date'] = $toDate;
        $validator = (new FinancialYear)->validateFinancialYear($inputs);
        if ($validator->fails()) { 
            return validationResponse(false, 206, "", "", $validator->messages());
        }
        try {

            if(array_key_exists('status', $inputs)) {
                (new FinancialYear)->updateStatusAll();
            }

            $inputs = $inputs + [
                    'from_date' => $fromDate,
                    'to_date' => $toDate,
                    'company_id' => loggedInCompanyId(),
                    'created_by' => authUserId()
                ];  
            \DB::beginTransaction();
            (new FinancialYear)->store($inputs);
            \DB::commit(); 
            $route = route('financial-year.index');
            $lang  = lang('messages.created', lang('financial_year.financial_year'));
            return validationResponse(true, 201, $lang, $route);

        } catch (\Exception $exception) {
            \DB::rollBack(); 
            return validationResponse(false, 207, lang('messages.server_error'));
        }
    }

    /**
     * Updating the record
     * @param null $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update($id = null)
    {
        $result = (new FinancialYear)->company()->find($id);
        if (!$result) {
            abort(401);
        }

        $inputs = \Input::all();
        $fromDate = dateFormat('Y-m-d', $inputs['from_date']);
        unset($inputs['from_date']);
        $toDate = dateFormat('Y-m-d', $inputs['to_date']);
        unset($inputs['to_date']);
        $inputs['from_date'] = $fromDate;
        $inputs['to_date'] = $toDate;
        $validator = (new FinancialYear)->validateFinancialYear($inputs, $id);
        if ($validator->fails()) {
            return validationResponse(false, 206, "", "", $validator->messages());
        }

        try {
            \DB::beginTransaction();
            $inputs = $inputs + [
                    'status' => (isset($inputs['status'])?1:0),
                    'updated_by' => authUserId()
                ];
             if($inputs['status'] == 1)
             {
                 (new FinancialYear)->updateStatusAll();
             }
            (new FinancialYear())->store($inputs, $id);
            \DB::commit();
            $route = route('financial-year.index');
            $lang = lang('messages.updated', lang('financial_year.financial_year'));
            return validationResponse(true, 201, $lang, $route);

        } catch (\Exception $exception) {
            \DB::rollBack();
            return validationResponse(false, 207, lang('messages.server_error'));
        }
    }

    /**
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id = null)
    {
        $result = (new FinancialYear)->company()->find($id);
        if (!$result) {
            abort(401);
        }

        return view('financial-year.edit', compact('result'));
    }

    /**
     * used for financial year pagination
     * @param null $pageNumber
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|String
     */
    public function financialYearPaginate($pageNumber = null)
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

            $data = (new FinancialYear)->getFinancialYears($inputs, $start, $perPage);
            $totalFinancialYear = (new FinancialYear())->totalFinancialYears($inputs);
            $total = $totalFinancialYear->total;
        } else {

            $data = (new FinancialYear)->getFinancialYears($inputs, $start, $perPage);
            $totalFinancialYear = (new FinancialYear)->totalFinancialYears();
            $total = $totalFinancialYear->total;
        }

        return view('financial-year.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }

    /**
     * code for toggle - financial year status
     * @param null $id
     * @return string
     */
    public function financialYearToggle($id = null)
    {
        if (!\Request::ajax()) {
            return lang('messages.server_error');
        }
        try {
            /* Changing the status of the all the financial year */
            (new FinancialYear)->updateStatusAll();
            $result = FinancialYear::find($id);
        } catch (Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('financial_year.financial_year')));
        }
        $result->update(['status' => !$result->status]);
        $response = ['status' => 1, 'data' => (int)$result->status . '.gif'];
        return json_encode($response);
    }

    /**
     * Remove the specified resource from storage
     * @param $id
     * @return string
     */
    public function drop($id)
    {


        if (!\Request::ajax()) {
            return lang('messages.server_error');
        }

        $result = (new FinancialYear)->company()->find($id);
        if (!$result) {
            abort(401);
        }

        try {
            // get the unit w.r.t id
             $result = (new FinancialYear)->company()->find($id);
             if($result->status == 1) {
                 $response = ['status' => 0, 'message' => lang('financial_year.financial_year_in_use')];
             }
             else {
                 (new FinancialYear)->drop($id);
                 $response = ['status' => 1, 'message' => lang('messages.deleted', lang('financial_year.financial_year'))];
             }
        }
        catch (Exception $exception) {
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }        
        // return json response
        return json_encode($response);
    }
}