<?php

namespace App\Http\Controllers;

use App\Person;
use App\Support\Controller\BillingDemandHelper;
use Carbon\Carbon;

/**
 * Class BillingController
 *
 * @package App\Http\Controllers
 */
class BillingController extends Controller
{
    use BillingDemandHelper;
    
    /**
     * BillingController constructor.
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
        return view('billing.index')->with($this->getViewData());
    }
    
    /**
     * @return array
     */
    private function getViewData()
    {
        $data = [];
    
        $data['countries'] = get_codebook_opts('countries')->pluck('name', 'code')->toArray();
    
        $data['dates_data'] = [
            'start_date' => is_null($start = request('start')) ? now()->subDays(30) : Carbon::createFromFormat('Y-m-d', $start),
            'end_date' => is_null($end = request('end')) ? now() : Carbon::createFromFormat('Y-m-d', $end),
        ];
    
        $data['query'] = [
            'start' => request('start', $data['dates_data']['start_date']->toDateString()),
            'end' => request('end', $data['dates_data']['end_date']->toDateString()),
            'country' => request('country', 'bih'),
            'salesman' => request('salesman'),
        ];
        
        if (userIsSalesman()) {
            $data['query']['salesman'] = auth()->user()->rPerson->id;
            $data['persons'] = [];
        } else {
            $data['persons'] = $this->getSalesmanPersonsPerCountry($data['query']['country']);
        }
        
        $data['salesman_person'] = null;
        if (not_null($data['query']['salesman'])) {
            $data['salesman_person'] = Person::find($data['query']['salesman']);
        }
        
        $data['currency'] = ($data['query']['country'] == 'bih') ? 'KM' : 'RSD';
        
        $billings = $this->getBillingsData($data['query']['country'], $data['query']['start'], $data['query']['end'], $data['query']['salesman']);
        $billings_yesterday = $this->getBillingsData($data['query']['country'], now()->subDay()->toDateString(), now()->subDay()->toDateString(), $data['query']['salesman']);
        
        $data['billings_per_fund_source'] = $this->getBillingsPerFundSource($billings, $billings_yesterday);
        
        $data['billings_per_overdue_period'] = $this->getBillingsPerOverduePeriod($billings, $billings_yesterday);
        
        $data['billings_per_client'] = $this->getBillingsPerClient($billings);
        
        $data['billings_per_document'] = $this->getBillingsPerDocument($billings);
        
        return $data;
    }
}
