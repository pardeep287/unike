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
        'user_buyer_id',
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
     * @param $user_id
     * @return Model|null|static
     */
    public function findByUserId($user_id = null, $skip, $perPage)
    {
        trimInputs();
        $take = ((int)$perPage > 0) ? $perPage : 20;
        $fields = [
            'order_master.id',
            'order_master.user_id',
            'mrCustomer.customer_name as mr_name',
            'user_buyer_id',
            'customers.customer_name',
            'cart_id',
            'order_number',
            'order_date',
            'gross_amount',
            'order_master.status',

        ];

        return $this
            ->leftJoin('customers', 'order_master.user_buyer_id', '=', 'customers.user_id')
            ->leftJoin('customers as mrCustomer', 'order_master.user_id', '=', 'mrCustomer.user_id')
            ->where('order_master.user_id', $user_id)
            ->where('order_master.status', 1)
            ->skip($skip)->take($take)
            ->get($fields);


    }
    /**
     * @param $user_id
     * @return Model|null|static
     */
    public function findByUserIdNew($user_id = null, $skip, $perPage)
    {
        trimInputs();
        $take = ((int)$perPage > 0) ? $perPage : 20;
        $fields = [
            'order_master.id',
            'order_master.user_id as mr_id',
            'users.username as mr_name',
            'user_buyer_id as customer_id',
            'customers.customer_name as customer_name',
            'cart_id',
            'order_number',
            'order_date',
            'gross_amount',
            'order_master.status',

        ];

        return $this
            ->leftJoin('customers', 'order_master.user_buyer_id', '=', 'customers.user_id')
            ->leftJoin('users', 'order_master.user_id', '=', 'users.id')
            ->where('order_master.user_id', $user_id)
            ->where('order_master.status', 1)
            ->skip($skip)->take($take)
            ->get($fields);


    }

    /**
     * @param $user_id
     * @return Model|null|static
     */
    public function findByOrderId($order_id )
    {

        $fields = [
            'order_master.id',
            'order_master.user_id',
            'mrCustomer.customer_name as mr_name',
            'user_buyer_id',
            'customers.customer_name',
            'cart_id',
            'order_number',
            'order_date',
            'gross_amount',
            'order_master.status',

        ];

        return $this
            ->leftJoin('customers', 'order_master.user_buyer_id', '=', 'customers.user_id')
            ->leftJoin('customers as mrCustomer', 'order_master.user_id', '=', 'mrCustomer.user_id')
            ->where('order_master.id', $order_id)
            ->where('order_master.status', 1)
            ->first($fields);


    }

    /**
     * @param $user_id
     * @return Model|null|static
     */
    public function findByOrderIdNew($order_id )
    {

        $fields = [
            'order_master.id',

            'order_master.user_id as mr_id',
            'users.username as mr_name',
            'user_buyer_id as customer_id',
            'customers.customer_name as customer_name',

            /*'order_master.user_id',
            'mrCustomer.customer_name as mr_name',
            'user_buyer_id',
            'customers.customer_name',*/

            'cart_id',
            'order_number',
            'order_date',
            'gross_amount',
            'order_master.status',

        ];

        return $this
            //->leftJoin('customers', 'order_master.user_buyer_id', '=', 'customers.user_id')
            //->leftJoin('customers as mrCustomer', 'order_master.user_id', '=', 'mrCustomer.user_id')
            ->leftJoin('customers', 'order_master.user_buyer_id', '=', 'customers.user_id')
            ->leftJoin('users', 'order_master.user_id', '=', 'users.id')
            ->where('order_master.id', $order_id)
            ->where('order_master.status', 1)
            ->first($fields);


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

    public function getOrders($search = null, $skip, $perPage)
    {
        // dd($search, $skip, $perPage);
        trimInputs();
        $take = ((int)$perPage > 0) ? $perPage : 20;
        $fields = [

            'order_master.id',
            'order_master.user_id',
            'customers.customer_name as customer_name',
            'order_number',
            'order_date',
            'gross_amount',
            //'net_amount',
            //'round_off',
            'order_master.status',
            //'remarks',
            'user_buyer_id',


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
      //dd($filter,$skip,$take,$orderEntity, $orderAction);
        return $this->financialyear()->company()
            ->leftJoin('customers', 'order_master.user_id', '=', 'customers.user_id')
            ->whereRaw($filter)
            ->orderBy($orderEntity, $orderAction)
            ->skip($skip)->take($take)->get($fields);
    }

    public function getOrdersNew($search = null, $skip, $perPage)
    {
        // dd($search, $skip, $perPage);
        trimInputs();
        $take = ((int)$perPage > 0) ? $perPage : 20;
        $fields = [

            'order_master.id',
            'order_master.user_id as mr_id',
            'users.username as mr_name',
            'user_buyer_id as customer_id',
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
        //dd($filter,$skip,$take,$orderEntity, $orderAction);
        return $this->financialyear()->company()
            ->leftJoin('users', 'order_master.user_id', '=', 'users.id')
            ->leftJoin('customers', 'order_master.user_buyer_id', '=', 'customers.user_id')
            ->whereRaw($filter)
            ->orderBy($orderEntity, $orderAction)
            ->skip($skip)->take($take)->get($fields);
    }

    public function getOrdersByFilter($search = null, $skip, $perPage)
    {
        // dd($search, $skip, $perPage);
        trimInputs();
        $take = ((int)$perPage > 0) ? $perPage : 20;
        $fields = [

            'order_master.id',
            'order_master.user_id',
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
        //dd($filter,$skip,$take,$orderEntity, $orderAction);
        return $this->financialyear()->company()
            //->rigthJoin('customers', 'order_master.user_buyer_id', '=', 'customers.user_id')
            ->leftJoin('customers as mrCustomer', 'order_master.user_id', '=', 'mrCustomer.user_id')
            //->leftJoin('customers', 'order_master.user_id', '=', 'customers.user_id')
            ->whereRaw($filter)
            ->orderBy($orderEntity, $orderAction)
            ->skip($skip)->take($take)->get($fields);
    }

    /**
     * Method is used to get total results.
     * @param array $search
     * @return mixed
     */
    public function totalOrders($search = null)
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
        //dump($search);
        $filter = 1;
        if (is_array($search) && count($search) > 0)
        {
            $keyword = (array_key_exists('keyword', $search) && $search['keyword'] != "") ?
                " AND (order_number LIKE '%" .addslashes(trim($search['keyword'])) . "%'" .
                " OR customer_name LIKE '%" .addslashes(trim($search['keyword'])) . "%'" .
                " OR order_date LIKE '%" .addslashes(trim($search['keyword'])) . "%')"
                : "";

            $f1 = (array_key_exists('financial_year', $search) && $search['financial_year'] != "") ? " and financial_year_id = " .
                addslashes(trim($search['financial_year'])) : "";

            $f2 = (array_key_exists('customer_id', $search) && $search['customer_id'] != "") ? " AND order_master.user_id = '" .
                addslashes(trim($search['customer_id'])) . "' " : "";

            $f4 = (array_key_exists('mr_id', $search) && $search['mr_id'] != "") ? " AND order_master.user_buyer_id = '" .
                addslashes(trim($search['mr_id'])) . "' " : "";

            $f3 = (array_key_exists('order_date', $search) && $search['order_date'] != "") ? " AND order_master.order_date = '" .
                addslashes(trim($search['order_date'])) . "' " : "";

            if (array_key_exists('from_date', $search) && $search['from_date'] != ""  && $search['to_date'] == "") {
                $date = $search['from_date'] . ' 00:00:00';
                $filter .= " and " . \DB::raw('DATE_FORMAT(order_date, "%Y-%m-%d")') . " = '" . convertToUtc($date, 'Y-m-d') . "' ";
            }

            if (array_key_exists('from_date', $search) && $search['from_date'] != "" &&
                array_key_exists('to_date', $search) && $search['to_date'] != ""
            )
            {
                $fromDate = $search['from_date'] . ' 00:00:00';
                $toDate = $search['to_date'] . ' 23:59:59';
                $filter .= " and " . \DB::raw('DATE_FORMAT(order_date, "%Y-%m-%d")') . " between '" . convertToUtc($fromDate, 'Y-m-d') . "' and '" . convertToUtc($toDate, 'Y-m-d') . "'";
            }

            if (array_key_exists('month', $search) && $search['month'] != "" && $search['report_type'] == '2') {
                $filter .= " and " . \DB::raw('DATE_FORMAT(order_date, "%m")') . " = '" . paddingLeft($search['month']) . "' and financial_year_id = '" . financialYearId() . "'";
            }

            if (array_key_exists('year', $search) && $search['year'] != "" && $search['report_type'] == '3') {
                $filter .= " and " . \DB::raw('DATE_FORMAT(order_date, "%Y")') . " = '" . $search['year'] . "' ";
            }

            if (array_key_exists('order_date', $search) && $search['order_date'] != "") {
                $date = $search['order_date'] . ' 00:00:00';
                $filter .= " and " . \DB::raw('DATE_FORMAT(order_date, "%Y-%m-%d")') . " = '" . convertToUtc($date, 'Y-m-d') . "' ";

            }

            /* filter sale Invoice as per ProductID */
            if(array_key_exists('product', $search) && $search['product'] != "") {
                $filter.=" and " . \DB::raw('invoice_master_items.product_id') . " = " . $search['product'];
            }
            $filter.= $keyword . $f1 . $f2 . $f3 . $f4;
        }


        return $filter;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getOrderDetail($id)
    {
        $fields = [
            'order_master.*',
            'users.username as mr_name',
            'customers.customer_name as customer_name',
            //'customers.customer_name as customer_name',
        ];

        return $this
            ->leftJoin('users', 'order_master.user_id', '=', 'users.id')
            ->leftJoin('customers', 'order_master.user_buyer_id', '=', 'customers.user_id')
            //->leftJoin('customers', 'order_master.user_id', '=', 'customers.user_id')
            //->leftJoin('account_master as ledger_account', 'invoice_master.ledger_id', ' = ', 'ledger_account.id')
            ->where('order_master.id', $id)
            ->company()->first($fields);
    }

    /**
     * @param array $search
     * @return null
     */
    public function saleOrderReport($search = [])
    {
        $fields = [
            'order_master.id',
            'customers.customer_name',
            'order_number',
            'order_date',
            'gross_amount',
            //'net_amount',
        ];
        $filter = 1;

        if(array_key_exists('form-search', $search)) {
            if (is_array($search) && count($search) > 0) {

                $f1 = (array_key_exists('product', $search) && $search['product'] != "") ? " AND (product.id = " .
                    addslashes(trim($search['product'])) . ")" : "";

                $f2 = (array_key_exists('customer_name', $search) && $search['customer_name'] != "") ? " AND (customers.id = " .
                    addslashes(trim($search['customer_name'])) . ")" : "";

                if (array_key_exists('from_date', $search) && $search['from_date'] != "" && $search['to_date'] == "" && $search['report_type'] == '1') {
                    $filter .= " and " . \DB::raw('DATE_FORMAT(order_date, "%Y-%m-%d")') . " = '" . convertToLocal($search['from_date'], 'Y-m-d') . "'";
                }

                if (array_key_exists('from_date', $search) && $search['from_date'] != "" &&
                    array_key_exists('to_date', $search) && $search['to_date'] != "" && $search['report_type'] == '1'
                )
                {
                    $filter .= " and " . \DB::raw('DATE_FORMAT(order_date, "%Y-%m-%d")') . " between '" . convertToLocal($search['from_date'], 'Y-m-d') . "' and
                    '" . convertToLocal($search['to_date'], 'Y-m-d') . "'";
                }

                if (array_key_exists('month', $search) && $search['month'] != "" && $search['report_type'] == '2') {
                    $filter .= " and " . \DB::raw('DATE_FORMAT(order_date, "%m")') . " = '" . paddingLeft($search['month']) . "' and financial_year_id = '" . financialYearId() . "'";
                }

                if (array_key_exists('year', $search) && $search['year'] != "" && $search['report_type'] == '3') {
                    $filter .= " and " . \DB::raw('DATE_FORMAT(order_date, "%Y")') . " = '" . $search['year'] . "' ";
                }

                $filter .= $f1 . $f2;
                return $this->leftJoin('customers', 'customers.id', '=', 'order_master.user_id')
                    ->whereRaw($filter)
                    ->company()
                    ->get($fields);
            }
        }
        return null;
    }

    /**
     * @param array $search
     * @return null
     */
    public function monthWiseMrOrderCount($search = [])
    {

        $filter = 1;

            if (is_array($search) && count($search) > 0) {

                if (array_key_exists('month', $search) && $search['month'] != ""    ) {
                    $filter .= " and " . \DB::raw('DATE_FORMAT(order_date, "%m")') . " = '" . paddingLeft($search['month']) . "' and financial_year_id = '" . financialYearId() . "'";
                }

                //dd($filter);
                return $this
                    //->leftJoin('users', 'users.id', '=', 'order_master.user_id')
                    ->whereRaw($filter)
                    ->where('user_buyer_id','!=',null)
                    ->selectRaw('sum(gross_amount) as total_amount,count(gross_amount) as orders_count')
                    ->company()
                    ->first();
                    //->get($fields);
                   // ->count();
            }

        return null;
    }

    /**
     * @param array $search
     * @return null
     */
    public function monthWiseMrOrder($search = [])
    {
        $fields = [
            //'order_master.id',
            //'users.name',
            //'order_number',
            //'order_date',
            //'gross_amount',
            // 'customers.customer_name',
            //'net_amount',
            //\DB::raw('sum(gross_amount) as total_amount'),
            //\DB::raw('count(gross_amount) as count'),
            //\DB::raw('count(gross_amount) as count'),
          // \DB::raw('max(order_master.user_id) as max_user_id'),
          // \DB::raw('customer_name'),
        ];
        $filter = 1;


            if (is_array($search) && count($search) > 0) {

                if (array_key_exists('month', $search) && $search['month'] != ""    ) {
                    $filter .= " and " . \DB::raw('DATE_FORMAT(order_date, "%m")') . " = '" . paddingLeft($search['month']) . "' and financial_year_id = '" . financialYearId() . "'";
                }

                //dd($filter);
                return $this
                     ->selectRaw('sum(gross_amount) as total_amount,count(gross_amount) as count,order_master.user_id, username,customers.customer_name')
                     ->leftJoin('users', 'users.id', '=', 'order_master.user_id')
                     ->leftJoin('customers', 'customers.id', '=', 'order_master.user_buyer_id')
                    ->whereRaw($filter)
                    ->whereNotNull('user_buyer_id')
                    ->groupby('order_master.user_id')
                    //->orderby('order_master.user_id')
                    ->orderBy('order_master.user_id', 'DESC')
                    ->company()
                    ->get($fields);
            }

        return null;
    }


    /**
     * @param array $search
     * @return null
     */
    public function monthWiseLatestOrder($search = [],$take=null)
    {
        $fields = [
            //'order_master.id',
            //'customers.customer_name',
            'users.username as mr_name',
            'customers.customer_name as customer_name',
            'order_number',
            'order_date',
            'gross_amount',
        ];
        $filter = 1;


        if (is_array($search) && count($search) > 0) {

            if (array_key_exists('month', $search) && $search['month'] != ""    ) {
                $filter .= " and " . \DB::raw('DATE_FORMAT(order_date, "%m")') . " = '" . paddingLeft($search['month']) . "' and financial_year_id = '" . financialYearId() . "'";
            }

            //dd($filter);
            return $this

                ->leftJoin('users', 'users.id', '=', 'order_master.user_id')
                ->leftJoin('customers', 'customers.user_id', '=', 'order_master.user_buyer_id')
                ->whereRaw($filter)
                ->orderBy('order_master.order_date', 'DESC')
                ->company()
                ->take($take)
                ->get($fields);
        }

        return null;
    }
}
