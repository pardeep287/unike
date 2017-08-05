<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'order_master';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [

        'user_id',
        'cart_id',
        'company_id',
        'financial_year_id',
        'order_number',
        'order_date',
        'gross_amount',
        'net_amount',
        'round_off',
        'status',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
    ];

    /**
     * Scope a query to only include active users.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('order_master.status', 1);
    }

    /**
     * Scope a query to only include active users.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFinancialYear($query)
    {
        return $query->where('order_master.financial_year_id', financialYearId());
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeCompany($query)
    {
        return $query->where('order_master.company_id', loggedInCompanyId());
    }

    public function  validate( $inputs, $id = null)
    {

        if($id)
        {
            // $rules['name'] = 'required|unique:brand,name,' . $id .',id,deleted_at,NULL,company_id,'.loggedInCompanyId();
            $rules['user_id'] = 'required';
           // $rules['cart_id'] = 'required';
        }
        else {
            // $rules['name'] = 'required|unique:brand,name,NULL,id,deleted_at,NULL,company_id,'.loggedInCompanyId();
            $rules['user_id'] = 'required';
            //$rules['cart_id'] = 'required';

        }

        return \Validator::make($inputs, $rules);
    }


    /**
     * @return string
     */
    public function getOrderNumber()
    {
        $result  = $this->orderBy('order_number', 'desc')
            ->first(['order_number']);
        //dd($result);
        if (!$result) {
            //$number = 'UN-01';
            $number = '01';
        } else {
            //dd($result);
            $number = paddingLeft(++$result->order_number);
        }
        //dd($number);
        return $number;
    }
    /**
     * @param $inputs
     * @param null $id
     * @return mixed
     */
    public function store($inputs, $id = null)
    {
        if ($id) {
             return $this->find($id)->update($inputs);
        }
        else {
            return $this->create($inputs)->id;
        }
    }

    public function getInvoices($search = null, $skip, $perPage)
    {
        trimInputs();
        $take = ((int)$perPage > 0) ? $perPage : 20;
        $fields = [

            'order_master.id',
            'customers.customer_name as customer_name',
            'order_number',
            'order_date',
            'gross_amount',
            //'net_amount',
            //'round_off',
            'order_master.status',
            //'remarks',

            //'invoice_master.is_email_sent'
        ];

        $sortBy = [
            'order_number' => 'order_number',
            'customer_name' => 'customer_name',
            'order_date' => 'order_date',
        ];

        $orderEntity = 'order_master.id';
        $orderAction = 'desc';

        if (isset($search['sort_action']) && $search['sort_action'] != "") {
            $orderAction = ($search['sort_action'] == 1) ? 'desc' : 'asc';
        }

        if (isset($search['sort_entity']) && $search['sort_entity'] != "") {
            $orderEntity = (array_key_exists($search['sort_entity'], $sortBy)) ? $sortBy[$search['sort_entity']] : $orderEntity;
        }

        $filter = $this->getFilters($search);
        return $this->financialyear()->company()
            ->leftJoin('customers', 'order_master.user_id', '=', 'customers.user_id')
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
            ->leftJoin('customers', 'order_master.user_id', '=', 'customers.user_id')
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
     * @param $id
     * @return mixed
     */
    public function getInvoiceDetail($id)
    {
        $fields = [
            'order_master.*',
            'customers.customer_name as customer_name',
        ];

        return $this
            ->leftJoin('customers', 'order_master.user_id', '=', 'customers.user_id')
            //->leftJoin('account_master as ledger_account', 'invoice_master.ledger_id', ' = ', 'ledger_account.id')
            ->where('order_master.id', $id)
            ->company()->first($fields);
    }
}
