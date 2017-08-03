<?php

namespace App;
/**
 * :: Company Model ::
 * To manage Company CRUD operations
 *
 **/

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'company';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'company_name',
        'company_logo',
        'contact_person',
        'tin_number',
        'pan_number',
        'gst_number',
        'email1',
        'email2',
        'mobile1',
        'mobile2',
        'phone',
        'website',
        'permanent_address',
        'correspondence_address',
        'country',
        'state',
        'city',
        'pincode',
        'timezone',
        'status',
        'is_full_version',
        'created_by',
        'updated_by',
        'deleted_by'
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
     * @param $inputs
     * @return \Illuminate\Validation\Validator
     */
    public function validateCompany($inputs, $tab)
    {
        if ($tab == 1) {
            $rules = [
                'company_name' => 'required',
                //'abn_number' => 'required',
                'email1' => 'required',
                'mobile1' => 'required'
            ];
        }
        else if($tab == 2) {
            $rules = [
                'company_logo' => 'required|image'
            ];
        }
        else if($tab == 3) {
            $rules = [
                'currency' => 'required',
                'datetime_format' => 'required',
                'timezone' => 'required',
                //'theme' => 'required'
            ];
        }
        return \Validator::make($inputs, $rules);
    }

    /**
     * @param $inputs
     * @return \Illuminate\Validation\Validator
     */
    public function validateCompanyLogo($inputs)
    {
        $rules = [
            'company_logo' => 'required|image'
        ];

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
    public function getCompany($search = null, $skip, $perPage)
    {

        //trimInputs($search);
        $take = ((int)$perPage > 0) ? $perPage : 20;
        $filter = 1; // default filter if no search

        $fields = [
            'id',
            'company_name',
            'contact_person',
            'gst_number',
            'email1',
            'mobile1',
            'status',
            'created_by',
            'updated_by',
            'deleted_by',
        ];

        if (is_array($search) && count($search) > 0) {
            $keyword = (array_key_exists('keyword', $search) && $search['keyword'] !='') ? " AND (company_name LIKE '%" .
                addslashes(trim($search['keyword'])) . "%')" : "";
            $filter .= $keyword;
        }
        $result  =  $this->whereRaw($filter)->orderBy('id', 'ASC')
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
    public function totalCompany($search = null)
    {

        $filter = 1; // default filter if no search

        if (is_array($search) && count($search) > 0) {
            $keyword = (array_key_exists('keyword', $search)) ? " AND (company_name LIKE '%" .
                addslashes(trim($search['keyword'])) . "%')" : "";
            $filter .= $keyword;
        }
        return $this->select(\DB::raw('count(*) as total'))->whereRaw($filter)->get()->first();
    }

    /**
     * @return mixed
     */
    public function getCompanyService()
    {
        $result = $this->active()->pluck('company_name', 'id')->toArray();
       
        return ['' => '-Select Company-'] + $result;
    }

    /**
     * Method is used to get company information
     *
     * @param null $id
     *
     * @return mixed
     */
    public function getCompanyInfo($id = null)
    {
        if($id) {
            return $this->where('id', $id)->first();
        }
        return $this->first();
    }
}
