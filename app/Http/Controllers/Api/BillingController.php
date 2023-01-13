<?php

namespace App\Http\Controllers\Api;

use App\Billing;
use App\Http\Controllers\Controller;
use App\Http\Requests\Billing\InsertBillingRequest;
use App\Http\Requests\Billing\StoreBillingRequest;
use App\Http\Resources\Billing\BillingResource;
use App\Support\Excel\ImportHelper;
use Illuminate\Support\Facades\DB;

/**
 * Class BillingController
 *
 * @package App\Http\Controllers\Api
 */
class BillingController extends Controller
{
    use ImportHelper;
    
    /**
     * @var \App\Billing
     */
    private $billing;
    
    /**
     * BillingController constructor.
     *
     * @param \App\Billing $billing
     */
    public function __construct(Billing $billing)
    {
        $this->billing = $billing;
    }
    
    /**
     * @param \App\Http\Requests\Billing\StoreBillingRequest $request
     * @return \App\Http\Resources\Billing\BillingResource
     */
    public function store(StoreBillingRequest $request)
    {
        $fund_sources = $this->getFundSources();
        
        $attributes = $request->only([
            'country',
            'fund_source',
            'kif',
            'payed',
            'date_of_payment',
            'person_id',
        ]);
        $attributes['country_id'] = $this->parseCountryId($attributes['country']);
        $attributes['fund_source_id'] = $fund_sources[str_slug($attributes['fund_source'])] ?? null;
        
        $item = $this->billing->add($attributes);
        
        return new BillingResource($item);
    }
    
    /**
     * @param int $id
     * @return \App\Http\Resources\Billing\BillingResource
     */
    public function show($id)
    {
        $item = $this->billing->getOne($id);
        abort_if(is_null($item), 404);
    
        return new BillingResource($item);
    }
    
    /**
     * @param \App\Http\Requests\Billing\InsertBillingRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function insert(InsertBillingRequest $request)
    {
        $fund_sources = $this->getFundSources();
        
        $billings = $request->get('billings', []);
        
        $now = now()->toDateTimeString();
        
        $billings = array_map(function ($billing) use ($fund_sources, $now) {
            $billing['country_id'] = $this->parseCountryId($billing['country']);
            $billing['fund_source_id'] = $fund_sources[str_slug($billing['fund_source'])] ?? null;
            $billing['created_at'] = $now;
            $billing['updated_at'] = $now;
            
            return $billing;
        }, $billings);
        
        $this->dbTransaction(function () use ($billings) {
            foreach (array_chunk($billings, 100) as $data) {
                DB::table('billings')->insert($data);
            }
        });
        
        return $this->getSuccessJsonResponse($billings);
    }
}
