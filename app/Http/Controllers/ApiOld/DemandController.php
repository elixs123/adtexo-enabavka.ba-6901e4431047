<?php

namespace App\Http\Controllers\Api;

use App\Demand;
use App\Http\Controllers\Controller;
use App\Http\Requests\Demand\InsertDemandRequest;
use App\Http\Requests\Demand\StoreDemandRequest;
use App\Http\Resources\Demand\DemandResource;
use App\Support\Excel\ImportHelper;
use Illuminate\Support\Facades\DB;

/**
 * Class DemandController
 *
 * @package App\Http\Controllers\Api
 */
class DemandController extends Controller
{
    use ImportHelper;
    
    /**
     * @var \App\Demand
     */
    private $demand;
    
    /**
     * DemandController constructor.
     *
     * @param \App\Demand $demand
     */
    public function __construct(Demand $demand)
    {
        $this->demand = $demand;
    }
    
    /**
     * @param \App\Http\Requests\Demand\StoreDemandRequest $request
     * @return \App\Http\Resources\Demand\DemandResource
     */
    public function store(StoreDemandRequest $request)
    {
        $clients = $this->getClients();
    
        $persons = $this->getPersons('salesman_person');
        
        $attributes = $request->only([
            'country',
            'kif',
            'binding_document',
            'document',
            'salesman_person',
            'client',
            'date_of_document',
            'date_of_payment',
            'amount',
            'payed',
            'debt',
            'overdue_days',
        ]);
        $attributes['client_id'] = $clients[$attributes['client']]['id'] ?? null;
        $attributes['person_id'] = $persons[$attributes['salesman_person']] ?? null;
        $attributes['document_id'] = $attributes['document'] ? (int) $attributes['document'] : null;
        
        $item = $this->demand->add($attributes);
        
        return new DemandResource($item);
    }
    
    /**
     * @param int $id
     * @return \App\Http\Resources\Demand\DemandResource
     */
    public function show($id)
    {
        $item = $this->demand->getOne($id);
        abort_if(is_null($item), 404);
        
        return new DemandResource($item);
    }
    
    /**
     * @param \App\Http\Requests\Demand\InsertDemandRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function insert(InsertDemandRequest $request)
    {
        $clients = $this->getClients();
    
        $persons = $this->getPersons('salesman_person');
    
        $demands = $request->get('demands', []);
    
        $now = now()->toDateTimeString();
    
        $demands = array_map(function ($demand) use ($clients, $persons, $now) {
            $demand['client_id'] = $clients[$demand['client']]['id'] ?? null;
            $demand['person_id'] = $persons[$demand['salesman_person']] ?? null;
            $demand['document_id'] = $demand['document'] ? (int) $demand['document'] : null;;
            $demand['created_at'] = $now;
            $demand['updated_at'] = $now;
        
            return $demand;
        }, $demands);
    
        $this->dbTransaction(function () use ($demands) {
            foreach (array_chunk($demands, 100) as $data) {
                DB::table('demands')->insert($data);
            }
        });
    
        return $this->getSuccessJsonResponse($demands);
    }
}
