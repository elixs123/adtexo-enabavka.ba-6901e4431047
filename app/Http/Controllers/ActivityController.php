<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Activity;
use App\User;

class ActivityController extends Controller
{
    
    /**
     * @var \App\Activity model
     */
    protected $activity;
    
    /**
     * @var \App\User model
     */
    protected $user;      
    
    public function __construct(
            Activity $activity, 
            User $user         
        )
    {
        $this->activity = $activity;
        $this->user = $user; 
        
		$this->middleware('auth');
		$this->middleware('xss');
        //$this->middleware('acl:view-activity', ['only' => ['index']]);               
    }     

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {                
		$users = $this->user->getAll();
	
        $this->activity->paginate = true;
        $this->activity->limit = 100;
		$this->activity->userId = request('user_id');	
        $this->activity->dateStart = request('date_start', date('Y-m-d', strtotime('-30 days')));
        $this->activity->dateEnd = request('date_end', date('Y-m-d'));		
        $items = $this->activity->getAll();
		                        
        return view('activity.index')->with('items', $items)->with('users', $users);
    }

}