<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cart_master';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'cart_date',
        'user_buyer_id',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Scope a query to only include active users.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * @param type $query
     * @return type
     */
    public function scopeCompany($query)
    {
        return $query->where('company_id', loggedInCompanyId());
    }

    /**
     * @param $inputs
     * @param null $id
     * @return \Illuminate\Validation\Validator
     */

    public function  validate( $inputs, $id = null)
    {

        if($id)
        {
            // $rules['name'] = 'required|unique:brand,name,' . $id .',id,deleted_at,NULL,company_id,'.loggedInCompanyId();
            $rules['user_id'] = 'required|numeric|min:1';
            $rules['product_id'] = 'required|numeric|min:1';
            $rules['size_id'] = 'required|array';
            $rules['quantity'] = 'required|array|not_in:0';
        }
        else {
            // $rules['name'] = 'required|unique:brand,name,NULL,id,deleted_at,NULL,company_id,'.loggedInCompanyId();
            $rules['user_id'] = 'required|numeric|min:1';
            $rules['product_id'] = 'required|numeric|min:1';
            $rules['size_id'] = 'required|array';
            $rules['size_id.*'] = 'required|not_in:0';

            $rules['quantity'] = 'required|array';
            $rules['quantity.*'] = 'required|not_in:0';

        }

        return \Validator::make($inputs, $rules);
    }

    /**
     * @param $inputs
     * @param null $id
     * @return \Illuminate\Validation\Validator
     */
    public function  validateCartDeleteItems( $inputs, $id = null)
    {

        if($id)
        {
            // $rules['name'] = 'required|unique:brand,name,' . $id .',id,deleted_at,NULL,company_id,'.loggedInCompanyId();
            $rules['user_id'] = 'required|numeric|min:1';
            $rules['cart_id'] = 'required|numeric|min:1';
            $rules['cart_product_id'] = 'required_without_all:cart_size_id|array';
            $rules['cart_size_id'] = 'required_without_all:cart_product_id|array';
        }
        else {
            // $rules['name'] = 'required|unique:brand,name,NULL,id,deleted_at,NULL,company_id,'.loggedInCompanyId();
            $rules['user_id'] = 'required|numeric|min:1';
            $rules['cart_id'] = 'required|numeric|min:1';
            $rules['cart_product_id'] = 'required_without_all:cart_size_id|array';
            $rules['cart_size_id'] = 'required_without_all:cart_product_id|array';
            if(isset($inputs['cart_product_id']) && is_array($inputs['cart_product_id'])){
                foreach($inputs['cart_product_id'] as $key => $val){
                    $rules['cart_product_id.'.$key] = 'required_without_all:cart_size_id|not_in:0';
                }
            }
            if(isset($inputs['cart_size_id']) && is_array($inputs['cart_size_id'])) {
                foreach ($inputs['cart_size_id'] as $keys => $vals) {
                    $rules['cart_size_id.' . $keys] = 'required_without_all:cart_product_id|not_in:0';
                }
            }

        }


        $message = array(
            'cart_product_id' => 'The cart_product_id field is required when none of cart_size_id are present.',
            'cart_size_id' => 'The cart_size_id field is required when none of cart_product_id are present.',
        );


        $validator = \Validator::make($inputs, $rules, $message);

        return $validator;
        //return \Validator::make($inputs, $rules);
    }


    /**
     * @param $inputs
     * @param null $id
     * @return \Illuminate\Validation\Validator
     */
    public function  validateCartEditItems( $inputs, $id = null)
    {

        if($id)
        {
            // $rules['name'] = 'required|unique:brand,name,' . $id .',id,deleted_at,NULL,company_id,'.loggedInCompanyId();
            $rules['user_id'] = 'required|numeric|min:1';
            $rules['cart_id'] = 'required|numeric|min:1';
            $rules['quantity'] = 'required_without_all:cart_size_id|array';
            $rules['cart_size_id'] = 'required_without_all:quantity|array';
            if(isset($inputs['quantity']) && is_array($inputs['quantity'])){
                foreach($inputs['quantity'] as $key => $val){
                    $rules['quantity.'.$key] = 'required_without_all:cart_size_id|not_in:0';
                }
            }
            if(isset($inputs['cart_size_id']) && is_array($inputs['cart_size_id'])) {
                foreach ($inputs['cart_size_id'] as $keys => $vals) {
                    $rules['cart_size_id.' . $keys] = 'required_without_all:quantity|not_in:0';
                }
            }
        }
        else {
            // $rules['name'] = 'required|unique:brand,name,NULL,id,deleted_at,NULL,company_id,'.loggedInCompanyId();
            $rules['user_id'] = 'required|numeric|min:1';
            $rules['cart_id'] = 'required|numeric|min:1';
            $rules['quantity'] = 'required_without_all:cart_size_id|array';
            $rules['cart_size_id'] = 'required_without_all:cart_product_id|array';
            if(isset($inputs['quantity']) && is_array($inputs['quantity'])){
                foreach($inputs['quantity'] as $key => $val){
                        $rules['quantity.'.$key] = 'required_without_all:cart_size_id|not_in:0';
                    }
            }
            if(isset($inputs['cart_size_id']) && is_array($inputs['cart_size_id'])) {
                foreach ($inputs['cart_size_id'] as $keys => $vals) {
                    $rules['cart_size_id.' . $keys] = 'required_without_all:quantity|not_in:0';
                }
            }

        }


        $message = array(
            'cart_product_id' => 'The cart_product_id field is required when none of cart_size_id are present.',
            'cart_size_id' => 'The cart_size_id field is required when none of cart_product_id are present.',
        );


        $validator = \Validator::make($inputs, $rules, $message);

        return $validator;
        //return \Validator::make($inputs, $rules);
    }

    /**
     * @param $inputs
     * @param null $id
     * @return mixed
     */
    public function store($inputs, $id = null)
    {
        if ($id) {
            $this->find($id)->update($inputs);

        } else {
            return $this->create($inputs)->id;
            //return $id;
        }
    }

    public function findByUserId($user_id)
    {
        $fields = [
            'id',
            'user_id',
            'status',

        ];

        return $this
            ->where('cart_master.user_id', $user_id)
            ->where('cart_master.status', 0)
            //->get($fields);
            ->first($fields);


    }

    
}
