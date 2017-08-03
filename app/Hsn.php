<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hsn extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
     protected $table = 'hsn_master';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hsn_code',
        'company_id',
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
    public function validateHsn($inputs, $id = null)
    {
        /*if ($inputs) {
            $rules = [
                'hsn_code' => 'required|unique:currency,name,NULL,id,deleted_at,NULL,company_id,'.loggedInCompanyId();
           ];
        }*/
        if ($id) {
            $rules['hsn_code'] = 'required|numeric|unique:hsn_master,hsn_code,' . $id .',id,deleted_at,NULL,company_id,'.loggedInCompanyId();

        } else {

            $rules['hsn_code'] = 'required|numeric|unique:hsn_master,hsn_code,NULL,id,deleted_at,NULL,company_id,'.loggedInCompanyId();
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
            return $this->find($id)->update($inputs);
        } else {
            return $this->create($inputs)->id;
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
    public function getHsn($search = null, $skip, $perPage)
    {

        //trimInputs($search);
        $take = ((int)$perPage > 0) ? $perPage : 20;
        $filter = 1; // default filter if no search

        $fields = [
            'id',
            'hsn_code',
            'company_id',
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
            $keyword = (array_key_exists('keyword', $search) && $search['keyword'] !='') ? " AND (hsn_code LIKE '%" .
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
    public function totalHsn($search = null)
    {

        $filter = 1; // default filter if no search

        if (is_array($search) && count($search) > 0) {
            $keyword = (array_key_exists('keyword', $search)) ? " AND (hsn_code LIKE '%" .
                addslashes(trim($search['keyword'])) . "%')" : "";
            $filter .= $keyword;
        }
        return $this->select(\DB::raw('count(*) as total'))->whereRaw($filter)->get()->first();
    }

    /**
     * @return mixed
     */
    public function getHsnService()
    {
        $data = $this->active()->company()->get([\DB::raw("hsn_code as name"), 'id']);
        $result = [];
        foreach($data as $detail) {
            $result[$detail->id] = $detail->name ;
        }
        return ['' => '-Select Hsn Code-'] + $result;
    }
}
