<?php

 namespace App;
 use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\SoftDeletes;

 class FinancialYear extends Model
 {
     use SoftDeletes;

     protected $table = 'financial_year';

     protected $fillable = [
         'name',
         'from_date',
         'to_date',
         'status',
         'company_id',
         'created_at',
         'updated_at',
         'deleted_at',
         'deleted_by'
     ];

     /**
      * @param $query
      * @return mixed
      */
     public function scopeActive($query)
     {
         return $query->where('status', 1);
     }
     
     public function scopeCompany($query)
     {  
         return $query->where('company_id', loggedInCompanyId());
     }

     /**
      * @param $inputs
      * @param null $id
      * @return \Illuminate\Validation\Validator
      */
     public function validateFinancialYear($inputs, $id = null)
     { 
        /*
        $rules['name'] = 'required';
        $rules['from_date'] = 'required|date';
        $rules['to_date'] = 'required|date';*/
         if ($id) {
             $rules['name'] = 'required|unique:financial_year,name,' . $id .',id,deleted_at,NULL,company_id,'.loggedInCompanyId();
             $rules['from_date'] = 'required|date';
             $rules['to_date'] = 'required|date';
         } else {

             $rules['name'] = 'required|unique:financial_year,name,NULL,id,deleted_at,NULL,company_id,'.loggedInCompanyId();
             $rules['from_date'] = 'required|date';
             $rules['to_date'] = 'required|date';
         }
        return \Validator::make($inputs, $rules);
     }

     /**
      * @param $input
      * @param null $id
      * @return mixed
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
      * @return mixed
      */
     public function updateStatusAll()
     {
         return $this->company()->where('status', 1)->update(['status' => 0]);
     }

     /**
      * @param null $search
      * @param $skip
      * @param $perPage
      * @return mixed
      */
     public function getFinancialYears($search = null, $skip, $perPage)
     {
         $take = ((int)$perPage > 0) ? $perPage : 20;
         $filter = 1; // default filter if no search

         $fields = [
             'id',
             'name',
             'from_date',
             'to_date',
             'status',
         ];

         $sortBy = [
             'name' => 'name',
             'from_date' => 'from_date',
             'to_date' => 'to_date',
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
             $keyword = (array_key_exists('keyword', $search)) ?
                 " AND (name LIKE '%" .addslashes(trim($search['keyword'])) . "%')" : "";
             $filter .= $keyword;
         }

         return $this
             ->whereRaw($filter)
             ->company()
             ->orderBy($orderEntity, $orderAction)
             ->skip($skip)->take($take)->get($fields);
     }

     /**\
      * @param null $search
      * @return mixed
      */
     public function totalFinancialYears($search = null)
     {
         $filter = 1; // if no search add where

         // when search
         if (is_array($search) && count($search) > 0) {
             $partyName = (array_key_exists('keyword', $search)) ? " AND name LIKE '%" .
                 addslashes(trim($search['keyword'])) . "%' " : "";
             $filter .= $partyName;
         }
         return $this->select(\DB::raw('count(*) as total'))
             ->whereRaw($filter)->company()->first();
     }

     /**
      * @return mixed
      */
     public function getActiveFinancialYear()
     {
        return $this->active()->company()->first();
     }

     /**
      * @param $id
      */
    public function drop($id)
    {
        $this->find($id)->update([ 'deleted_by' => authUserId(), 'deleted_at' => convertToUtc()]);
    }
 }