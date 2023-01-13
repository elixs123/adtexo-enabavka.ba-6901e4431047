<?php

namespace App\Http\Controllers;

use App\Action;
use App\ActionProduct;
use App\FileHelper;
use App\Http\Requests\Action\StoreActionRequest;
use App\Http\Requests\Action\UpdateActionRequest;
use App\PhotoHelper;
use App\Role;
use App\Stock;
use App\Support\Controller\ActionHelper;
use App\Support\Scoped\ScopedStockFacade as ScopedStock;

/**
 * Class ActionController
 *
 * @package App\Http\Controllers
 */
class ActionController extends Controller
{
    use ActionHelper, PhotoHelper, FileHelper;
    
    /**
     * @var \App\Action
     */
    private $action;
    
    /**
     * @var \App\ActionProduct
     */
    private $actionProduct;
    
    /**
     * ActionController constructor.
     *
     * @param \App\Action $action
     * @param \App\ActionProduct $actionProduct
     */
    public function __construct(Action $action, ActionProduct $actionProduct)
    {
        $this->action = $action;
        $this->actionProduct = $actionProduct;
    
        $this->middleware('auth');
        $this->middleware('acl:view-action', ['only' => ['index']]);
        $this->middleware('acl:create-action', ['only' => ['create', 'store']]);
        $this->middleware('acl:edit-action', ['only' => ['edit', 'update']]);
    }
    
    /**
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $this->action->paginate = true;
        $this->action->limit = 10;
        $this->action->keywords = request('keywords');
        $items = $this->action->relation(['rType', 'rStatus'])->getAll();
        
        return view('action.index')
            ->with('items', $items);
    }
    
    /**
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
     */
    public function create()
    {
        $roles = $this->getRoles();
        
        $stocks = $this->getStocks();
        
        $action_roles = [];
        
        return view('action.form')
            ->with('item', $this->action)
            ->with('method', 'post')
            ->with('form_url', route('action.store'))
            ->with('form_title', trans('action.actions.create'))
            ->with('roles', $roles)
            ->with('stocks', $stocks)
            ->with('action_roles', $action_roles);
    }
    
    /**
     * @param \App\Http\Requests\Action\StoreActionRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(StoreActionRequest $request)
    {
        $input = $request->only([
            'name',
            'type_id',
            'started_at',
            'finished_at',
            'stock_id',
            'stock_type',
            'qty',
            'status',
        ]);
        $input['product_prices'] = [];
        $input['finished_at'] .= ' 23:59:59';
        $input['free_delivery'] = $request->has('free_delivery') ? 1 : 0;
        
        $input['photo'] = $this->upload('photo', $input['name'], config('picture.action_path'), $request);
        $this->makePhotoThumbs(config('picture.action_path') , $input['photo'], config('picture.action_thumbs'), 0);
        
        $input['presentation'] = $this->uploadFile('presentation', $input['name'].'-presentation', config('file.action.path'), $request);
        
        $input['technical_sheet'] = $this->uploadFile('technical_sheet', $input['name'].'-technical_sheet', config('file.action.path'), $request);
    
        $action = $this->action->add($input);
        
        $roles = $request->get('roles', []);
        $action->rRoles()->sync($roles);
    
        return $this->getStoreJsonResponse($action->fresh(['rType', 'rStatus']), 'action._row', trans('action.notifications.created'));
    }
    
    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function show($id)
    {
        $export = request()->get('export', false);
        
        $action = scopedAction()->getQuery()->findOrFail($id);
    
        $products_action = $action->rActionProducts->keyBy('product_id');
        $products_gratis = ($action->isGratis()) ? $action->rGratisProducts->keyBy('product_id') : collect([]);
        
        $products = $this->getActionProducts(array_merge($products_action->pluck('product_id')->toArray(), $products_gratis->pluck('product_id')->toArray()));
        
        $view_data = [
            'action' => $action,
            'products' => $products,
            'currency' => ScopedStock::currency(),
            'stock' => $action->rStock,
            'products_action' => $products_action,
            'products_gratis' => $products_gratis,
        ];
    
        if($export == 'pdf') {
            // return view('action.export_pdf')->with($view_data);
    
            return \PDF::loadView('action.export_pdf', $view_data)->download($action->name.'.pdf');
        }
    
        $range = [];
        for($i = 1; $i <= $action->available_qty; $i++) {
            $range[$i] = $i;
        }
        
        return view('action.show')->with($view_data)->with([
            'range' => $range,
        ]);
    }
    
    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function edit($id)
    {
        $item = $this->action->getOne($id);
    
        $roles = $this->getRoles();
    
        $stocks = $this->getStocks();
    
        $action_roles = $item->rRoles->pluck('id')->toArray();
    
        return view('action.form')
            ->with('method', 'put')
            ->with('form_url', route('action.update', [$id]))
            ->with('form_title', trans('action.actions.edit'))
            ->with('item', $item)
            ->with('roles', $roles)
            ->with('stocks', $stocks)
            ->with('action_roles', $action_roles);
    }
    
    /**
     * @param \App\Http\Requests\Action\UpdateActionRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(UpdateActionRequest $request, $id)
    {
        $input = $request->only([
            'name',
            'started_at',
            'finished_at',
            'qty',
            'status',
        ]);
        $input['finished_at'] .= ' 23:59:59';
        $input['free_delivery'] = $request->has('free_delivery') ? 1 : 0;
    
        if ($request->hasFile('photo')) {
            $input['photo'] = $this->upload('photo', $input['name'], config('picture.action_path'), $request);
            $this->makePhotoThumbs(config('picture.action_path') , $input['photo'], config('picture.action_thumbs'), 0);
        }
    
        if ($request->hasFile('presentation')) {
            $input['presentation'] = $this->uploadFile('presentation', $input['name'].'-presentation', config('file.action.path'), $request);
        }
    
        if ($request->hasFile('technical_sheet')) {
            $input['technical_sheet'] = $this->uploadFile('technical_sheet', $input['name'].'-technical_sheet', config('file.action.path'), $request);
        }
    
        $action = $this->action->edit($id, $input);
    
        $roles = $request->get('roles', []);
        $action->rRoles()->sync($roles);
    
        return $this->getUpdateJsonResponse($action->fresh(['rType', 'rStatus']), 'action._row', trans('action.notifications.updated'));
    }
    
    /**
     * @return array
     */
    private function getRoles()
    {
        $role = new Role();
        $role->statusId = 'active';
        $role->limit = null;
        
        return $role->getAll()->filter(function($role) {
            return in_array($role->id, scopedAction()->rolesWithAccess());
        })->pluck('label', 'id')->toArray();
    }
    
    /**
     * @return array
     */
    private function getStocks()
    {
        $stock = new Stock();
        $stock->statusId = 'active';
        $stock->limit = null;
        
        return $stock->getAll()->pluck('name', 'id')->toArray();
    }
    
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        $exclude = explode('.', request('e', ''));
        $this->action->keywords = request('q');
        
        $items = $this->action->getAll()->reject(function ($item, $key) use ($exclude) {
            return in_array($item->id, $exclude);
        })->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
                'disabled' => false,
            ];
        })->values()->toArray();
    
        return response()->json([
            'items' => $items,
            'total_count' => count($items),
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
