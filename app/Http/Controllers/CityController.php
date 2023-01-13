<?php

namespace App\Http\Controllers;

use App\City;

/**
 * ClassCityController
 *
 * @package App\Http\Controllers
 */
class CityController extends Controller
{
    /**
     * @var City model
     */
    private $city;
    
    /**
     *CityController constructor.
     *
     * @param \App\City $city
     */
    public function __construct(City $city)
    {
        $this->city = $city;
        
        $this->middleware('auth');
        $this->middleware('emptystringstonull', ['only' => ['store', 'update']]);
    }
    
    /**
     * Search.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        $this->city->limit = null;
        $this->city->keywords = request('q');
        $this->city->countryId = request('c');

        $items = $this->city->getAll()->map(function ($item, $key) {
            return [
                'id' => $item->name,
                'city_id' => $item->id,
                'text' => $item->name . ' ('. $item->postal_code .')',
                'postal_code' => $item->postal_code,
                'disabled' => false,
            ];
        })->values()->toArray();
        
        return response()->json([
            'items' => $items,
            'total_count' => count($items),
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
