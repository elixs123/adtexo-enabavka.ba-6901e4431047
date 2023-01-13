<?php

namespace App\Http\Controllers;

use App\Http\Requests\Person\StorePersonRequest;
use App\Http\Requests\Person\UpdatePersonRequest;
use App\Person;
use App\Role;
use App\Stock;
use App\User;
use App\ProductCategory as Category;

/**
 * Class PersonController
 *
 * @package App\Http\Controllers
 */
class PersonController extends Controller
{
    /**
     * @var \App\Person
     */
    private $person;
    
    /**
     * @var \App\User
     */
    private $user;
    
    /**
     * @var \App\ProductCategory
     */
    private $category;
    
    /**
     * @var \App\Stock
     */
    private $stock;
    /**
     * @var \App\Role
     */
    private $role;
    
    /**
     * PersonController constructor.
     *
     * @param \App\Person $person
     * @param \App\User $user
     * @param \App\ProductCategory $category
     * @param \App\Stock $stock
     * @param \App\Role $role
     */
    public function __construct(Person $person, User $user, Category $category, Stock $stock, Role $role)
    {
        $this->person = $person;
        $this->user = $user;
        $this->category = $category;
        $this->stock = $stock;
        $this->role = $role;
    
        $this->middleware('auth');
        $this->middleware('acl:view-person', ['only' => ['index']]);
        $this->middleware('acl:create-person', ['only' => ['create', 'store']]);
        $this->middleware('acl:edit-person', ['only' => ['edit', 'update']]);
    }
    
    /**
     * Index.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->person->typeId = request('type_id');
        $this->person->keywords = request('keywords');
        $this->person->paginate = true;
        $this->person->limit = 10;
        if (userIsAdmin()) {
            $this->person->roleName = request('role');
        }
        $items = $this->person->relation(['rType', 'rStatus', 'rUser'])->getAll();
    
        $types = $this->person->getTypes();
    
        $roles = userIsAdmin() ? $this->role->getAll() : collect([]);
    
        return view('person.index')
            ->with('types', $types)
            ->with('roles', $roles)
            ->with('items', $items);
    }
    
    /**
     * Create.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('person.form')
            ->with('item', $this->person)
            ->with('method', 'post')
            ->with('form_url', route('person.store'))
            ->with('form_title', trans('person.actions.create'))
            ->with('types', $this->person->getTypes())
            ->with('categories', $this->category->getCategoryTree()->pluck('name_length', 'id')->toArray())
            ->with('stocks', $this->getStocks())
            ->with('categories_selected', [])
            ->with('products_selected', []);
    }
    
    /**
     * Store.
     *
     * @param \App\Http\Requests\Person\StorePersonRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(StorePersonRequest $request)
    {
        $input = $request->only([
            'name',
            'type_id',
            'email',
            'phone',
            'note',
            'code',
            'stock_id',
            'status',
            'printer_type',
            'printer_receipt_url',
            'printer_access_token'
        ]);
        $input['kpi_values'] = ($input['type_id'] == 'salesman_person') ? array_map(function($value) {return round((float) $value, 2);}, $request->get('kpi_values', [])) : null;
        
        if ((int) $request->get('assign_to_user') == 1) {
            $input['user_id'] = $this->createUser($input['email'], $input['type_id'], $input['status']);
        }
        
        $person = $this->person->add($input);
    
        if ((int) $request->get('invite_user') == 1) {
            $this->sendInviteUserNotification($person->user_id);
        }
        
        if ($input['type_id'] == 'focuser_person') {
            $person->rCategories()->sync($request->get('categories', []));
            $person->rProducts()->sync($request->get('products', []));
        }
    
        return $this->getStoreJsonResponse($person, 'person._row', trans('person.notifications.created'));
    }
    
    /**
     * Edit.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $item = $this->person->getOne($id);
    
        return view('person.form')
            ->with('method', 'put')
            ->with('form_url', route('person.update', [$id]))
            ->with('form_title', trans('person.actions.edit'))
            ->with('item', $item)
            ->with('types', $this->person->getTypes())
            ->with('categories', $this->category->getCategoryTree()->pluck('name_length', 'id')->toArray())
            ->with('stocks', $this->getStocks())
            ->with('categories_selected', $item->rCategories->pluck('id')->toArray())
            ->with('products_selected', $this->getPersonProducts($item));
    }
    
    /**
     * @param mixed $person
     * @return array
     */
    private function getPersonProducts($person)
    {
        return $person->rProducts()->with('translation')->get()->map(function($product, $key) {
            return [
                'id' => $product->id,
                'name' => $product->translation->name,
            ];
        })->toArray();
    }
    
    /**
     * Update.
     *
     * @param \App\Http\Requests\Person\UpdatePersonRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(UpdatePersonRequest $request, $id)
    {
        $input = $request->only([
            'name',
            'type_id',
            'email',
            'phone',
            'note',
            'code',
            'stock_id',
            'status',
            'printer_type',
            'printer_receipt_url',
            'printer_access_token'
        ]);
        $input['kpi_values'] = ($input['type_id'] == 'salesman_person') ? array_map(function($value) {return round((float) $value, 2);}, $request->get('kpi_values', [])) : null;
    
        $person = $this->person->edit($id, $input);
    
        $assign_to_user = (int) $request->get('assign_to_user');
    
        if (($assign_to_user == 1) && is_null($person->user_id)) {
            $person->user_id = $this->createUser($input['email'], $input['type_id'], $input['status']);
            $person->save();
        } else if (($assign_to_user == 1) && !is_null($person->user_id)) {
            $user = $person->rUser;
    
            $user->update([
                'email' => $input['email'],
                'status' => $input['status'],
            ]);
    
            $user->giveRoleTo($this->getRolesByType($input['type_id']));
        } else if (($assign_to_user == 0) && !is_null($person->user_id)) {
            $user = $person->rUser;
    
            $person->user_id = null;
            $person->save();
    
            $user->update([
                'status' => 'inactive',
            ]);
        }
    
        if ((int) $request->get('invite_user') == 1) {
            $this->sendInviteUserNotification($person->user_id);
        }
    
        if ($input['type_id'] == 'focuser_person') {
            $person->rCategories()->sync($request->get('categories', []));
            $person->rProducts()->sync($request->get('products', []));
        } else {
            $person->rCategories()->detach();
            $person->rProducts()->detach();
        }
    
        return $this->getUpdateJsonResponse($person, 'person._row', trans('person.notifications.updated'));
    }
    
    /**
     * Creat user.
     *
     * @param string $email
     * @param string $type
     * @param string $status
     * @return int
     */
    private function createUser($email, $type, $status)
    {
        $user = User::firstOrCreate([
            'email' => $email,
        ], [
            'password' => bcrypt($email.now()->timestamp),
            'status' => $status,
        ]);
    
        $user->giveRoleTo($this->getRolesByType($type));
        
        return $user->id;
    }
    
    /**
     * Get roles by person type.
     *
     * @param string $type
     * @return array
     */
    private function getRolesByType($type)
    {
        switch ($type) {
            case 'administrator_person' :
                $role_id = [1];
                break;
            case 'responsible_person' :
            case 'payment_person' :
                $role_id = [2];
                break;
            case 'salesman_person' :
                $role_id = [3];
                break;
            case 'focuser_person' :
                $role_id = [4];
                break;
            case 'editor_person' :
                $role_id = [5];
                break;
            case 'warehouse_person' :
                $role_id = [6];
                break;
            case 'supervisor_person' :
                $role_id = [7];
                break;
            case 'sales_agent_person' :
                $role_id = [8];
                break;
            default :
                $role_id = [];
        }
        
        return $role_id;
    }
    
    /**
     * Search.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        $exclude = explode('.', request('e', ''));
        $userOnly = request('u', 0);
        
        $this->person->keywords = request('q');
        $this->person->typeId = explode('.', request('t'));
        $this->person->userId = 0;
        $items = $this->person->getAll()->reject(function ($item, $key) use ($exclude, $userOnly) {
            if ($userOnly) {
                return is_null($item->user_id);
            }
            
            return in_array($item->id, $exclude);
        })->map(function($item) use ($userOnly) {
            return [
                'id' => $userOnly ? $item->user_id : $item->id,
                'text' => $item->name,
                'type' => $item->rType->name,
                'disabled' => false,
            ];
        })->values()->toArray();
    
        return response()->json([
            'items' => $items,
            'total_count' => count($items),
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * @return array
     */
    private function getStocks()
    {
        $this->stock->limit = null;
        $this->stock->statusId = 'active';
        
        return $this->stock->getAll()->pluck('name', 'id')->prepend('-', '')->toArray();
    }
    
    /**
     * @param int $userId
     * @return void
     */
    private function sendInviteUserNotification($userId)
    {
        $user = $this->user->getOne($userId);
        
        if (is_null($user)) {
            return;
        }
        
        $token = app('auth.password.broker')->createToken($user);
        
        $user->sendInviteUserNotification($token);
    }
}
