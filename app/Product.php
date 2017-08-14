<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_master';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'hsn_id',
        'tax_id',
        'name',
        'code',
        'description',
        'p_image',
        'd_image',
        'status',
        'created_by',
        'updated_by',
    ];

    /*protected $hidden = [
      'id',
    'created_at',
    'deleted_at'
    ];*/

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

    /**
     * @param $inputs
     * @return \Illuminate\Validation\Validator
     */
    public function validateProduct($inputs , $id = null)
    {
        if ($id) {
            $rules['name']   = 'required|unique:product_master,name,' . $id .',id,deleted_at,NULL,company_id,'.loggedInCompanyId();
            $rules['hsn_id'] = 'required';
            $rules['tax_id'] = 'required';
            $messages = [];
            $messages += [
                    'name.required' => 'The Product :attribute field is required.',
                ];
        } else {
            $rules['name']   = 'required|unique:product_master,name,NULL,id,deleted_at,NULL,company_id,'.loggedInCompanyId();
            $rules['hsn_id'] = 'required';
            $rules['tax_id'] = 'required';
            $messages = [];
            foreach($inputs['price'] as $key => $val)
            {
                $rules['price.'.$key] = 'required|numeric';
                $newkey= $key + 1 ;
                $messages['price.'.$key.'.required'] = 'The field labeled "Price '.$newkey .'" Required.';
                $messages['price.'.$key.'.numeric'] = 'The field labeled "Price '.$newkey .'" only Numeric value Accepted.';
            }

            foreach($inputs['size_master_id'] as $key => $size)
            {
                $rules['size_master_id.'.$key] = 'required';
                $newkey= $key + 1 ;
                $messages['size_master_id.'.$key.'.required'] = 'Please Select the field labeled "Size Master '.$newkey .'".';

            }
            foreach($inputs['dimension_id'] as $key => $size)
            {
                $rules['dimension_id.'.$key] = 'required';
                $newkey= $key + 1 ;
                $messages['dimension_id.'.$key.'.required'] = 'Please Select the field labeled "Dimension '.$newkey .'".';

            }
            $messages = $messages + [
                'name.required' => 'The Product :attribute field is required.',
            ];

        }
        return \Validator::make($inputs, $rules,$messages);
    }

    /**
     * @param $inputs
     * @return \Illuminate\Validation\Validator
     */
    public function validateProductSize($inputs , $id = null)
    {
        if ($id) {

        } else {

            $rules['size_master_id'] = 'required';
            $rules['price'] = 'required|numeric|min:1';


        }
        return \Validator::make($inputs, $rules);
    }
    public function validateProductSizeDim($inputs , $id = null)
    {
        if ($id) {

        } else {

            $rules['dim-34'] = 'required|numeric';


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
            $id = $this->create($inputs)->id;
            return $id;
        }
    }

    /**
     * Method is used to search total results.
     *
     * @param array $search
     * @param int $skip
     * @param int $perPage
     *
     * @return mixed
     */
    public function getProduct($search = null, $skip, $perPage, $isApi=false)
    {

        //trimInputs($search);
        $take = ((int)$perPage > 0) ? $perPage : 20;
        $filter = 1; // default filter if no search

        $fields = [
            'id',
            'company_id',
            'hsn_id',
            'name',
            'code',
            'status',
            'created_by',
            'updated_by',

        ];
        $orderEntity = 'id';
        $orderAction = 'desc';
        if($isApi)
        {
            $fields = [
                'id',
                'name',
                'p_image',
            ];
            $orderAction = 'Asc';
        }
        $sortBy = [
            'name' => 'name',

        ];


        if (isset($search['sort_action']) && $search['sort_action'] != "") {
            $orderAction = ($search['sort_action'] == 1) ? 'desc' : 'asc';
        }

        if (isset($search['sort_entity']) && $search['sort_entity'] != "") {
            $orderEntity = (array_key_exists($search['sort_entity'], $sortBy)) ? $sortBy[$search['sort_entity']] : $orderEntity;
        }

        if (is_array($search) && count($search) > 0) {
            $keyword = (array_key_exists('keyword', $search) && $search['keyword'] !='') ? " AND (name LIKE '%" .
                addslashes(trim($search['keyword'])) . "%')" : "";
            $filter .= $keyword;
        }

        $result  =  $this->whereRaw($filter)
                         ->orderBy($orderEntity, $orderAction)
                         ->skip($skip)->take($take)->get($fields);
        return $result;

    }

    /**
     * Method is used to get total results.
     *
     * @param array $search
     *
     * @return mixed
     */
    public function totalProduct($search = null)
    {

        $filter = 1; // default filter if no search

        if (is_array($search) && count($search) > 0) {
            $keyword = (array_key_exists('keyword', $search)) ? " AND (name LIKE '%" .
                addslashes(trim($search['keyword'])) . "%')" : "";
            $filter .= $keyword;
        }
        return $this->select(\DB::raw('count(*) as total'))->whereRaw($filter)->get()->first();
    }

    /**
     * @return mixed
     */
    public function getProductService()
    {
        $data = $this->active()->company()->get([\DB::raw("concat(name, ' (', code) as name"), 'id']);
        $result = [];
        foreach($data as $detail) {
            $result[$detail->id] = $detail->name .')';
        }
        return ['' => '-Select Units-'] + $result;
    }

    public function findById($productId)
    {

        $fields = [

            'product_master.*',
            \DB::raw('GROUP_CONCAT(image_name) as images'),
            //'group_concat(product_images.image_name) ol',
            //'DB::raw(group_concat(product_images.image_name) as namefs',
            //'dimension_name',
            //'product_size_dimensions_value.value',

        ];

        return $this

            //->select('product_master.id')
            //->select('hsn_id',\DB::raw("group_concat(product_images.image_name) as namesd"))
            ->select('product_master.*')
            ->selectRaw('GROUP_CONCAT(image_name) as images')
            ->leftJoin('product_images', 'product_master.id', '=', 'product_images.product_id')
            //->whereNull('product_sizes.deleted_at')
            //->whereIn('product_type_id', [4])
            ->where('product_master.id', $productId)
            //->where('size_master.status', 1)
            ->groupBy('product_master.id')
            //->where('sizes.id', '!=', "")
            //->whereRaw($filter)
            //->orderBy('product_id', 'ASC')
            // ->orderBy('size_master_id', 'ASC')
            //->skip($skip)->take($take)
            //->get($fields);
            ->first($fields);
    }

    /**
     * @param $product_id
     * @return mixed
     */

    public function getProductDetailOnly($productId)
    {
        $fields = [
            'id as product_id',
            'name',
            'p_image',
            'tax_id',
        ];
        return $this
            ->active()
            ->where('product_master.id', $productId)
            ->first($fields);
            //->first();
    }

}
