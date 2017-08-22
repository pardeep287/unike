<?php

namespace App;
/**
 * :: Company Model ::
 * To manage Company CRUD operations
 *
 **/
use App\User;
use Illuminate\Database\Eloquent\Model;



/**
 * Class Company
 * @package App
 * @author Inderjit Singh
 */
class StateCode extends Model
{


    /**
     * @var string
     */
    protected $table = 'state_master';

    protected $fillable = [
        'id',
        'state_name',
        'state_digit_code',
        'state_char_code'
    ];

    /**
     * @param $inputs
     * @param null $id
     * @return mixed
     */
    public function store($inputs, $id = null)
    {
        //dd($inputs, $id);
        if ($id) {
            return $this->find($id)->update($inputs);
        } else {
            return $this->create($inputs)->id;
        }
    }


    /**
     * @param null $search
     * @param $skip
     * @param $perPage
     * @return mixed
     */
    public function getStateCode($search = null, $skip, $perPage)
    {
        $take = ((int)$perPage > 0) ? $perPage : 20;
        $filter = 1; // default filter if no search

        $fields = [
            'id',
            'state_name',
            'state_digit_code',
            'state_char_code'
        ];
        /* Sorting operation */
        $sortBy = [
            'name' => 'state_name',

        ];

        $orderEntity = 'id';
        $orderAction = 'desc';
        if (isset($search['sort_action']) && $search['sort_action'] != "") {
            $orderAction = ($search['sort_action'] == 1) ? 'desc' : 'asc';
        }

        if (isset($search['sort_entity']) && $search['sort_entity'] != "") {
            $orderEntity = (array_key_exists($search['sort_entity'], $sortBy)) ? $sortBy[$search['sort_entity']] : $orderEntity;

        }

        /*End of sorting operation*/

        if (is_array($search) && count($search) > 0) {
            $name = (array_key_exists('keyword', $search)) ? " AND state_name LIKE '%" .
                addslashes(trim($search['keyword'])) . "%' " : "";
            $filter .= $name;
        }

        //dd($filter);
        return $this
            //->leftJoin('role', 'role.id', '=', 'users.role_id')
            //->leftJoin('company', 'company.id', '=', 'users.company_id')
            ->whereRaw($filter)
            //->company()
            ->orderBy($orderEntity, $orderAction)
            ->skip($skip)->take($take)->get($fields);
    }


}


