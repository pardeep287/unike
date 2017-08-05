<?php

namespace App;
/**
 * :: Invoice Model ::
 * To manage Invoice CRUD operations
 *
 **/

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;

class Invoice extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'invoice_master';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'financial_year_id',
        'account_id',
        'ledger_id',
        'company_id',
        'sale_order_id',
        'bank_id',
        'cash_credit',
        'sale',
        'sale_type',
        'order_date',
        'invoice_number',
        'invoice_date',
        'order_number',
        'carriage',
        'through',
        'dispatch_to',
        'weight',
        'private_mark',
        'no_of_cases',
        'vehicle_no',
        'cgst_total',
        'sgst_total',
        'igst_total',
        'freight',
        'round_off',
        'other_charges',
        'gross_amount',
        'net_amount',
        'status',
        'is_email_sent',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * Scope a query to only include active users.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('invoice_master.status', 1);
    }

    /**
     * Scope a query to only include active users.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFinancialYear($query)
    {
        return $query->where('invoice_master.financial_year_id', financialYearId());
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeCompany($query)
    {
        return $query->where('invoice_master.company_id', loggedInCompanyId());
    }
    
    /**
     * @return string
     */
    public function getNewPONumber()
    {
        $data =  $this->financialyear()->company()
            ->first([\DB::raw('max(invoice_number) as invoice_number')]);
        if (count($data) == 0) {
            $number = '01';
        } else {
            $number = paddingLeft(++$data->invoice_number); 
            // new sale order number increment by 1
        }
        return $number;
    }

    /**
     * @param array $inputs
     * @param int $id
     *
     * @return \Illuminate\Validation\Validator
     */
    public function validateInvoice($inputs, $id = null, $isApi = false , $isEdited = false)
    {
        //dd($inputs);
        $rules = [
            'account' => 'required|numeric',
            'invoice_date' => 'required|date|date_format:d-m-Y|before:'. date('d-m-Y', strtotime("+1 days")),
            'order_date' => 'date|date_format:d-m-Y|before:invoice_date',
            //'invoice_date' => 'required|date|after:'. date('Y-m-d', strtotime("-1 days")),
            //'ledger' => 'required|numeric',
            'sale' => 'required|numeric',
            'cash_credit' => 'required|numeric',
        ];

        if($id) {
            $rules['invoice_number'] = 'unique:invoice_master,invoice_number,' . $id . ',id,deleted_at,NULL,financial_year_id,' . financialYearId();
        } else {
            $rules['invoice_number'] = 'required|unique:invoice_master,invoice_number,NULL,id,deleted_at,NULL,financial_year_id,' . financialYearId();
        }

        if($isApi) 
        {
            if(isset($inputs['product']) && isset($inputs['quantity']) && isset($inputs['price']) && isset($inputs['manual_price']) )
            {
                foreach($inputs['product'] as $key => $value)
                {
                    $message =
                        [
                            'product.'.$key.'.required'      => lang('messages.product_required'),
                            'quantity.'.$key.'.required'     => lang('messages.quantity_required'),
                            'quantity.'.$key.'.numeric'      => lang('messages.quantity_num'),
                            'quantity.'.$key.'.min:1'        => lang('messages.quantity_min'),
                            'price.'.$key.'.required'        => lang('messages.price_required'),
                            'price.'.$key.'.numeric'         => lang('messages.price_num'),
                            'manual_price.'.$key.'.numeric'  => lang('messages.manual_price_num')
                        ];

                        if($inputs['price'][$key] == "") {
                            $rules['manual_price.'.$key] =  'required|numeric';
                            $message = [
                                'manual_price.'.$key.'.required'  => lang('messages.one_price_required'),
                                'manual_price.'.$key.'.numeric' => lang('messages.manual_price_num')
                           ];
                        }

                        if ($isEdited) {
                            $rules['invoice_master_item_id.'. $key]        = 'numeric';
                        }
                        
                        $rules['product.'. $key]        = 'required|numeric';
                        $rules['quantity.'. $key]       = 'required|numeric';
                        $rules['price.'.   $key]        = 'numeric';
                }
            }
            else {
                $rules['product.0']        = 'required';
                $rules['hsn_code.0']        = 'required';
                $rules['quantity.0']       = 'required|numeric';
                $rules['unit.0']            = 'required';
                $rules['price.0']          = 'required|numeric';
                $rules['cgst.0']            = 'required|numeric';
                $rules['sgst.0']          = 'required|numeric';
                $rules['manual_price.0']   = 'numeric|numeric|min:1';
            }
        } else {
          if (isset($inputs['addmore']) || isset($inputs['update_item'])) {
              $rules['product'] = 'required';
              $rules['quantity'] = 'required|numeric';
              $rules['tax_id'] = 'required';
              $rules['price'] = 'required|numeric';
              $rules['manual_price'] = 'required|numeric';
          } else {
              if(!$id) {
                  $rules['product'] = 'required';
                  $rules['tax_id'] = 'required';
                  $rules['quantity'] = 'required|numeric';
                  $rules['price'] = 'required|numeric';
                  $rules['manual_price'] = 'required|numeric';
              }
          }
        }

        $messages = [
            'account.required' => 'The party head field is required.',
            'account.numeric' => 'The party head field must be numeric.',

            'ledger.required' => 'The ledger field is required.',
            'ledger.numeric' => 'The ledger field must be numeric.',

            'cash_credit.required' => 'The mode field is required.',
            'cash_credit.numeric' => 'The mode field must be numeric.',

            'tax_id.required' => 'Product tax not defined, please define tax for product.',

            'manual_price.required' => 'The price field is required.',
            'manual_price.numeric' => 'The price must be a numeric.',
            'manual_price.min' => 'The price must be at least 1.',
        ];

        return \Validator::make($inputs, $rules, $messages);
    }
    
    /**
     * @param array $inputs
     * @param int $id
     *
     * @return mixed
     */
    public function store($inputs, $id = null)
    {
        if ($id) {
            $this->find($id)->update($inputs);
            if (isset($inputs['update_item'])) {
                (new InvoiceItems)->where('id',  $inputs['item_id'])->update($inputs);
            } else {
                if ($inputs['product'] != "" && $inputs['quantity'] != "") {
                    $inputs['invoice_id'] = $id;
                    (new InvoiceItems)->store($inputs);
                }
            }
            return $id;
        } else {
            $id = $this->create($inputs)->id;
            $inputs['invoice_id'] = $id;
            (new InvoiceItems)->store($inputs);
            return $id;
        }
    }

    /**
     * @param array $inputs
     * @param null $id
     * @return mixed
     */
    public function invoiceUpdate($inputs = [], $id = null)
    {
        return $this->find($id)->update($inputs);
    }

    /**
     * Method is used to search total results.
     * @param array $search
     * @param int $skip
     * @param int $perPage
     * @return mixed
     */
    public function getInvoices($search = null, $skip, $perPage)
    {
      trimInputs();
      $take = ((int)$perPage > 0) ? $perPage : 20;      
      $fields = [
          'invoice_master.id',
          'account_master.account_name as account',
          'invoice_number',
          'invoice_date',
          'gross_amount',
          'cgst_total',
          'sgst_total',
          'igst_total',
          'net_amount',
          'invoice_master.status',
          'invoice_master.is_email_sent'
      ];

      $sortBy = [
          'invoice_number' => 'invoice_number',
          'account' => 'account_name',
          'invoice_date' => 'invoice_date',
      ];

      $orderEntity = 'invoice_master.id';
      $orderAction = 'desc';

      if (isset($search['sort_action']) && $search['sort_action'] != "") {
          $orderAction = ($search['sort_action'] == 1) ? 'desc' : 'asc';
      }

      if (isset($search['sort_entity']) && $search['sort_entity'] != "") {
          $orderEntity = (array_key_exists($search['sort_entity'], $sortBy)) ? $sortBy[$search['sort_entity']] : $orderEntity;
      }

      $filter = $this->getFilters($search);
      return $this->financialyear()->company()
          ->leftJoin('account_master', 'invoice_master.account_id', ' = ', 'account_master.id')
          ->whereRaw($filter)
          ->orderBy($orderEntity, $orderAction)
          ->skip($skip)->take($take)->get($fields);
    }

    /**
     * Method is used to get total results.
     * @param array $search
     * @return mixed
     */
    public function totalInvoices($search = null)
    {
        trimInputs();
        $filter = $this->getFilters($search);
        return $this->financialyear()->company()
            ->leftJoin('account_master', 'invoice_master.account_id', ' = ', 'account_master.id')
            ->select(\DB::raw('count(*) as total'))
            ->whereRaw($filter)
            ->get()->first();
    }

    /**
     * Method is used to get sale invoice filters.
     * @param array $search
     * @return mixed
     */
    public function getFilters($search = [])
    {
        $filter = 1;
        if (is_array($search) && count($search) > 0)
        {
            $keyword = (array_key_exists('keyword', $search) && $search['keyword'] != "") ?
                " AND (invoice_number LIKE '%" .addslashes(trim($search['keyword'])) . "%'" .
                " OR account_name LIKE '%" .addslashes(trim($search['keyword'])) . "%')"
                : "";

          $f1 = (array_key_exists('financial_year', $search) && $search['financial_year'] != "") ? " and financial_year_id = " .
              addslashes(trim($search['financial_year'])) : "";

          $f2 = (array_key_exists('account', $search) && $search['account'] != "") ? " AND invoice_master.account_id = '" .
                addslashes(trim($search['account'])) . "' " : "";

          if (array_key_exists('from_date', $search) && $search['from_date'] != ""  && $search['to_date'] == "") {
              $date = $search['from_date'] . ' 00:00:00';
              $filter .= " and " . \DB::raw('DATE_FORMAT(invoice_date, "%Y-%m-%d")') . " = '" . convertToLocal($date, 'Y-m-d') . "' ";
          }

          if (array_key_exists('from_date', $search) && $search['from_date'] != "" &&
              array_key_exists('to_date', $search) && $search['to_date'] != ""
          )
          {
              $fromDate = $search['from_date'] . ' 00:00:00';
              $toDate = $search['to_date'] . ' 00:00:00';
              $filter .= " and " . \DB::raw('DATE_FORMAT(invoice_date, "%Y-%m-%d")') . " between '" . convertToLocal($fromDate, 'Y-m-d') . "' and
              '" . convertToLocal($toDate, 'Y-m-d') . "'";
          }

            if (array_key_exists('invoice_date', $search) && $search['invoice_date'] != "") {
                $date = $search['invoice_date'] . ' 00:00:00';
                $filter .= " and " . \DB::raw('DATE_FORMAT(invoice_date, "%Y-%m-%d")') . " = '" . convertToLocal($date, 'Y-m-d') . "' ";

            }

          /* filter sale Invoice as per ProductID */
          if(array_key_exists('product', $search) && $search['product'] != "") {
            $filter.=" and " . \DB::raw('invoice_master_items.product_id') . " = " . $search['product'];
          }
          $filter.= $keyword . $f1 . $f2;
        }


        return $filter;
    }

    /**
     * @return mixed
     */
    public function getInvoicesService()
    {
        $result = $this->active()->company()->lists('od_number', 'id')->toArray();
        return ['' => '-Select Orders No-'] + $result;
    }

    /**
     * @param array $inputs
     * @param null $start
     * @param null $end
     * @return array
     */
    public function getTotalAmount($inputs = [], $start = null, $end = null)
    {
        $result = [];
        $filter = 1; // default filter if no search
        //$f1 = " and " .\DB::raw('DATE_FORMAT(invoice_date, "%m")') . "= " . paddingLeft($month);
        //$f2 = " and " .\DB::raw('DATE_FORMAT(invoice_date, "%Y")') . "= " . paddingLeft($year);
        $f3 = " and invoice_date between '" . $start . "' and '" . $end . "'";
        $filter .= $f3;

        $fields = [
            \DB::raw('sum(net_amount) as total'),
        ];

        $result = $this->whereRaw($filter)->financialyear()->company()->first($fields);
        return $result;
    }

    /**
     * @param array $inputs
     * @param null $start
     * @param null $end
     * @return array
     */
    public function getAllInvoice($inputs = [] , $start = null, $end = null)
    {
        $filter = 1; // default filter if no search
        //$f1 = " and " .\DB::raw('DATE_FORMAT(invoice_date, "%m")') . "= " . paddingLeft($month);
        //$f2 = " and " .\DB::raw('DATE_FORMAT(invoice_date, "%Y")') . "= " . paddingLeft($year);
        $f3 = " and invoice_date between '" . $start . "' and '" . $end . "'";
        $filter .= $f3;

        $result = $this->whereRaw($filter)
            ->financialyear()->company()->first([\DB::raw('count(*) as total_invoices')]);
        return $result;
    }

    /**
     * @param $id
     * @return int
     */
    public function drop($id)
    {
        return $this->destroy($id);
    }

    /**
     * @param null $id
     * @param $inputs
     * @return null
     */
    public function updateEmailStatus($id = null, $inputs)
    {
        if ($id) {
            $this->find($id)->update($inputs);
            return $id;
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getInvoiceDetail($id)
    {
        $fields = [
            'invoice_master.*',
            'account_master.account_name as account',
            'ledger_account.account_name as ledger'
        ];

        return $this->leftJoin('account_master', 'invoice_master.account_id', ' = ', 'account_master.id')
            ->leftJoin('account_master as ledger_account', 'invoice_master.ledger_id', ' = ', 'ledger_account.id')
              ->where('invoice_master.id', $id)
                ->company()->first($fields);
    }

    /**
     * @param $invoiceId
     * @param $amount
     * @return mixed
     */
    public function updateAmount($invoiceId, $amount) 
    {        
        $input = [
            'gross_amount' => $amount ,
            'net_amount' => $amount
        ];
        return $this->find($invoiceId)->update($input);
    }

    /**
     * @param $id
     * @param $result
     * @param $account
     * @param bool $isApi
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function sendEmailToCustomer($id, $result, $account, $isApi = false)
    {
        try {
            \DB::beginTransaction();
            $inputs['is_email_sent'] = 1;
            (new Invoice)->updateEmailStatus($id, $inputs);
            $items = (new Invoice)->getInvoiceItemDetail($id, $result);
            $orderItems = $items['orderItem'];
            $taxes = $items['taxes'];
            try {
                $companyId = loggedInCompanyId();
                $company = (new Company)->getCompanyDetails($companyId);
                $setting = getCompanySetting();
                $bank = Bank::find($result->bank_id);
                $printOptions = explode(',', $setting->print_options);
                $pdf = \PDF::loadView('invoice.invoice_print', [
                    'id' => $id, 'party' => $account, 'result' => $result,
                    'orderItems' => $orderItems, 'company' => $company, 
                    'bank' => $bank, 'pdf' => 1, 'setting' => $setting,
                    'printOptions' => $printOptions, 'taxes' => $taxes,
                ]);
                Mail::send('emails.sale-invoice-to-customer', ['customer' => $account], function ($m) use ($account, $result, $pdf, $company) {
                    $m->from(\Config::get('constants.EMAIL'), lang('emails.sir_ltd'));
                    $m->to($account->email1, $account->account_name)->subject(lang('emails.invoice_hash') . $result->invoice_number . lang('emails.dated') . convertToLocal($result->invoice_date, 'd.m.Y') . lang('emails.generated_by') . $company->company_name);
                    $m->attachData($pdf->output(), lang('emails.invoice_number') . paddingLeft($result->invoice_number).'.pdf');
                });
            } catch (\Exception $e) {
                //return apiResponse(false, 404, lang('messages.server_error'));
            }
            \DB::commit();
            if (!$isApi) {
              return redirect()->back()->with('success', lang('messages.email_send', lang('invoice.invoice')));
            } else {
              return apiResponse(true, 200, lang('messages.email_send', lang('invoice_master.invoice_master')));
            }
        } catch (\Exception $exception) {
            \DB::rollBack();
            if (!$isApi) {
              return redirect()->back()->with('error', lang('messages.server_error'));
            } else {
              return apiResponse(false, 404, lang('messages.server_error'));
            }
        }
    }

    /**
     * Method is used for print and pdf and email invoice.
     *
     * @param $id
     * @param $result
     * @return array
     */
    public function getInvoiceItemDetail($id, $result)
    {
        $orderItems = (new InvoiceItems)->getInvoiceItems(['invoice_id' => $id]);
        $taxes = ['cgst' => [], 'sgst' => [], 'igst' => []];
        foreach ($orderItems as $key => $values) {
            if($result->sale == 1) {
                if (!in_array("'" . $values->cgst . "'", array_keys($taxes['cgst']))) {
                    $taxes['cgst'] = $taxes['cgst'] + ["'" . $values->cgst . "'" => $values->cgst_amount];
                } else {
                    $taxes['cgst']["'" . $values->cgst . "'"] = $taxes['cgst']["'" . $values->cgst . "'"] + $values->cgst_amount;
                }

                if (!in_array("'" . $values->sgst . "'", array_keys($taxes['sgst']))) {
                    $taxes['sgst'] = $taxes['sgst'] + ["'" . $values->sgst . "'" => $values->sgst_amount];
                } else {
                    $taxes['sgst']["'" . $values->sgst . "'"] = $taxes['sgst']["'" . $values->sgst . "'"] + $values->sgst_amount;
                }
            } elseif($result->sale == 2) {

                if (!in_array("'" . $values->igst . "'", array_keys($taxes['igst']))) {
                    $taxes['igst'] = $taxes['igst'] + ["'" . $values->igst . "'" => $values->igst_amount];
                } else {
                    $taxes['igst']["'" . $values->igst . "'"] = $taxes['igst']["'" . $values->igst . "'"] + $values->igst_amount;
                }
            }
        }

        return ['orderItem' => $orderItems, 'taxes' => $taxes];
    }

    /**
     * @return array
     */
    public function getMonthWiseInvoice()
    {
        $previousStartDate = date('Y-m-01', strtotime('-1 year'));
        $previousEndDate = date('Y-06-30', strtotime('-1 year'));
        $result = $this->select(\DB::raw("
                            MONTH(invoice_date) as month,
                            sum(gross_amount) as total_sale,
                            count(*) as total_invoice"
                ))
            ->where("financial_year_id", financialYearId())
            ->company()
            ->groupBy(\DB::raw("MONTH(invoice_date)"))
            ->get();

        $result2 = $this->select(\DB::raw("
                                MONTH(invoice_date) as month,
                                sum(gross_amount) as total_sale,
                                count(*) as total_invoice"
            ))
            //->where("financial_year_id", financialYearId())
            ->whereRaw('invoice_date between "' . $previousStartDate . '" and "' . $previousEndDate . '"')
            ->company()
            ->groupBy(\DB::raw("MONTH(invoice_date)"))
            ->get();
        //dd($result2->toArray());
        $prepareData = [];
        /*$emptyData = [
            'total_sale' => 0,
            'total_sale_amount' => 0,
        ];*/
        if (count($result) > 0) {
            $saleAmount = array_column($result->toArray(), 'total_sale', 'month');
            $totalSale = array_column($result->toArray(), 'total_invoice', 'month');
            $saleAmountP = array_column($result2->toArray(), 'total_sale', 'month');
            $totalSaleP = array_column($result2->toArray(), 'total_invoice', 'month');
            $months = getMonthDefaultValue();

            foreach($months as $number => $monthName) {
                if (array_key_exists($number, $saleAmountP)) {
                    $prepareData[] = [
                        'month' => '(' . $monthName . ')',
                        'total_sale' => $totalSaleP[$number],
                        'total_sale_amount' => ($saleAmountP[$number]),
                    ];
                }
            }
            foreach($months as $number => $monthName) {
                if (array_key_exists($number, $saleAmount)) {
                    $prepareData[] = [
                        'month' => $monthName,
                        'total_sale' => $totalSale[$number],
                        'total_sale_amount' => ( $saleAmount[$number]),
                    ];
                }
            }
        }
        //dd($prepareData);
        return $prepareData;
    }

    /**
     * Method is used to get line chart on dashboard
     * @return array|string
     * @internal param bool $isJson
     */
    public function getFinancialYearWiseInvoice()
    {
        $result = $this->select(\DB::raw("sum(gross_amount) as total_sale, financial_year.name as financial_year"))
                    ->leftJoin('financial_year', 'financial_year.id', '=', 'invoice_master.financial_year_id')
                    ->company()
                    ->groupBy("financial_year_id")
                    ->orderBy("financial_year_id", "DESC")
                    ->take(3)
                    ->get();

        $prepareData = [];
        if(count($result) > 0) {
            $data = array_column($result->toArray(), 'total_sale', 'financial_year');
            foreach ($data as $key => $value) {
                $prepareData[] = [
                  'year' => $key,
                  'total_sale_amount' => $value
                ];
            }
        }
        return $prepareData;
    }

    /**
     * @param array $search
     * @return null
     */
    public function saleInvoiceReport($search = [])
    {
        $fields = [
            'invoice_master.id',
            'account_master.account_name',
            'invoice_number',
            'invoice_date',
            'gross_amount',
            'net_amount',
        ];
        $filter = 1;

        if(array_key_exists('form-search', $search)) {
            if (is_array($search) && count($search) > 0) {

                $f1 = (array_key_exists('product', $search) && $search['product'] != "") ? " AND (product.id = " .
                    addslashes(trim($search['product'])) . ")" : "";

                $f2 = (array_key_exists('account', $search) && $search['account'] != "") ? " AND (account_master.id = " .
                    addslashes(trim($search['account'])) . ")" : "";

                if (array_key_exists('from_date', $search) && $search['from_date'] != "" && $search['to_date'] == "" && $search['report_type'] == '1') {
                    $filter .= " and " . \DB::raw('DATE_FORMAT(invoice_date, "%Y-%m-%d")') . " = '" . convertToLocal($search['from_date'], 'Y-m-d') . "'";
                }

                if (array_key_exists('from_date', $search) && $search['from_date'] != "" &&
                    array_key_exists('to_date', $search) && $search['to_date'] != "" && $search['report_type'] == '1'
                )
                {
                    $filter .= " and " . \DB::raw('DATE_FORMAT(invoice_date, "%Y-%m-%d")') . " between '" . convertToLocal($search['from_date'], 'Y-m-d') . "' and
                    '" . convertToLocal($search['to_date'], 'Y-m-d') . "'";
                }

                if (array_key_exists('month', $search) && $search['month'] != "" && $search['report_type'] == '2') {
                    $filter .= " and " . \DB::raw('DATE_FORMAT(invoice_date, "%m")') . " = '" . paddingLeft($search['month']) . "' and financial_year_id = '" . financialYearId() . "'";
                }

                if (array_key_exists('year', $search) && $search['year'] != "" && $search['report_type'] == '3') {
                    $filter .= " and " . \DB::raw('DATE_FORMAT(invoice_date, "%Y")') . " = '" . $search['year'] . "' ";
                }

                $filter .= $f1 . $f2;
                return $this->leftJoin('account_master', 'account_master.id', '=', 'invoice_master.account_id')
                    ->whereRaw($filter)
                    ->company()
                    ->get($fields);
            }
        }
        return null;
    }
    /**
     * @param $id
     * @return invoice number
     */
    public function getInvoiceNumber($id)
    {
        return $this->where('invoice_master.id', $id)
            ->company()
            ->first(['invoice_master.invoice_number']);
    }

}
