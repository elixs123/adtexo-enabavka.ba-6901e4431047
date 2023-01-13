<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\PhotoHelper;
use App\Role;
use App\User;
use Illuminate\Support\Facades\Cookie;

/**
 * Class UserController
 *
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
	use PhotoHelper;

    /**
     * @var \App\User
     */
    private $user;
    
    /**
     * @var \App\Role
     */
    private $role;
    
    /**
     * UserController constructor.
     *
     * @param \App\User $user
     * @param \App\Role $role
     */
    public function __construct(User $user, Role $role)
    {
        $this->user = $user;
        $this->role = $role;
		
        $this->middleware('auth');
        $this->middleware('acl:view-user', ['only' => ['index']]);
        $this->middleware('acl:create-user', ['only' => ['create', 'store']]);
        $this->middleware('acl:edit-user', ['only' => ['edit', 'update']]);
        $this->middleware('acl:login-as', ['only' => ['loginAs']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->user->paginate = true;
        $this->user->statusId = request('status');
        $this->user->keywords = request('keywords');
        $this->user->roleName = request('role');
        $items = $this->user->relation(['rStatus'])->getAll();
    
        $roles = $this->role->getAll();
        
        return view('user.index')
                ->with('items', $items)
                ->with('roles', $roles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $roles = $this->role->getAll()->sortBy('id');
        
        return view('user.form')
                ->with('roles', $roles)
                ->with('item', $this->user)
                ->with('method', 'post')
                ->with('form_url', route('user.store'))
                ->with('form_title', trans('user.actions.create'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\User\StoreUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(StoreUserRequest $request)
    {
        // Get form data
        $input = $request->except(['_token', '_method', 'password_confirmation', 'role_id', 'photo']);
        $input['password'] = bcrypt($input['password']);
		
        $photo = $this->upload('photo', auth()->id(), config('picture.user_path'), $request);
        
        if($photo != null)
        {
            $input['photo'] = basename($photo);
            
            $this->makePhotoThumbs(config('picture.user_path') , $photo, config('picture.user_thumbs'), 0);
        }
        
        // Save user
        $user = $this->user->add($input);
        
        // Assign roles to user
        $user->giveRoleTo((array) $request->get('role_id'));
        
        return $this->getStoreJsonResponse($user->fresh('roles'), 'user._row', trans('user.notifications.created'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $item = $this->user->getOne($id);
        $roles = $this->role->getAll();
        $current_user_roles = $item->roles;
		
        return view('user.form')
                ->with('method', 'put')
                ->with('form_url', route('user.update', [$id]))
                ->with('form_title', trans('user.actions.edit'))
                ->with('roles', $roles)
                ->with('current_user_roles', $current_user_roles)
                ->with('item', $item);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\User\UpdateUserRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $input = $request->except(['_token', '_method', 'password_confirmation', 'role_id', 'photo']);
        
        if($input['password'] != '')
        {
            $input['password'] = bcrypt($input['password']);
        }
        else
        {
            unset($input['password']);
        }
		
        $photo = $this->upload('photo', auth()->id(), config('picture.user_path'), $request);
        
        if($photo != null)
        {
            $input['photo'] = basename($photo);
            
            $this->makePhotoThumbs(config('picture.user_path') , $photo, config('picture.user_thumbs'), 0);
        }
        
        // Change user data
        $user = $this->user->edit($id, $input);
        
        // Sync user roles
        $user->giveRoleTo((array) $request->get('role_id'));
        
        return $this->getUpdateJsonResponse($user->fresh('roles'), 'user._row', trans('user.notifications.updated'));
        
    }

    /**
     * Login as any user
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function loginAs($id)
    {
        // Remember real user id
        request()->session()->put('real_user_id', auth()->id());
        
        $user = $this->user->getOne($id);
    
        if (!is_null($user->client) && $user->isClient() && !is_null($lang_id = $user->client->lang_id)) {
            $lang_id = $user->client->lang_id;
    
            app()->setLocale($lang_id);
    
            Cookie::queue(Cookie::make('lang_id', $lang_id, 525600));
        }
        
        auth()->loginUsingId($id);
        
        return redirect('/');
    }
    
    /**
     * Login back as original user
     *
     * @return \Illuminate\Http\Response
     */
    public function loginAsRealUser()
    {
        // Retrieve and delete real_user_id
        $id = request()->session()->pull('real_user_id');
    
        $lang_id = 'bs';
    
        app()->setLocale($lang_id);
    
        Cookie::queue(Cookie::make('lang_id', $lang_id, 525600));
        
        auth()->loginUsingId($id);
        
        return redirect('/');
    }
}
