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

    public function  validate( $inputs, $id = null)
    {

        if($id)
        {
            // $rules['name'] = 'required|unique:brand,name,' . $id .',id,deleted_at,NULL,company_id,'.loggedInCompanyId();
            $rules['user_id'] = 'required';
            $rules['product_id'] = 'required';
            $rules['size_id'] = 'required';
            $rules['quantity'] = 'required';
        }
        else {
            // $rules['name'] = 'required|unique:brand,name,NULL,id,deleted_at,NULL,company_id,'.loggedInCompanyId();
            $rules['user_id'] = 'required';
            $rules['product_id'] = 'required';
            $rules['size_id'] = 'required';
            $rules['quantity'] = 'required';

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
