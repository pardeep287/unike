<?php

namespace App\Http\Controllers;
/**
 * :: Menu Controller ::
 * To manage Menu.
 *
 **/

use App\Menu;
use App\BankMaster;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('menu.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function create()
    {
        $parentdata = (new Menu)->parentData();
        return view('menu.create',compact('parentdata'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
     public function store()
    {
        $inputs = \Input::all();
        $validator = (new Menu)->validateMenu($inputs); 
        if ($validator->fails()) { 
            return redirect()->route('menu.create')
                ->withInput($inputs)
                ->withErrors($validator);
        }

        try {
            \DB::beginTransaction();

            $displayName = $inputs['display_name'];
            unset($inputs['display_name']);
            $routeName = $inputs['route_name'];
            unset($inputs['route_name']);
            $parentId = $inputs['parent_menu'];
            unset($inputs['parent_menu']);
            $order = $inputs['order'];
            unset($inputs['order']);

            $inputs = $inputs + [
                'name'        => $displayName,
                'route'       => $routeName,
                'parent_id'   => ($parentId != "") ? $parentId : null,
                '_order'      => $order,
                'created_by'  => authUserId()
            ];
            (new menu)->store($inputs);
            \DB::commit();
            return redirect()->route('menu.index')
                ->with('success', lang('messages.created', lang('menu.menu')));
        }
        catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->route('menu.create')
                ->withInput($inputs)
                ->with('error', lang('messages.server_error'));
        }
    }

    /**
     * Used to load more records and render to view.
     *
     * @param int $pageNumber
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $result = Menu::find($id);
        if (!$result) {
            abort(404);
        }
       // dd($result);
       // dd($result->toArray());
        // $items = (new Menu)->getMenuItems(['id' => $id]);
        $parentdata = (new Menu)->parentData();
        return view('menu.edit', compact('result', 'parentdata'));
    }

    /**
     * Used to update navigation active status.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id = null)
    {
        $result = Menu::find($id);
        if (!$result) {
            return redirect()->route('menu.index')
                ->with('error', lang('messages.invalid_id', string_manip(lang('menu.menu'))));
        }

        $inputs = \Input::all();

        $validator = (new Menu)->validateMenu($inputs, $id);
        if ($validator->fails()) {
            return redirect()->route('menu.edit', ['id' => $id])
                ->withInput()
                ->withErrors($validator);
        }

        try {
            \DB::beginTransaction();

            $displayName = $inputs['display_name'];
            unset($inputs['display_name']);
            $routeName = $inputs['route_name'];
            unset($inputs['route_name']);
            $parentId = $inputs['parent_menu'];
            unset($inputs['parent_menu']);
            $order = $inputs['order'];
            unset($inputs['order']);

            $inputs = $inputs + [
                'name'          => $displayName,
                'route'         => $routeName,
                'parent_id'    => ($parentId != "") ? $parentId : null,
                '_order'      => $order,
                'is_in_menu'    => (isset($inputs['is_in_menu']) ? $inputs['is_in_menu'] : 0),
                'quick_menu'    => (isset($inputs['quick_menu']) ? $inputs['quick_menu'] : 0),
                'is_common'     => (isset($inputs['is_common']) ? $inputs['is_common'] : 0),
                'for_devs'      => (isset($inputs['for_devs']) ? $inputs['for_devs'] : 0),
                'has_child'     => (isset($inputs['has_child']) ? $inputs['has_child'] : 0),
                'status'     => (isset($inputs['status']) ? $inputs['status'] : 0),
                'updated_by' => authUserId()
            ];
            (new Menu)->store($inputs, $id);
            \DB::commit();
            return redirect()->route('menu.index')
                ->with('success', lang('messages.updated', lang('menu.menu')));
        } catch (\Exception $exception) {
            \DB::rollBack();
            return redirect()->route('menu.create')
                ->with('error', lang('messages.server_error'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function menuAction()
    {

        $inputs = \Input::all();
        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('menu.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('menu.menu'))));
        }

        $ids = '';
        foreach ($inputs['tick'] as $key => $value) {
            $ids .= $value . ',';
        }

        $ids = rtrim($ids, ',');
        $status = 0;
        if (isset($inputs['active'])) {
            $status = 1;
        }

        Company::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('menu.index')
            ->with('success', lang('messages.updated', lang('menu.menu_status')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function menuPaginate($pageNumber = null)
    {
        if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }

        $inputs = \Input::all();
        $page = 1;
        if (isset($inputs['page']) && (int)$inputs['page'] > 0) {
            $page = $inputs['page'];
        }

        $perPage = 20;
        if (isset($inputs['perpage']) && (int)$inputs['perpage'] > 0) {
            $perPage = $inputs['perpage'];
        }

        $start = ($page - 1) * $perPage;
        if (isset($inputs['form-search']) && $inputs['form-search'] != '') {
            $inputs = array_filter($inputs);
            unset($inputs['_token']);

            $data = (new Menu)->getMenu($inputs, $start, $perPage);
            $total = (new Menu)->totalMenu($inputs);
            $total = $total->total;
        } else {
            $data = (new Menu)->getMenu($inputs, $start, $perPage);
            $total = (new Menu)->totalMenu($inputs);
            $total = $total->total;
        }
        return view('menu.load_data', compact('data', 'total', 'page', 'perPage'));
    }

    /**
     * @param null $id
     * @return string
     */
    public function menuToggle($id = null)
    {
        if (!\Request::ajax()) {
            return lang('messages.server_error');
        }

        try {
            // get the brand w.r.t id
            $result = Menu::find($id);
            // dd($result);
        } catch (Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('menu.menu')));
        }

        $result->update(['status' => !$result->status]);
        $response = ['status' => 1, 'data' => (int)$result->status . '.gif'];
        // return json response
        return json_encode($response);
    }
    /** * Method is used to sort news.
     *
     * @return Response
     */
    public function sortingMenu()
    {
        $inputAll = \Input::all();
        $menuOrder = $inputAll['order'];
        try {
            if( count($menuOrder) > 0 ) {
                $index = count($menuOrder);
                foreach ($menuOrder as $key => $value) {
                    Menu::where('id', $value)
                        ->update(['_order' => $index--]);
                }
            }
            // return 1 for successfully sorted news.
            echo '1';
        } catch (Exception $e) {
            // else return 0
            echo '0';
        }
    }
    
}
