<?php
namespace App;
/**
 * :: Product Cost Model ::
 * To manage Tax CRUD operations
 *
 **/

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCost extends Model
{/**
 * The database table used by the model.
 *
 * @var string
 */
    protected $table = 'product_cost';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'size_id',
        'price',
        'manual_price',
        'discount',
        'wef',
        'wet',
        'status'
    ];

    public $timestamps = false;

    /**
     * Scope a query to only include active users.
     *
     * @param $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function validatePrice($inputs , $id = null)
    {


        if ($id) {
            $rules['price.'] = 'required|numeric|min:1';
        } else {
            $rules['price'] = 'required|numeric|min:1';
        }
        return \Validator::make($inputs, $rules);
    }
    
    
    

    /**
     * @param $input
     * @param null $id
     * @return mixed
     */
    public function store($input, $id = null,$isMArray = false)
    {
        if ($id) {
            //dd($input, $id);
            $this->find($id)->update($input);
            return $id;
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

    /**
     * @param bool $active
     * @param array $search
     * @return mixed
     */
    public function findBySizeID($sizeId = false){

            $fields = [
                'id',
            ];

            return $this->active()->where( 'size_id', $sizeId)->first($fields);

    }

    /**
     * @param bool $active
     * @param array $search
     * @return mixed
     */
    

    public function getEffectedCost($active = true, $search = [])
    {
        $filter = 1;
        if (is_array($search) && count($search) > 0)
        {
            $tax = (array_key_exists('product', $search)) ? " AND product_id = '" .
                addslashes(trim($search['product'])) . "'" : "";
            $filter .= $tax;

            $from = (array_key_exists('from', $search)) ? " AND wef = '" .
                addslashes(trim($search['from'])) . "' " : "";
            $filter .= $from;
        }

        if ($active)
        {
            $active = " AND status = 1";
            $filter .= $active;
        }
        return $this->whereRaw($filter)->Company()->first();
    }


    /**
     * @param $id
     * @param $date
     * @return mixed
     */
    public function getEffectedCostRate($id, $date)
    {
        return $this->where('product_id', $id)->Company()
            ->where(function($query) use ($date) {
                $query->where(function($inner) use ($date) {
                    $inner->where('wef', '<=', $date)
                        ->where('wet', '>=', $date);
                });
                $query->oRWhere(function($inner) use ($date) {
                    $inner->where('wef', '<=', $date)
                        ->whereNull('wet');
                });
            })->first();
    }

    /**
     * @param $inputs
     */
    public function uploadProductCost($inputs)
    {
        $costId = $inputs['cost_id'];
        $result = $this->where('id', $costId)->where('company_id', loggedInCompanyId())->first();

        if($result && (float)$result->cost != (float)$inputs['cost']) {
            // update product cost
            $update = [
                'wet' => convertToUtc(),
                'status' => 0
            ];
            $result->update($update);
            // create product cost
            $create = [
                'product_id' => $result->product_id,
                'company_id' => loggedInCompanyId(),
                'cost' => $inputs['cost'],
                'wef' => convertToUtc(),
                'wet' => null,
                'status' => 1,
            ];
            $this->create($create);
        }
    }
}

