<?php

namespace App\Http\Controllers;

use App\Http\Requests\Permission\StorePermissionRequest;
use App\Http\Requests\Permission\UpdatePermissionRequest;
use App\Permission;

/**
 * Class PermissionController
 *
 * @package App\Http\Controllers
 */
class PermissionController extends Controller
{
    /**
     * @var \App\Permission
     */
    private $permission;
    
    /**
     * PermissionController constructor.
     *
     * @param \App\Permission $permission
     */
    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
		
        $this->middleware('auth');
        $this->middleware('xss');
        $this->middleware('acl:view-permission', ['only' => ['index']]);
        $this->middleware('acl:create-permission', ['only' => ['create', 'store']]);
        $this->middleware('acl:edit-permission', ['only' => ['edit', 'update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->permission->paginate = true;
        $this->permission->keywords = request('keywords');
        $this->permission->objectId = request('object');
        $this->permission->statusId = request('status');
        $items = $this->permission->relation(['rStatus'])->getAll();
    
        $objects = $this->permission->getObjects();
        
        return view('permission.index')
                ->with('items', $items)
                ->with('objects', $objects);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $objects = $this->permission->getObjects();
        
        return view('permission.form')
            ->with('item', $this->permission)
                ->with('objects', $objects)
                ->with('method', 'post')
                ->with('form_url', route('permission.store'))
                ->with('form_title', trans('permission.actions.create'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Permission\StorePermissionRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(StorePermissionRequest $request)
    {
        $permission = $this->permission->add($request->all());
        
        return $this->getStoreJsonResponse($permission, 'permission._row', trans('permission.notifications.created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $item = $this->permission->getOne($id);
        $objects = $this->permission->getObjects();

        return view('permission.form')
                ->with('objects', $objects)
                ->with('method', 'put')
                ->with('form_url', route('permission.update', [$id]))
                ->with('form_title', trans('permission.actions.edit'))
                ->with('item', $item);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Permission\UpdatePermissionRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(UpdatePermissionRequest $request, $id)
    {
        $permission = $this->permission->edit($id, $request->all());
        
        return $this->getUpdateJsonResponse($permission, 'permission._row', trans('permission.notifications.updated'));
    }
}
