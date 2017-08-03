<?php

namespace App\Http\Controllers;

/**
 * :: Company Controller ::
 * To manage companies.
 *
 **/

use App\Company;
use App\Setting;
use App\Currency;
use App\DateTimeFormat;
use App\Theme;
use App\Timestamp;
use App\Timezone;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;


class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('company.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $timezone = (new Timestamp)->getTimeStampsService();
        //dd($timezone);
        return view('company.create', compact('timezone'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $inputs = \Input::all();

        $tab  = 1;
        $validator = (new Company)->validateCompany($inputs, $tab);
        if ($validator->fails()) {
            return redirect()->route('company.create')
                ->withInput()
                ->withErrors($validator);
        }


        try {
            \DB::beginTransaction();
            $inputs = $inputs + [
                'created_by'    => authUserId()
            ];

            (new Company)->store($inputs);
            \DB::commit();
            return redirect()->route('company.index')
                ->with('success', lang('messages.created', lang('company.company')));
        } catch (\Exception $exception) {
            //dd($exception->getMessage());
            \DB::rollBack();
            return redirect()->route('company.create')
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
        $company = Company::find($id);
        if (!$company) {
            abort(404);
        }

        $tab = \Input::get('tab', '1');
        $company = (new Company)->getCompanyInfo($id);
        $setting = (new Setting)->getSettingByCompanyId($id);
        $currency = (new Currency)->getCurrencyService();
        $timezone = (new Timezone)->getTimezoneService();
        //$theme = (new Theme)->getThemeService();
        $dateTimeFormat = (new DateTimeFormat)->getDateTimeFormatService();
        return view('company.edit', compact('company', 'setting', 'currency', 'timezone',  'dateTimeFormat', 'tab', 'id'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $company = Company::find($id);
        $inputs = \Input::all();
        $tab = $inputs['tab'];
        unset($inputs['tab']);

        if (!$company) {
            return redirect()->route('company.edit', ['id' => $id, 'tab' => $tab])
                ->with('error', lang('messages.invalid_id', string_manip(lang('company.company'))));
        }


        $validator = (new Company)->validateCompany($inputs, $tab);
        if ($validator->fails()) {
            return redirect()->back()->with(['tab' => $tab])
                ->withErrors($validator)->withInput($inputs);
        }

        try {
            \DB::beginTransaction();

            // tab == 1 for company profile detail
            if($tab == 1) {
                if(!array_key_exists('is_full_version', $inputs)) {
                    $inputs = $inputs + ['is_full_version' => 0 ];
                }
                (new Company)->store($inputs, $id);
            }
            // tab == 2 for company logo
            else if($tab == 2) {
                $companyLogo = \Input::file('company_logo');
                $oldCompanyLogo = $company->company_logo;
                $fileName = str_random(6) . '_' . str_replace(' ', '_', $companyLogo->getClientOriginalName());
                $folder = ROOT . \Config::get('constants.UPLOADS');
                if ($companyLogo->move($folder, $fileName)) {
                    if (!empty($oldCompanyLogo) && file_exists($folder . $oldCompanyLogo)) {
                        unlink($folder . $oldCompanyLogo);
                    }
                }
                $data = [
                    'company_logo' => $fileName,
                    'updated_by' => authUserId()
                ];
                (new Company)->store($data, $id);
            }
            // tab == 2 for company logo
            else if($tab == 3) {
                $data = [
                    'company_id' => $id,
                    'currency_id' => $inputs['currency'],
                    'timezone_id' => $inputs['timezone'],
                    'datetime_format_id' => $inputs['datetime_format'],
                    'theme_id' => $inputs['theme'],
                    'is_email_enable' => (isset($inputs['is_email_enable']) && $inputs['is_email_enable'] == '1')?1:0,
                    'is_sms_enable' => (isset($inputs['is_sms_enable']) && $inputs['is_sms_enable'] == '1')?1:0,
                    'status' => (isset($inputs['status']) && $inputs['status'] == '1')?1:0
                ];

                $setting = (new Setting)->getSettingByCompanyId($id);
                if(!$setting){
                    $data['created_by'] = authUserId();
                    (new Setting)->store($data);
                }else{
                    $data['updated_by'] = authUserId();
                    (new Setting)->store($data, $id);
                }
            }

            \DB::commit();
            return redirect()->back()
                ->with(['tab' => $tab, 'success' => lang('messages.updated', lang('company.company'))]);

        } catch (Exception $e) {
            \DB::rollback();
            return redirect()->back(['tab' => $tab])
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
    public function companyToggle($id)
    {
        if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }

        try {
            $company = Company::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('company.company')));
        }

        $company->update(['status' => !$company->status]);
        $response = ['status' => 1, 'data' => (int)$company->status . '.gif'];
        // return json response
        return json_encode($response);
    }

    /**
     * Used to load more records and render to view.
     *
     * @param int $pageNumber
     * @return \Illuminate\Http\Response
     */
    public function companyPaginate(Request $request, $pageNumber = null)
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

                $data = (new Company)->getCompany($inputs, $start, $perPage);
                $total = (new Company)->totalCompany($inputs);
                $total = $total->total;
            } else {
                $data = (new Company)->getCompany($inputs, $start, $perPage);
                $total = (new Company)->totalCompany($inputs);
                $total = $total->total;
            }

            return view('company.load_data', compact('data', 'total', 'page', 'perPage'));
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
    public function companyAction()
    {
        $inputs = \Input::all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('company.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('company.company'))));
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
        return redirect()->route('company.index')
            ->with('success', lang('messages.updated', lang('company.company_status')));
    }

    /**
     * @return String
     */
    public function mrUserSearch()
    {
        if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }
        $name = \Input::get('name', '');
        $result = "";
        if ($name != "") {
            $data = (new Company)->companySearch($name);
            foreach($data as $detail) {
                $result[] = $detail->id . "|" . $detail->first_name . " " . $detail->last_name . " (" . $detail->phone . ")";
            }
            echo json_encode($result);
        }
    }

    /**
     * @return String
     */
    public function getCompanysSearch($id)
    {
        if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }
        $result = (new Company)->companySearch($id);
        $options = '';
        foreach($result as $key => $value) {
            $options .='<option value="'. $key .'">' . $value . '</option>';
        }
        echo $options;
    }
}
