<?php

namespace App\Http\Controllers\Route;

use App\Http\Controllers\RouteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class PersonController
 *
 * @package App\Http\Controllers\Route
 */
class PersonController extends RouteController
{
    /**
     * Index.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($id)
    {
        $person = $this->getPerson($id);
    
        $this->route->personId = $id;
        $routes = $this->route->getAll()->reject(function($route) {
            return $route->rClient->status == 'inactive';
        })->groupBy('week_day');
    
        return view('route.person.index')->with([
            'item' => $person,
            'routes' => $routes,
        ]);
    }
    
    /**
     * Detail.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function details($id)
    {
        $person = $this->getPerson($id);
    
        $week_id = request('week', 0);
        $day_id = request('day', '');
    
        $this->route->personId = $id;
        $this->route->weekId = $week_id;
        $this->route->dayId = $day_id;
        $routes = $this->route->getAll()->reject(function($route) {
            return $route->rClient->status == 'inactive';
        });
    
        return view('route.person.detail')->with([
            'item' => $person,
            'routes' => $routes,
            'week' => $week_id,
            'day' => $day_id,
        ]);
    }
    
    /**
     * Update.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update(Request $request, $id)
    {
        $person = $this->getPerson($id);
        
        $input = $request->only(['week_id', 'day_id', 'ranks']);
        
        foreach ($input['ranks'] as $key => $client_id) {
            DB::table($this->route->getTable())->where([
                'person_id' => $id,
                'client_id' => (int) $client_id,
                'week' => (int) $input['week_id'],
                'day' => $input['day_id'],
            ])->update([
                'rank' => (int) $key + 1,
                'updated_at' => now(),
            ]);
        }
        
        return $this->getUpdateJsonResponse($person, null, trans('route.notifications.updated'));
    }
    
    /**
     * Assign.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function assign(Request $request, $id)
    {
        $person = $this->getPerson($id);
        
        $input = $request->only(['week_id', 'day_id', 'client_id', 'rank']);
        
        $client = $this->client->getOne($input['client_id']);
        
        if (is_null($client)) {
            abort(404);
        }
        
        $route = $this->route->add([
            'person_id' => $id,
            'client_id' => (int) $input['client_id'],
            'week' => (int) $input['week_id'],
            'day' => $input['day_id'],
            'rank' => (int) $input['rank'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $this->route->personId = $id;
        $this->route->weekId = (int) $input['week_id'];
        $this->route->dayId = $input['day_id'];
        $count = $this->route->getAll()->reject(function($route) {
            return $route->rClient->status == 'inactive';
        })->count();
    
        return $this->getUpdateJsonResponse($person, null, trans('route.notifications.assigned'), [
            'route' => [
                'uid' => $route->uid,
                'full_name' => $client->full_name,
                'client_id' => (int) $input['client_id'],
                'rank' => (int) $input['rank'],
                'action' => route('route.destroy', [$route->id, 'week' => $input['week_id'], 'day' => $input['day_id']]),
                'td' => '<a class="badge badge-info" data-toggle="modal" data-target="#form-modal2" title="'.trans('skeleton.view_details').'" href="'.route('route.person.details', [$id, 'week' => $input['week_id'], 'day' => $input['day_id'], 'callback' => 'resetPersonRoutes']).'" data-tooltip>'.trans_choice('route.data.clients_num', $count, ['num' => $count]).'</a>',
            ],
        ]);
    }
    
    /**
     * Get person.
     *
     * @param $id
     * @return \App\Person
     */
    private function getPerson($id)
    {
        $person = $this->person->getOne($id);
    
        if (is_null($person)) {
            abort(404);
        }
        
        return $person;
    }
}
