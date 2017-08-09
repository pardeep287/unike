<?php

namespace App\Http\Controllers;

/**
 * :: Customer Controller ::
 * To manage customers.
 *
 **/

use App\Customer;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Tax;
use App\User;
use App\Timestamp;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('customer.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $code= (new Customer)->getCustomerCode();
        return view('customer.create', compact( 'code'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $inputs = \Input::all();
        $data = \Input::all();

        /*$inputs = $inputs + [
            'email1' => $inputs['email'],
            'mobile1' => $inputs['mobile'],
        ];

        unset($inputs['mobile']);
        unset($inputs['email']);

        unset($inputs['address1']);
        unset($inputs['address2']);
        unset($inputs['country']);
        unset($inputs['state']);
        unset($inputs['city']);
        unset($inputs['pincode']);*/
        $validator = (new Customer)->validateCustomer($inputs);
        if ($validator->fails()) {
            unset($inputs['customer_code']);
            return redirect()->route('customer.create')
                ->withInput($inputs)
                ->withErrors($validator);
        }
        try {
            \DB::beginTransaction();

            if ($inputs['password'] != "") {
                $user = [
                    'name' => $inputs['customer_name'],
                    'username' => $inputs['username'],
                    'email' => $inputs['email'],
                    'role_id' => 2,
                    'company_id' => loggedInCompanyId(),
                    'password' => \Hash::make($inputs['password']),
                    'created_by' => authUserId(),
                ];

                $userId = (new User)->store($user);
                $inputs = $inputs + [
                        'salutation'    => 'M/S.',
                        'created_by'    => authUserId(),
                        'company_id'    => loggedInCompanyId(),
                        'user_id'    => $userId,

                    ];
                $customerId = (new Customer)->store($inputs);

            }

            /*$priceList = array_filter($data['price_list']);
            if (count($priceList) > 0) {
                foreach ($data['price_list'] as $key => $value) {
                    if ($value > 0) {
                        $list[] = [
                            'customer_id' => $customerId,
                            'price_list_id' => $value
                        ];
                    }
                }
                (new CustomerPriceList)->store($list);
            }*/
            /*    $list[] = [
                'customer_id' => $customerId,
                'price_list_id' => $inputs['price_list']
            ];
            (new CustomerPriceList)->store($list);

            $taxes = array_filter($data['taxes']);
            if (count($taxes) > 0) {
                foreach ($data['taxes'] as $key => $value) {
                    if ($value > 0) {
                        $tax[] = [
                            'customer_id' => $customerId,
                            'tax_id' => $value
                        ];
                    }
                }
                (new CustomerTaxes)->store($tax);
            }*/
            \DB::commit();
            if (isset($inputs['save_edit'])) {
                return redirect()->route('customer.edit', ['id' => $customerId, 'tab' => 1]);
            }
            return redirect()->route('customer.index')
                ->with('success', lang('messages.created', lang('customer.customer')));
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->route('customer.create')
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
    public function edit($id)
    {
        $result = Customer::find($id);

        if (!$result) {
            abort(404);
        }

        $tab = \Input::get('tab', 1);

        /*$list = (new CustomerPriceList)->getPriceLists(['customer_id' => $id]);
        $prices  = [];
        if ($result) {
            $prices = array_column($list->toArray(), 'price_list_id');
        }

        $appliedTaxes  = [];
        $taxes = (new CustomerTaxes)->getTaxes(['customer_id' => $id]);
        if ($taxes) {
            $appliedTaxes = array_column($taxes->toArray(), 'tax_id');
        }*/

        $user = User::find($result->user_id);
        //dd($user);
        //$priceLists = (new PriceList)->getPriceListService();
        //$taxes = (new Tax)->getTaxesService();
        return view('customer.edit', compact('result',  'user', 'tab'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {

        $customer = Customer::find($id);
        if (!$customer) {
            return redirect()->route('customer.index')
                ->with('error', lang('messages.invalid_id', string_manip(lang('customer.customer'))));
        }

        $inputs = \Input::all();

        unset($inputs['address2']);

        $data = \Input::all();


        $validator = (new Customer)->validateCustomer($inputs, $id);
        if ($validator->fails()) {
            return redirect()->route('customer.edit', ['id' => $id])
                ->withInput()
                ->withErrors($validator);
        }
       // dd($inputs,$customer);
        try {
            \DB::beginTransaction();
            $inputs = $inputs + [
                'status' => isset($inputs['status']) ? 1 : 0,
                'updated_by'    => authUserId(),
            ];
           
            (new Customer)->store($inputs, $id);
           // dd($inputs);
            if ($inputs['password'] != "") {
                $user = [
                    'name'      => $inputs['customer_name'],
                    'username'  => $inputs['username'],
                    'password'  => \Hash::make($inputs['password']),
                ];
                if ($customer->user_id == "") {
                    $userId = (new User)->store($user);
                    (new Customer)->store(['user_id' => $userId], $id);
                } else {
                    (new User)->store($user, $customer->user_id);
                }
            }
            //(new CustomerPriceList)->updatePriceList($id, $inputs['price_list']);

            /*
            22-02-2017 changed customer have only one price list.
            $oldPrices = [];
            $result = (new CustomerPriceList)->getPriceLists(['customer_id' => $id]);
            if ($result) {
                $oldPrices = array_column($result->toArray(), 'price_list_id');
            }

            $deletedPrice = array_diff($oldPrices, []);
            $priceList = (isset($data['price_list'])) ? array_filter($data['price_list']) : [];
            if (count($priceList) > 0) {
                $newPrices = array_values($data['price_list']);
                $deletedPrice = array_diff($oldPrices, $newPrices);
                $newAdded = array_diff($newPrices, $oldPrices);

                if (count($newAdded) > 0) {
                    foreach ($newAdded as $key => $value) {
                        if ($value > 0) {
                            $save[] = [
                                'customer_id' => $id,
                                'price_list_id' => $value
                            ];
                        }
                    }
                    (new CustomerPriceList)->store($save);
                }
            }

            if (count($deletedPrice) > 0) {
                foreach ($deletedPrice as $key => $value) {
                    if ($value > 0) {
                        $deletePrices[] = $value;
                    }
                }
                (new CustomerPriceList)->deletePrices($id, $deletePrices);
            }*/

            /*$oldTaxes = $save = [];
            $result = (new CustomerTaxes)->getTaxes(['customer_id' => $id]);
            if ($result) {
                $oldTaxes = array_column($result->toArray(), 'tax_id');
            }

            $deletedTax = array_diff($oldTaxes, []);
            $taxes = (isset($data['taxes'])) ? array_filter($data['taxes']) : [];
            if (count($taxes) > 0) {
                $newTaxes = array_values($data['taxes']);
                $deletedTax = array_diff($oldTaxes, $newTaxes);
                $newAdded = array_diff($newTaxes, $oldTaxes);

                if (count($newAdded) > 0){
                    foreach ($newAdded as $key => $value) {
                        if ($value > 0) {
                            $save[] = [
                                'customer_id' => $id,
                                'tax_id' => $value
                            ];
                        }
                    }
                    (new CustomerTaxes)->store($save);
                }
            }

            if (count($deletedTax) > 0) {
                foreach ($deletedTax as $key => $value) {
                    if ($value > 0) {
                        $deleteTaxes[] = $value;
                    }
                }
                (new CustomerTaxes)->deleteTaxes($id, $deleteTaxes);
            }*/

            \DB::commit();
            return redirect()->route('customer.index')
                ->with('success', lang('messages.updated', lang('customer.customer')));
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->route('customer.edit', ['id' => $id])
                ->with('error', $exception->getMessage() . lang('messages.server_error'));
        }
    }

    /**
     * Used to load more records and render to view.
     *
     * @param int $pageNumber
     * @return \Illuminate\Http\Response
     */
    public function customerPaginate($pageNumber = null)
    {
        if (!\Request::isMethod('post') && !\Request::ajax()) {
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

            $data = (new Customer)->getCustomers($inputs, $start, $perPage);
            $total = (new Customer)->totalCustomers($inputs);
            $total = $total->total;
        } else {
            $data = (new Customer)->getCustomers($inputs, $start, $perPage);
            $total = (new Customer)->totalCustomers($inputs);
            $total = $total->total;
        }

        return view('customer.load_data', compact('data', 'total', 'page', 'perPage'));
    }

    /**
     * Method is used to update status of group enable/disable
     *
     * @return \Illuminate\Http\Response
     */
    public function customerToggle($id = null)
    {
        if (!\Request::ajax()) {
            return lang('messages.server_error');
        }
        
        try {
            // get the customer w.r.t id
            $result = Customer::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('customer.customer')));
        }

        $result->update(['status' => !$result->status]);
        $response = ['status' => 1, 'data' => (int)$result->status . '.gif'];
        // return json response
        return json_encode($response);
    }

    /**
     * @return String
     */
    public function getCustomersSearch($id)
    {
        if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }
        $result = (new Customer)->customerSearch($id);
        $options = '';
        foreach($result as $key => $value) {
            $options .='<option value="'. $key .'">' . $value . '</option>';
        }
        echo $options;
    }

    /**
     * @param $id
     * @return String
     */
    public function getPriceList($id)
    {
        if (!\Request::ajax()) {
            return lang('messages.server_error');
        }
        $result = (new CustomerPriceList)->getPriceListService($id);
        $options = '';
        foreach($result as $key => $value) {
            $options .='<option value="'. $key .'">' . $value . '</option>';
        }
        echo $options;
        exit(0);
    }
}
