<?php

namespace App\Support\Controller;

use App\Route;

/**
 * Trait RouteHelper
 *
 * @package App\Support\Controller
 */
trait RouteHelper
{
    /**
     * @return \App\Route
     */
    protected function getRouteModel()
    {
        return new Route();
    }
    
    /**
     * @param \Carbon\Carbon $date
     * @param int $weekId
     * @return array
     */
    protected function getUserRoutes($date, $weekId)
    {
        $route = $this->getRouteModel();
        $route->personId = $this->getUser()->rPerson->id;
        
        $startOfWeek = $date->copy()->startOfWeek();
        // $weekId = $startOfWeek->weekOfMonth;
    
        $client_location_types = get_codebook_opts('client_location_types')->pluck('name', 'code')->toArray();
        
        $routes = [];
        for ($i = 0; $i < 7; $i++) {
            if ($i) {
                $startOfWeek->addDay();
            }
            
            $dayId = strtolower($startOfWeek->shortEnglishDayOfWeek);
            
            if ($dayId == 'sun') {
                continue;
            }
            
            $route->weekId = $weekId;
            $route->dayId = $dayId;
            
            $routes[] = [
                'week' => $weekId,
                'week_real' => 0,
                'day' => $dayId,
                'date' => $startOfWeek->format('d.m.Y'),
                'date_eng' => $startOfWeek->toDateString(),
                'data' => $route->getAll()->reject(function($route) {
                    return $route->rClient->status == 'inactive';
                })->map(function($route) use ($client_location_types) {
                    $route = $route->toArray();
                    $route['client_location_type'] = isset($client_location_types[$route['r_client']['location_type_id']]) ? $client_location_types[$route['r_client']['location_type_id']] : '';
                    
                    return $route;
                })->toArray(),
            ];
        }
        
        return $routes;
    }
}
