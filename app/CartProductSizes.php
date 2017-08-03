<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartProductSizes extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cart_product_size_qty_price';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cart_id',
        'product_id',
        'size_id',
        'quantity',
        'price',
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
     * @param $inputs
     * @param null $id
     * @return mixed
     */
    public function store($inputs, $id = null)
    {
        if ($id) {
            $this->find($id)->update($inputs);

        } else {
            $id = $this->create($inputs)->id;
            return $id;
        }
    }
    public function getCartProductAllSize($cart_id,$product_id)
    {
        $fields = [
            'id',
            'cart_id',
            'product_id',
            'size_id',
            'quantity',
            'price',
            'status',

        ];
        return $this
            ->where('cart_id', $cart_id)
            ->where('product_id', $product_id)
            ->get($fields);
            //->first($fields);


    }
}
