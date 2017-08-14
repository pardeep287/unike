<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductSizes extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_sizes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'size_master_id',
        //'dimension_name',
        //'dimension_value',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    //public $timestamps = false;
    /**
     * Scope a query to only include active users.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('product_sizes.status', 1);
    }

    /*public function findByProductID($pId = false){

        $fields = [
            'dimension_name',
        ];

        return $this->active()->where( 'product_id', $pId)->first($fields);

    }*/

    public function store($input, $id = null)
    {
        if ($id) {
            //dd($input, $id);
            $id= $this->find($id)->update($input);
            //dd($id);
        }
        else {
            //dd('im');
            return $this->create($input)->id;
        }
    }





    public function getPriceListProductSize($product_id)
    {


        $fields = [
            'product_sizes.id as product_sizes_id',
            'size_master_id',
            //'dimension_name',
            //'dimension_value',
            'product_sizes.status as product_sizes_status',
            'size_master.name as normal_size',
            'product_cost.price as price',
            'product_sizes.status'
            //'product_size_dimensions_value.value',
        ];



        return $this
            ->leftJoin('size_master', 'product_sizes.size_master_id', '=', 'size_master.id')
            ->leftJoin('product_cost', 'product_sizes.id', '=', 'product_cost.size_id')
            //->leftJoin('product_size_dimensions_value', 'product_sizes.id', '=', 'product_size_dimensions_value.product_size_id')
            //->whereNull('product_sizes.deleted_at')
            //->whereIn('product_type_id', [4])
            ->where('product_sizes.product_id', $product_id)
            //->where('size_master.status', 1)
            ->where('product_cost.status', 1)
            //->where('sizes.id', '!=', "")
            //->whereRaw($filter)
            //->orderBy('product_id', 'ASC')
            ->orderBy('size_master_id', 'ASC')
            //->skip($skip)->take($take)
            ->get($fields);

    }

    public function getProductSizeDimensionValue($product_id)
    {


        $fields = [
            'product_sizes.id as product_sizes_id',
            'size_master_id',
            'dimension_name',
            'dimension_value',
            'product_sizes.status as product_sizes_status',
            'size_master.name as normal_size',
            'product_cost.price as price',
            'product_sizes.status'
            //'product_size_dimensions_value.value',
        ];



        return $this
            ->leftJoin('size_master', 'product_sizes.size_master_id', '=', 'size_master.id')
            ->leftJoin('product_cost', 'product_sizes.id', '=', 'product_cost.size_id')
            ->leftJoin('product_size_dimensions_value', 'product_sizes.id', '=', 'product_size_dimensions_value.product_size_id')
            //->whereNull('product_sizes.deleted_at')
            //->whereIn('product_type_id', [4])
            ->where('product_sizes.product_id', $product_id)
            //->where('size_master.status', 1)
            ->where('product_cost.status', 1)
            //->where('sizes.id', '!=', "")
            //->whereRaw($filter)
            //->orderBy('product_id', 'ASC')
            ->orderBy('size_master_id', 'ASC')
            //->skip($skip)->take($take)
            ->get($fields);

    }

    /*public function findByProductID($productId)
    {
        $fields = [
            'product_sizes.id as product_sizes_id',
            'size_master_id',
            'dimension_name',
            'dimension_value',
            'product_sizes.status as product_sizes_status',
            'size_master.name as normal_size',
            //'product_cost.price as price',
            //'product_sizes.status'
            //'product_size_dimensions_value.value',
        ];
        return $this
            ->leftJoin('size_master', 'product_sizes.size_master_id', '=', 'size_master.id')
            //->leftJoin('product_cost', 'product_sizes.id', '=', 'product_cost.size_id')
           // ->leftJoin('product_size_dimensions_value', 'product_sizes.id', '=', 'product_size_dimensions_value.product_size_id')
            //->whereNull('product_sizes.deleted_at')
            //->whereIn('product_type_id', [4])
            ->where('product_sizes.product_id', $productId)
            //->where('size_master.status', 1)
            //->where('product_cost.status', 1)
            //->where('sizes.id', '!=', "")
            //->whereRaw($filter)
            //->orderBy('product_id', 'ASC')
           // ->orderBy('size_master_id', 'ASC')
            //->skip($skip)->take($take)
            ->first($fields);

    }*/


    


}
