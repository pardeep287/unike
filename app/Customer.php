<?php

namespace App;
/**
 * :: Company Model ::
 * To manage Company CRUD operations
 *
 **/
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Company
 * @package App
 * @author Inderjit Singh
 */
class Customer extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'customers';

    protected $fillable = [
        'company_id',
        'discount',
        'salutation',
        'customer_name',
        'customer_code',
        'mobile_no',
        'alternate_mobile_no',
        'landline_no',
        'email',
        'pan_number',
        'gst_number',
        'address',
        'alternate_address',
        'country',
        'state_id',
        'city',
        'pin_code',
        'user_id',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeUserId($query) {
        if(!isAdmin()) {
            return $query->where('customers.user_id', authUserId());
        }
    }

    public function scopeCompany($query)
    {
        return $query->where('company_id', loggedInCompanyId());
    }
    /**
     * @param $inputs
     * @return mixed
     */

    public function validate( $inputs, $id = null)
    {
        $inputs = array_filter($inputs);
        if($id) {
            $rules['email'] = 'email';
        } else {
            $rules['email'] = 'email|unique:customers,email';
        }

        $rules = $rules +  [
            'customer_name'  => 'required',
            'contact_person' => 'required',
            'mobile_no'      => 'digits_between:10,12',
            'landline_no'    => 'digits_between:7,12'
        ];
        return \Validator::make($inputs, $rules);
    }

    /**
     * @param $inputs
     * @return mixed
     */

    public function validateCAddress( $inputs, $id = null)
    {
        $inputs = array_filter($inputs);
        if($id) {
            //$rules['email'] = 'email';
        } else {
            $rules['user_id'] = 'required|numeric|min:1';
            $rules['address'] = 'required';
            $rules['country'] = 'required';
            $rules['state_id'] = 'required|numeric|min:1';
            $rules['city'] = 'required';
            $rules['pin_code'] = 'required|numeric|min:1';
            $rules['gst_number'] = 'numeric|min:1';
        }


        return \Validator::make($inputs, $rules);
    }
    /*
     * required:username,password(min:5),email,mobile,phone
     */
    public function  validateCustomer( $inputs, $id = null,$tab=null,$isApi=null )
    {

        if($id ){
            if($isApi==1) {
                $rules['customer_name'] = 'required';
                $rules['gst_number'] = 'nullable|alpha_dash|min:15|max:15|unique:customers,gst_number,' . $id . ',id,deleted_at,NULL';
                $rules['email'] = 'required|email|unique:customers,email,' . $id . ',id,deleted_at,NULL';
                $rules['customer_code'] = 'unique:customers,customer_code,' . $id . ',id,deleted_at,NULL';
                $rules['mobile_no'] = 'required|numeric|unique:customers,mobile_no,' . $id . ',id,deleted_at,NULL';
                $rules['address'] = 'required';
                $rules['country'] = 'required';
                $rules['state_id'] = 'required';
                $rules['city'] = 'required';
                $rules['pin_code'] = 'required|numeric';
            }

            if(isset($inputs['tab']) && $inputs['tab']==1){
                $rules['customer_name'] = 'required';
                $rules['email'] = 'required|email|unique:customers,email,' . $id . ',id,deleted_at,NULL';
                $rules['customer_code'] = 'unique:customers,customer_code,' . $id . ',id,deleted_at,NULL';
                $rules['mobile_no'] = 'required|numeric|unique:customers,mobile_no,' . $id . ',id,deleted_at,NULL';
                $rules['gst_number'] = 'nullable|alpha_dash|min:15|max:15|unique:customers,gst_number,' . $id . ',id,deleted_at,NULL';
                $rules['discount'] = 'nullable|numeric|max:2,' . $id . ',id,deleted_at,NULL';
            }
            if(isset($inputs['tab']) && $inputs['tab']==2){

                $rules['address'] = 'required';
                $rules['country'] = 'required';
                $rules['state_id'] = 'required';
                $rules['city'] = 'required';
                $rules['pin_code'] = 'required|numeric';
            }
        }
        else{
            if($isApi==1) {
                $rules['username'] = 'required|unique:users,username';
                $rules['gst_number'] = 'nullable|alpha_dash|min:15|max:15|unique:customers,gst_number';
                $rules['password'] = 'required|min:5';
                $rules['email'] = 'required|unique:customers,email';
                $rules['mobile_no'] = 'required|numeric|digits:10|unique:customers,mobile_no';
                $rules['address'] = 'required';
                $rules['country'] = 'required';
                $rules['state_id'] = 'required|numeric';
                $rules['city'] = 'required';
                $rules['pin_code'] = 'required|numeric';
            }
            if($tab==1){
                $rules['customer_name'] = 'required';
                $rules['username'] = 'required|unique:users,username';
                $rules['password'] = 'required|min:5';
                $rules['email']    = 'required|unique:customers,email';
                $rules['mobile_no']   = 'required|numeric|digits:10|unique:customers,mobile_no';
            }
            if($tab==2){
                $rules['address'] = 'required';
                $rules['country'] = 'required';
                $rules['state_id'] = 'required|numeric';
                $rules['city'] = 'required';
                $rules['pin_code'] = 'required|numeric';
            }
        }
        return \Validator::make($inputs, $rules);
    }

    /**
     * @param $inputs
     * @param null $id
     * @return mixed
     */
    public function store($inputs, $id = null)
    {
        //dd($inputs, $id);
        if ($id) {
            return $this->find($id)->update($inputs);
        } else {
            return $this->create($inputs)->id;
        }
    }

    /**
     * @param array $search
     * @param bool $id
     * @return mixed
     */
    public function getCustomer($search = [], $id = null )
    {
         if ($id) {
            $result = $this->find($id);
         } else {
             $filter = 1;
             if( count($search) > 0 && is_array( $search ) ) {
                 $filter.= (array_key_exists('keyword', $search) && $search['keyword'] != "") ?
                     " AND customer_name LIKE '" . addslashes(trim($search['keyword'])) . "%'":"";
             }
             $result = $this
                        ->whereRaw($filter)
                        ->UserId()
                        ->get();
         }
         return $result;
    }



    /**
     * @param $product_id
     * @return mixed
     */

    public function getCustomerByUserId($userId)
    {
        $fields = [
            'id',
            'discount',
            /*'name',
            'p_image',
            'tax_id',*/
        ];
        return $this
            ->active()
            ->where('customers.user_id', $userId)
            ->first($fields);
        //->first();
    }

    /**
     * @param $id
     * @return int
     */
    public function deleteCustomer($id)
    {
        return $this->destroy($id);
    }

    /**
     * @return string
     */
    public function getCustomerCode($code = null)
    {
        $data =  $this->orderBy('id', 'desc')->take(1)->first(['customer_code']);

        if (count($data) == 0) {
            $number = 'C/01';
        } else {
            $number = orderNumberInc($data->customer_code); // new purchase order number increment by 1
        }
        return $number;
    }

    /**
     * Method is used to search total results.
     *
     * @param array $search
     * @param int $skip
     * @param int $perPage
     *
     * @return mixed
     */
    public function getCustomers($search = null, $skip, $perPage)
    {
        trimInputs();
        $take = ((int)$perPage > 0) ? $perPage : 20;
        $filter = 1; // default filter if no search

        $fields = [
            'id',
            'customer_name',
            'customer_code',
            'user_id',
           // 'contact_person',
            'email',
            'mobile_no',
            'address',
            'status',
        ];

        if (is_array($search) && count($search) > 0) {
            $keyword = (array_key_exists('keyword', $search)) ? " AND (customer_name LIKE '%" .
                addslashes(trim($search['keyword'])) . "%' Or customer_code LIKE '%" .
                addslashes(trim($search['keyword'])) . "%')" : "";
            $filter .= $keyword;
        }
        return $this->whereRaw($filter)
            ->orderBy('id', 'ASC')
            ->skip($skip)->take($take)->get($fields);
    }

    /**
     * Method is used to get total results.
     *
     * @param array $search
     *
     * @return mixed
     */
    public function totalCustomers($search = null)
    {
        trimInputs();
        $filter = 1; // default filter if no search

        if (is_array($search) && count($search) > 0) {
            $keyword = (array_key_exists('keyword', $search)) ? " AND (customer_name LIKE '%" .
                addslashes(trim($search['keyword'])) . "%' Or customer_code LIKE '%" .
                addslashes(trim($search['keyword'])) . "%')" : "";
            $filter .= $keyword;
        }
        return $this->select(\DB::raw('count(*) as total'))->whereRaw($filter)->get()->first();
    }

    public function findByUserId($user_id)
    {
        $fields = [
            'id',
            'customer_name',
            'customer_code',
            'user_id',
            // 'contact_person',
            'email',
            'mobile_no',
            'address',
            'status',

        ];

        return $this
            ->where('customers.user_id', $user_id)
            //->get($fields);
            ->first($fields);


    }
    /**
     * @param array $search
     * @return array
     */
    public function getCustomerService($search = [])
    {
        $filter = 1; // if no search add where
        // when search
        if (is_array($search) && count($search) > 0) {
            $f1 = (array_key_exists('t', $search) && $search['t'] != "") ? " AND (customer_name LIKE '%" .
                addslashes(trim($search['t'])) . "%' OR customer_code LIKE '%" .
                addslashes(trim($search['t'])) . "%')" : "";
            $filter .= $f1;
        }
        $data = $this->active()
            ->whereRaw($filter)
            //->where('role_id',2)
            ->company()
            ->orderBy('id','DESC')
            //->take(10)
            ->get([\DB::raw("concat(customer_name, ' (', customer_code) as name"), 'id','user_id']);
        $result = [];
        foreach($data as $detail) {
            $result[$detail->user_id] = $detail->name .')';
        }
        
        return ['' => '-Select Customer-'] + $result;
    }
}


