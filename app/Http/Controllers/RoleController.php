<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\StoreManageRoleRequest;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Permission;
use App\Role;

/**
 * Class RoleController
 *
 * @package App\Http\Controllers
 */
class RoleController extends Controller
{
    /**
     * @var \App\Role
     */
    private $role;
    
    /**
     * RoleController constructor.
     *
     * @param \App\Role $role
     */
    public function __construct(Role $role)
    {
        $this->role = $role;

        $this->middleware('auth');
        $this->middleware('xss');
        $this->middleware('acl:view-role', ['only' => ['index']]);
        $this->middleware('acl:create-role', ['only' => ['create', 'store']]);
        $this->middleware('acl:edit-role', ['only' => ['edit', 'update', 'getManagePermissions', 'postManagePermissions']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->role->paginate = true;
        $items = $this->role->relation(['rStatus'])->getAll();

        return view('role.index')->with('items', $items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('role.form')
                ->with('item', $this->role)
                ->with('method', 'post')
                ->with('form_url', route('role.store'))
                ->with('form_title', trans('role.actions.create'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Role\StoreRoleRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(StoreRoleRequest $request)
    {
        $role = $this->role->add($request->all());
        
        return $this->getStoreJsonResponse($role, 'role._row', trans('role.notifications.created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $item = $this->role->getOne($id);

        return view('role.form')
                ->with('method', 'put')
                ->with('form_url', route('role.update', [$id]))
                ->with('form_title', trans('role.actions.edit'))
                ->with('item', $item);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Role\UpdateRoleRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(UpdateRoleRequest $request, $id)
    {
        $role = $this->role->edit($id, $request->all());
        
        return $this->getUpdateJsonResponse($role, 'role._row', trans('role.notifications.updated'));
    }

    /**
     * Display form for managing permisions per role
     *
     * @param  int  $id
     * @param  \App\Permission  $permission
     * @return \Illuminate\View\View
     */
    public function getManagePermissions($id, Permission $permission)
    {
        $role = $this->role->getOne($id);
        $permissions = $permission->all()->sortBy('object')->groupBy('object');

        return view('role.manage_permission')
                ->with('role_data', $role)
                ->with('permissions', $permissions)
                ->with('method', 'post')
                ->with('form_url', route('role.permission.update', [$id]))
                ->with('form_title', trans('role.actions.permission'));
    }
    
    /**
     * Store permission for role
     *
     * @param int $id
     * @param \App\Http\Requests\Role\StoreManageRoleRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function postManagePermissions($id, StoreManageRoleRequest $request)
    {
        $role = $this->role->getOne($id);

        $role->givePermissionTo($request->get('permission_id', []));
        
        return $this->getUpdateJsonResponse($role, null, trans('role.notifications.permission'));
    }

}
