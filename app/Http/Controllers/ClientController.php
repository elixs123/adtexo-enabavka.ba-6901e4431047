<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Person;
use App\PhotoHelper;
use App\ProductCategory as Category;
use App\Route;
use App\Support\Controller\ClientHelper;
use App\User;

/**
 * Class ClientController
 *
 * @package App\Http\Controllers
 */
class ClientController extends Controller
{
    use ClientHelper, PhotoHelper;
    
    /**
     * @var Client model
     */
    private $client;
    
    /**
     * @var \App\Route
     */
    private $route;
    
    /**
     * @var \App\Category
     */
    private $category;
    
    /**
     * @var \App\Person
     */
    private $person;
    
    /**
     * ClientController constructor.
     *
     * @param \App\Client $client
     * @param \App\Route $route
     * @param \App\ProductCategory $category
     * @param \App\Person $person
     */
    public function __construct(Client $client, Route $route, Category $category, Person $person)
    {
        $this->client = $client;
        $this->route = $route;
        $this->category = $category;
        $this->person = $person;
        
        $this->middleware('auth');
        $this->middleware('acl:view-client', ['only' => ['index']]);
        $this->middleware('acl:create-client', ['only' => ['create', 'store']]);
        $this->middleware('acl:edit-client', ['only' => ['edit', 'update']]);
        $this->middleware('emptystringstonull', ['only' => ['store', 'update']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parent_id = request('parent_id');
        $keywords = request()->get('keywords');
        $countryId = request()->get('country_id');
        $export = request()->get('export', false);
        
        if ($export != false) {
            $this->client->relation([
                'rType',
                'rStatus',
                'rCountry',
                'rLocationType',
                'rCategory',
                'rPaymentTherms',
                'rPaymentPeriod',
                'rPaymentType',
                'rStock',
                'rSalesmanPerson',
                'rResponsiblePerson',
                'rPaymentPerson',
                'rSupervisorPerson',
                'rRoutes',
            ]);
        } else {
            $this->client->relation([
                'rType',
                'rStatus',
                'rResponsiblePerson',
            ]);
        }
    
        if (userIsAdmin()) {
            $person_id = request('person_id');
            
            $this->client->personType = request('person_type');
            $this->client->personId = $person_id;
            
            $person = is_null($person_id) ? null : $this->person->getOne($person_id);
        } else {
            $person = null;
        }
        
        $this->client->parentId = $parent_id ? $parent_id : null;
        $this->client->keywords = $keywords;
        $this->client->countryId = $countryId;
        $this->client->typeId = request('type_id');
        $this->client->paymentTypeId = request('payment_type');
        $this->client->onlyLocations = userIsSalesman();
        $this->client->includeIds = (userIsSalesman()) ? $this->getUser()->rPerson->rClients->pluck('id')->unique()->all() : null;
        $this->client->statusId = (userIsSalesman()) ? ['active', 'pending'] : request('status');
        $this->client->limit = ($export != false) ? null : 25;
        $this->client->paginate = ($export != false) ? false : true;
        if (userIsFocuser()) {
            $this->client->personType = 'salesman_person';
            $this->client->personId = $this->getUser()->rPerson->id;
        }
        
        $items = $this->client->getAll();
    
        if ($export == 'xls') {
            return $this->exportToExcel($items, request('type_id'));
        }
        
        $types = $this->client->getTypes();
        
        $parent = is_null($parent_id) ? null : $this->client->getOne($parent_id);
        
        return view('client.index')->with([
            'items' => $items,
            'types' => $types,
            'parent_id' => $this->client->parentId,
            'parent' => $parent,
            'person' => $person,
        ]);
    }
    
    /**
     * @param \Illuminate\Database\Eloquent\Collection $items
     * @return \Illuminate\Http\Response
     */
    private function exportToExcel($items, $typeId = 'business_client')
    {
        $items = $items->filter(function ($item) {
            return $item->is_location;
        });
        
        $columns = trans('client.data');
        foreach (['code', 'name', 'is_location', 'photo', 'map', 'categories', 'products', 'lang_id', 'discount_value2', 'responsible_person_id', 'payment_person_id'] as $key) {
            array_forget($columns, $key);
        }
       
        // return view('client.export_xls')->with('items', $items)->with('columns', $columns);
      
        return \Excel::create(trans('client.title').' '.now()->format('YmdHis'), function($excel) use ($items, $columns, $typeId) {
            $excel->sheet('Sheet 1', function($sheet) use ($items, $columns, $typeId) {
				$viewName = $typeId == 'private_client' ? 'client.export_xls_private_client' : 'client.export_xls';
                $sheet->loadView($viewName)->with('items', $items)->with('columns', $columns);
            });
        })->download('xls');
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $parent_id = request('parent_id');
        $parent = is_null($parent_id) ? $this->client : $this->client->getOne($parent_id);
    
        $routes = userIsSalesman() ? [] : null;
    
        $no_other_locations = is_null($parent_id);
		      
        return view('client.form')->with([
            'item' => $this->client,
            'method' => 'post',
            'form_url' => route('client.store'),
            'form_title' => is_null($parent_id) ? trans('client.actions.create') : trans('client.actions.create_location'),
            'parent_id' => $parent_id,
            'parent' => $parent->toArray(),
            'person_types' => $this->getClientPersonTypes(),
            'routes' => $routes,
            'categories' => $this->category->getCategoryTree()->pluck('name_length', 'id')->toArray(),
            'categories_selected' => is_null($parent_id) ? [] : $parent->rCategories->pluck('id')->toArray(),
            'products_selected' => is_null($parent_id) ? [] : $this->getClientProducts($parent),
            'actions_selected' => is_null($parent_id) ? [] : $this->getClientActions($parent),
            'stocks' => $this->getStocks(),
            'no_other_locations' => $no_other_locations,
            'callback' => request('callback'),
        ]);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param StoreClientRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(StoreClientRequest $request)
    {
        $input = $request->only($this->requestData());
        $input['is_location'] = $request->get('is_location', 0);
        $input['photo'] = $this->upload('photo', auth()->id(), config('picture.client_path'), $request);
        $input['photo_contract'] = $this->upload('photo_contract', 'contract_' . auth()->id(), config('picture.client_path'), $request);
		
        if (!$input['is_location']) {
            $input['salesman_person_id'] = null;
        }
        
        if (!is_null($input['photo'])) {
            $this->makePhotoVarations(config('picture.client_path'), basename($input['photo']), config('picture.client_thumbs'), 0);
        }
		
        if (!is_null($input['photo_contract'])) {
            $this->makePhotoVarations(config('picture.client_path'), basename($input['photo_contract']), config('picture.client_thumbs'), 0);
        }
        
        $client = $this->client->add($input);
    
        if ($client->is_location) {
            $this->syncCategoriesProductsAndActionsWithHeadquarter($client);
            
            $this->syncPersonClientRoutes(userIsSalesman() ? $this->getUser()->rPerson->id : $client->salesman_person_id, $client->id, $request->get('routes', []), $client->parent_id);
        }
    
        if ($client->is_headquarter) {
            $client->rCategories()->sync($categories = $request->get('categories', []));
            $client->rProducts()->sync($products = $request->get('products', []));
            $client->rActions()->sync($actions = $request->get('actions', []));
    
            $this->syncCategoriesProductsAndActionsWithLocations($client, $categories, $products, $actions);
        }
        
        if (!is_null($client->rResponsiblePerson->rUser)) {
            $client->rResponsiblePerson->rUser->giveRoleTo(($client->type_id == 'business_client') ? [2] : [8]);
        }
		      
        return $this->getStoreJsonResponse($client, 'client._row', trans('client.notifications.created'));
    }
    
    /**
     * Show.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $item = $this->client->getOne($id);
        
        if (is_null($item)) {
            abort(404);
        }
    
        $parent_id = $item->parent_id;
        $parent = is_null($parent_id) ? $this->client : $this->client->getOne($parent_id);
    
        if ($item->is_location) {
            $this->route->personId = $item->salesman_person_id;
            $this->route->clientId = $id;
            $routes = $this->route->getAll()->pluck('rank', 'week_day')->toArray();
        } else {
            $routes = null;
        }
    
        return view('client.show')->with([
            'item' => $item,
            'parent_id' => $parent_id,
            'parent' => $parent->toArray(),
            'person_types' => $this->getClientPersonTypes(),
            'routes' => $routes,
            'categories' => $item->rCategories()->with('translation')->get(),
            'products' => $this->getClientProducts($item),
            'actions' => $this->getClientActions($item),
        ]);
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $item = $this->client->getOne($id);
        
        $parent_id = $item->parent_id;
        $parent = is_null($parent_id) ? $this->client : $this->client->getOne($parent_id);
        
        if ($item->is_location) {
            $this->route->personId = $item->salesman_person_id;
            $this->route->clientId = $id;
            $routes = $this->route->getAll()->pluck('rank', 'week_day')->toArray();
        } else {
            $routes = null;
        }
        
        $no_other_locations = false;
        
        return view('client.form')->with([
            'method' => 'put',
            'form_url' => route('client.update', [$id]),
            'form_title' => is_null($parent_id) ? trans('client.actions.edit') : trans('client.actions.edit_location'),
            'item' => $item,
            'parent_id' => $parent_id,
            'parent' => $parent->toArray(),
            'person_types' => $this->getClientPersonTypes(),
            'routes' => $routes,
            'categories' => $this->category->getCategoryTree()->pluck('name_length', 'id')->toArray(),
            'categories_selected' => $item->rCategories->pluck('id')->toArray(),
            'products_selected' => $this->getClientProducts($item),
            'actions_selected' => $this->getClientActions($item),
            'stocks' => $this->getStocks(),
            'no_other_locations' => $no_other_locations,
            'callback' => request('callback'),
        ]);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param UpdateClientRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(UpdateClientRequest $request, $id)
    {
        $input = $request->only($this->requestData());
        $input['is_location'] = $request->get('is_location', 0);
        $photo = $this->upload('photo', auth()->id(), config('picture.client_path'), $request);
        $photo_contract = $this->upload('photo_contract', 'contract_' . auth()->id(), config('picture.client_path'), $request);
    
        if (!is_null($photo)) {
            $input['photo'] = $photo;
            
            $this->makePhotoVarations(config('picture.client_path'), basename($input['photo']), config('picture.client_thumbs'), 0);
        }
		
        if (!is_null($photo_contract)) {
            $input['photo_contract'] = $photo_contract;
            
            $this->makePhotoVarations(config('picture.client_path'), basename($input['photo_contract']), config('picture.client_thumbs'), 0);
        }
    
        $client = $this->client->getOne($id);
    
        if (!$request->get('is_location', 0)) {
            $input['salesman_person_id'] = null;
    
            $old_person_id = null;
        } else {
            $old_person_id = ($client->salesman_person_id == (int) $input['salesman_person_id']) ? null : $client->salesman_person_id;
        }
        
        $was_location = $client->is_headquarter && $client->is_location && !$request->get('is_location', 0);
        
        $client = $this->client->edit($id, $input);
        
        if ($client->is_location) {
            $this->syncCategoriesProductsAndActionsWithHeadquarter($client);
            
            $this->syncPersonClientRoutes($client->salesman_person_id, $id, $request->get('routes', []), $client->parent_id, $old_person_id);
        } else if ($was_location) {
            $client->update([
                'location_code' => null,
                'location_name' => null,
                'location_type_id' => null,
                'category_id' => null,
            ]);
            
            if (!is_null($request->get('salesman_person_id'))) {
                $this->deleteRoutes((int) $request->get('salesman_person_id'), $id);
            }
        }
    
        if ($client->is_headquarter) {
            $client->rCategories()->sync($categories = $request->get('categories', []));
            $client->rProducts()->sync($products = $request->get('products', []));
            $client->rActions()->sync($actions = $request->get('actions', []));
            
            $this->syncLocationsWithHeadquarter($client, $categories, $products, $actions);
        }
    
        if (!is_null($client->rResponsiblePerson->rUser)) {
            $client->rResponsiblePerson->rUser->giveRoleTo(($client->type_id == 'business_client') ? [2] : [8]);
        }
        
        return $this->getUpdateJsonResponse($client, 'client._row', trans('client.notifications.updated'));
    }
    
    /**
     * Search.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        $exclude = explode('.', request('e', ''));
        
        $this->client->limit = null;
        $this->client->parentId = '';
        $this->client->isLocation = true;
        $this->client->statusId = ['active', 'pending'];
        $this->client->typeId = request('t');
        $this->client->keywords = request('q');
        if (userIsSalesman()) {
            $this->client->includeIds = $this->getUser()->rPerson->rClients->pluck('id')->unique()->all();
        } else if (userIsSupervisor()) {
            $this->client->personType = 'supervisor_person';
            $this->client->personId = $this->getUser()->rPerson->id;
        } else if (userIsEditor() || userIsWarehouse()) {
            $this->client->stockId = $this->getUser()->rPerson->stock_id;
        } else if (userIsFocuser()) {
            $this->client->personType = 'salesman_person';
            $this->client->personId = $this->getUser()->rPerson->id;
        }
        
        $items = $this->client->getAll()->reject(function ($item, $key) use ($exclude) {
            return in_array($item->id, $exclude);
        })->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'text' => $item->full_name,
                'type_id' => $item->type_id,
                'payment_type' => $item->payment_type,
                'payment_period' => $item->payment_period,
                'date_of_payment' => now()->addDays($item->payment_period_in_days)->toDateString(),
                'payment_discount' => format_price($item->payment_discount),
                'discount_value1' => format_price($item->discount_value1),
                'disabled' => false,
            ];
        })->values()->toArray();
        
        return response()->json([
            'items' => $items,
            'total_count' => count($items),
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
