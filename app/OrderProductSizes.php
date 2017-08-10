<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProductSizes extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'order_product_size_qty_price';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'order_id',
        'order_product_id',
        'product_id',
        'cgst',
        'cgst_amount',
        'sgst',
        'sgst_amount',
        'igst',
        'igst_amount',
        'size_id',
        'quantity',
        'price',
        'status',
        'total_price',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
    ];

    public $timestamps = false;

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
     * Method is used to search total results.
     *
     * @param array $search
     *
     * @return mixed
     */
    public function getInvoiceItems($search = null)
    {
        $fields = [
            'order_product_size_qty_price.*',
            //'order_master.order_number',
            //'order_master.order_date',
            //'order_master.financial_year_id',
            //'order_master.gross_amount',
            //'order_master.round_off',
            'product_master.name',
            'product_master.hsn_id',
            'product_master.tax_id',

           // 'hsn_master.id as hsn_id',
            'hsn_master.hsn_code',
            //'unit.id as unit_id',
            //'unit.name as unit',
           // 'unit.code as unit',
            'tax_master.name as tax_group',
            'product_sizes.size_master_id',
            'size_master.name as normal_size',

        ];
        $filter = 1; // default filter if no search
        if (is_array($search) && count($search) > 0) {
            $f1 = (array_key_exists('order_id', $search) && $search['order_id'] != "") ? " AND (order_id = " .
                addslashes(trim($search['order_id'])) . ")" : "";

            $filter .= $f1;
            return $this->leftJoin('order_master', 'order_master.id', '=', 'order_product_size_qty_price.order_id')
                  ->leftJoin('product_master', 'product_master.id', '=', 'order_product_size_qty_price.product_id')
                  ->leftJoin('tax_master', 'tax_master.id', '=', 'product_master.tax_id')
                  ->leftJoin('hsn_master', 'hsn_master.id', '=', 'product_master.hsn_id')
                  ->leftJoin('product_sizes', 'product_sizes.id', '=', 'order_product_size_qty_price.size_id')
                  ->leftJoin('size_master', 'size_master.id', '=', 'product_sizes.size_master_id')
                //->leftJoin('unit', 'unit.id', '=', 'product.unit_id')
                ->whereRaw($filter)
                ->get($fields);
        }
        return null;
    }

    public function getOrderProductAllSize($cart_id,$product_id)
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
            ->active()
            ->where('cart_id', $cart_id)
            ->where('product_id', $product_id)
            ->get($fields);
        //->first($fields);


    }
}
