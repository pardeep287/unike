<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductDimensions extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_dimension';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


    protected $fillable = [
        'product_id',
        'dimension_name',
        'deleted_by',
        'deleted_at',

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

    public function getProductDimension($product_id)
    {


        $fields = [
            'product_dimension.id as product_dimension_id',
            'dimension_name',
        ];



        return $this
            //->active()
            ->leftJoin('product_master', 'product_dimension.product_id', '=', 'product_master.id')
            //->leftJoin('product_cost', 'product_sizes.id', '=', 'product_cost.size_id')
            //->leftJoin('sizes', 'product_sizes.size_id', '=', 'sizes.id')
            //->whereNull('product_sizes.deleted_at')
            //->whereIn('product_type_id', [4])
            ->where('product_dimension.product_id', $product_id)
            ->where('product_master.status', 1)
            //->where('product_cost.status', 1)
            //->where('sizes.id', '!=', "")
            //->whereRaw($filter)
            //->orderBy('product_id', 'ASC')
            //->orderBy('_order', 'ASC')
            //->skip($skip)->take($take)
            ->get($fields);

    }


    /**
     * @param $id
     */
    public function drop($id)
    {
        $this->find($id)->update([ 'deleted_by' => authUserId(), 'deleted_at' => convertToUtc()]);
    }
}
