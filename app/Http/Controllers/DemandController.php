<?php

namespace App\Http\Controllers;

use App\Support\Controller\DemandBillingHelper;

/**
 * Class DemandController
 *
 * @package App\Http\Controllers
 */
class DemandController extends Controller
{
    use DemandBillingHelper;
    
    /**
     * DemandController constructor.
     */
    public function __construct()
    {
        //
    }
    
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        return view('demand.index')->with($this->getViewData());
    }
    
    /**
     * @return array
     */
    private function getViewData()
    {
        $data = [];
    
        $data['countries'] = get_codebook_opts('countries')->pluck('name', 'code')->toArray();
    
        $data['query'] = [
            'country' => request('country', 'bih'),
        ];
    
        $data['currency'] = ($data['query']['country'] == 'bih') ? 'KM' : 'RSD';
        
        $data['fund_sources'] = get_codebook_opts('fund_sources')->pluck('name', 'code')->toArray();
    
        $data['demands_per_fund_source'] = $this->getDemandsPerFundSource($data['query']['country']);
    
        $data['demands_by_fund_source'] = [];
        foreach (array_keys($data['demands_per_fund_source']) as $fund_source_key) {
            if ($fund_source_key != 'unknown') {
                $data['demands_by_fund_source'][$fund_source_key] = $this->getDemandsByFundSource($data['query']['country'], $fund_source_key);
            }
        }
        
        return $data;
    }
}
