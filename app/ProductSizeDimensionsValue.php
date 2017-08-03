<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductSizeDimensionsValue extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_size_dimension_value';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


    protected $fillable = [
        'product_id',
        'size_id',
        'dimension_id',
        'dimension_value',

    ];

    public $timestamps = false;

    /**
     * @param $input
     * @param null $id
     * @return bool|mixed
     */

    public function store($input, $id = null,$isMArray = false)
    {
        if ($id) {
            return $this->find($id)->update($input);
        }
        else
        {
            if ($isMArray)
            {
                $this->insert($input);
            }
            else
            {
                return $this->create($input)->id;
            }
        }
    }

    public function getProductDimensionValue($product_id)
    {


        $fields = [
            'product_id',
            'size_id',
            'dimension_id',
            'dimension_value',
            'product_size_dimension_value.id as product_size_dimensions_id',
            //'dimensions_name',
        ];



        return $this
            //->active()
            ->leftJoin('product_master', 'product_size_dimension_value.product_id', '=', 'product_master.id')
            //->leftJoin('product_cost', 'product_sizes.id', '=', 'product_cost.size_id')
            //->leftJoin('sizes', 'product_sizes.size_id', '=', 'sizes.id')
            //->whereNull('product_sizes.deleted_at')
            //->whereIn('product_type_id', [4])
            ->where('product_size_dimension_value.product_id', $product_id)
            ->where('product_master.status', 1)
            //->where('product_cost.status', 1)
            //->where('sizes.id', '!=', "")
            //->whereRaw($filter)
            //->orderBy('product_id', 'ASC')
            //->orderBy('_order', 'ASC')
            //->skip($skip)->take($take)
            ->get($fields);

    }
}
