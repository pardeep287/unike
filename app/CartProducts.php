<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartProducts extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cart_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'cart_id',
        'status',
        'created_by',
        'updated_by',
        'deleted_at',
        'deleted_by',
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
     * @param $inputs
     * @param null $id
     * @return mixed
     */
    public function store($inputs, $id = null)
    {
        if ($id) {
            $this->find($id)->update($inputs);
        }
        return $this->create($inputs)->id;
    }

    /**
     * @param $cart_id
     * @param null $product_id
     * @return mixed
     */
    public function getCartProduct($cart_id,$product_id=null)
    {
        $fields = [
            'id',
            'product_id',
            'cart_id',
            'status',

        ];
        //$filter = 1; // default filter if no search
        $where='';
        /*if(is_array($search) && count($search) > 0) {
            $f1  = (isset($search['product_id']) && $search['product_id'] != '') ?
                " and product_id = '" . $search['product_id'] ."'" : "";

            $filter .= $f1;
        }*/

        if($product_id) {

            $where = "product_id =". $product_id;

           // $filter .= $where;
            /*->where('product_id', $product_id)*/
        }

        //dd($where);
        return $this
            ->active()
            ->where('cart_id', $cart_id)
            ->whereRaw($where)
            ->first($fields);
        //->first($fields);
    }

    /**
     * @param $cart_id
     * @return mixed
     */
    public function getCartProductsCount($cart_id)
    {
        $fields = [
            'id',
            'product_id',
            'cart_id',
            'status',

        ];
        return $this
            ->active()
            ->where('cart_id', $cart_id)
            ->get($fields)
            ->count();

    }

    public function getCartProducts($cart_id)
    {
        $fields = [
            'id',
            'product_id',
            'cart_id',
            'status',

        ];
        return $this
            ->active()
            ->where('cart_id', $cart_id)
            ->get($fields);

    }
}
