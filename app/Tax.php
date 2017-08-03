<?php
namespace App;
/**
 * :: Tax Model ::
 * To manage Tax CRUD operations
 *
 **/

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tax extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tax_master';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'name',
        'cgst_rate',
        'sgst_rate',
        'igst_rate',
        'wef',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

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
    /**
     * @param type $query
     * @return type
     */
    public function scopeCompany($query)
    {
        return $query->where('company_id', loggedInCompanyId());
    }

    /**
     * Method is used to validate roles
     *
     * @param $inputs
     * @param int $id
     * @return Response
     */
    public function validateTax($inputs, $id = null)
    {
        // validation rule
        if ($id) {
            $rules['name'] = 'required|unique:tax_master,name,' . $id .',id,deleted_at,NULL,company_id,'.loggedInCompanyId();
            $rules['cgst_rate'] = 'required|between:0,999999.99';
        } else {
            $rules['name'] = 'required|unique:tax_master,name,NULL,id,deleted_at,NULL,company_id,'.loggedInCompanyId();
            $rules['cgst_rate'] = 'required|between:0,999999.99';
            //$rules['code'] = 'required|unique:tax,code,NULL,id,deleted_at,NULL,company_id,'.loggedInCompanyId();

        }
        return \Validator::make($inputs, $rules);
    }

    /**
     * Method is used to save/update resource.
     *
     * @param   array $input
     * @param   int $id
     * @return  Response
     */
    public function store($input, $id = null)
    {

        if ($id) {
            return $this->find($id)->update($input);
        } else {
            return $this->create($input)->id;
        }
    }

    /**
     * Method is used to search news detail.
     *
     * @param array $search
     * @param int $skip
     * @param int $perPage
     *
     * @return mixed
     */
    public function getTax($search = null, $skip, $perPage)
    {

        //trimInputs($search);
        $take = ((int)$perPage > 0) ? $perPage : 20;
        $filter = 1; // default filter if no search

        $fields = [
            'id',
            'company_id',
            'name',
            'cgst_rate',
            'sgst_rate',
            'igst_rate',
            'wef',
            'status',
            'created_by',
            'updated_by',
            'deleted_by',

        ];

        $sortBy = [
            'name' => 'name',
        ];

        $orderEntity = 'id';
        $orderAction = 'desc';
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
            //->orderBy('id', 'ASC')
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
    public function totalTax($search = null)
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
    public function getTaxService()
    {
        $data = $this->active()->company()->get([\DB::raw("name as name"), 'id']);
        $result = [];
        foreach($data as $detail) {
            $result[$detail->id] = $detail->name ;
        }
        return ['' => '-Select Tax-'] + $result;
    }

    /**
     * @param $id
     * @return bool
     */
    public function taxExists($id)
    {
        $taxExistsInProduct = (new Product())->where('tax_id',$id)->first();
        if(count($taxExistsInProduct) > 0) {
            return true;
        }
    }
}
