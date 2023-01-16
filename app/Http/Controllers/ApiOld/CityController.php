<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\City\StoreCityRequest;
use App\Http\Requests\City\UpdateCityRequest;
use App\Http\Resources\City\CityCollection;
use App\Http\Resources\City\CityResource;
use App\City;
use Illuminate\Http\Request;

/**
 * Class CityController
 *
 * @package App\Http\Controllers\Api
 */
class CityController extends Controller
{
    /**
     * @var \App\City
     */
    private $city;
    
    /**
     * CityController constructor.
     *
     * @param \App\City $city
     */
    public function __construct(City $city)
    {
        $this->city = $city;
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\City\CityCollection
     */
    public function index(Request $request)
    {
        $this->city->paginate = true;
        $this->city->limit = $request->get('limit', 100);
        $this->city->countryId = $request->get('country_id');
        $this->city->statusId = $request->get('status_id');
        $this->city->keywords = $request->get('keywords');
        $items = $this->city->relation(['rCountry'])->getAll();
        
        return new CityCollection($items);
    }
    
    /**
     * @param integer $id
     * @return \App\Http\Resources\City\CityResource
     */
    public function show($id)
    {
        $city = $this->city->getOne($id);
        abort_if(is_null($city), 404);
        
        return new CityResource($city);
    }
}
