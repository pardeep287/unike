<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProducts extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'order_products';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [

        'product_id',
        'order_id',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',

    ];

   // public $timestamps = false;

    /**
     * Scope a query to only include active users.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('order_products.status', 1);
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
        else {
            return $this->create($inputs)->id;
        }
    }


    /**
     * @return null
     */
    public function getProductsByOrderId($order_id)
    {
        $fields = [
            'order_products.*',
            'product_master.name',
            ];
        
        $filter = 1;
        return $this
            ->join('product_master','order_products.product_id','product_master.id')
            ->where('order_id',$order_id)
            ->active()
            ->get($fields);
    }
}
