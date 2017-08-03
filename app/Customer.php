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
        'customer_name',
        'mobile_no',
        'alternate_mobile_no',
        'landline_no',
        'email',
        'address',
        'alternate_address',
        'country',
        'state',
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
    /*
     * required:username,password(min:5),email,mobile,phone
     */
    public function  validateCustomer( $inputs, $id = null )
    {
        $rules = [
            'customer_name' => 'required',
           // 'username'      => 'required',
           // 'mobile_no'     => 'required|numeric'
        ];

        if($id)
        {
            $rules['email'] = 'required|email';
            if(array_key_exists('password', $inputs)) {
                $rules = $rules + ['password' => 'min:5'];
            }

            if(array_key_exists('mobile_no', $inputs)) {
                if($inputs['mobile_no'] != '') {
                    $rules = $rules + ['mobile_no'   => 'required|numeric|unique:customers,mobile_no'];
                }
            }
        }
        else {

            $rules = $rules +  ['username' => 'required|unique:users,username'];
            $rules = $rules +  ['password'  => 'required|min:5'];
            $rules = $rules +  ['email'    => 'required|unique:customers,email'];
            $rules = $rules +  ['mobile_no'   => 'required|numeric|digits:10|unique:customers,mobile_no'];

        }

        /*if (array_key_exists('dob', $inputs)) {
            if($inputs['dob'] != '') {
                $rules = $rules + ['dob' => 'date'];
            }
        }*/

        return \Validator::make($inputs, $rules);
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
     * @param $id
     * @return int
     */
    public function deleteCustomer($id)
    {
        return $this->destroy($id);
    }
}