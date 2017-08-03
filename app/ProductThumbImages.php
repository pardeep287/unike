<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductThumbImages extends Model
{
    protected $table = 'product_images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'image_name',
        'status',

    ];

    public $timestamps = false;

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

    public function getProductThumbImages($product_id)
    {


        $fields = [
            'product_images.id as product_image_thumb_id',
            'image_name',
        ];



        return $this
            //->active()
            ->leftJoin('product_master', 'product_images.product_id', '=', 'product_master.id')
            //->leftJoin('product_cost', 'product_sizes.id', '=', 'product_cost.size_id')
            //->leftJoin('sizes', 'product_sizes.size_id', '=', 'sizes.id')
            //->whereNull('product_sizes.deleted_at')
            //->whereIn('product_type_id', [4])
            ->where('product_images.product_id', $product_id)
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
     * @param int $id
     * @return int
     */
    public function drop($id)
    {
        $this->find($id)->delete();
    }
}
