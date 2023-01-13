<?php

namespace App\Http\Controllers;

use App\Client;
use App\Person;
use App\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class RouteController
 *
 * @package App\Http\Controllers
 */
class RouteController extends Controller
{
    /**
     * @var \App\Route
     */
    protected $route;
    
    /**
     * @var \App\Client
     */
    protected $client;
    
    /**
     * @var \App\Person
     */
    protected $person;
    
    /**
     * RouteController constructor.
     *
     * @param \App\Route $route
     * @param \App\Client $client
     * @param \App\Person $person
     */
    public function __construct(Route $route, Client $client, Person $person)
    {
        $this->route = $route;
        $this->client = $client;
        $this->person = $person;
    
        $this->middleware('auth');
        $this->middleware('acl:view-route', ['only' => ['index', 'rank', 'details']]);
        $this->middleware('acl:create-route', ['only' => ['create', 'store']]);
        $this->middleware('acl:edit-route', ['only' => ['edit', 'update']]);
    }
    
    /**
     * Rank.
     *
     * @return \Illuminate\View\View
     */
    public function rank()
    {
        $this->route->personId = request('person_id', 0);
        $this->route->clientId = request('client_id', 0);
        $items = $this->route->getAll();
    
        return view('route.rank')->with([
            'routes' => $items,
        ]);
    }
    
    /**
     * Destroy.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function destroy($id)
    {
        $item = $this->route->getOne($id);
        
        if (is_null($item)) {
            abort(404);
        }
        
        $this->route->remove($id);
    
        $this->route->personId = $item->person_id;
        $this->route->weekId = $week_id = (int) request('week', 0);
        $this->route->dayId = $day_id = request('day', '');
        $count = $this->route->getAll()->reject(function($route) {
            return $route->rClient->status == 'inactive';
        })->count();
        
        return $this->getDestroyJsonResponse($item, null, trans('route.notifications.deleted'), [
            'route' => [
                'td' => $count ? '<a class="badge badge-info" data-toggle="modal" data-target="#form-modal2" title="'.trans('skeleton.view_details').'" href="'.route('route.person.details', [ $item->person_id, 'week' => $week_id, 'day' => $day_id]).'" data-tooltip>'.trans_choice('route.data.clients_num', $count, ['num' => $count]).'</a>' : '<a class="d-block" data-toggle="modal" data-target="#form-modal2" title="'.trans('route.actions.assign').'" href="'.route('route.person.details', [ $item->person_id, 'week' => $week_id, 'day' => $day_id]).'" data-tooltip><span class="feather icon-plus"></span></a>',
                'count' => $count,
            ],
        ]);
    }
}
