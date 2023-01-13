<?php

namespace App\Http\Controllers\Billing;

use App\Billing;
use App\Http\Controllers\Controller;
use App\Support\Excel\BillingListImport;
use App\Support\Excel\ImportHelper;
use Illuminate\Support\Facades\DB;

/**
 * Class ImportController
 *
 * @package App\Http\Controllers\Billing
 */
class ImportController extends Controller
{
    use ImportHelper;
    
    /**
     * @var \App\Billing
     */
    private $billing;
    
    /**
     * ImportController constructor.
     *
     * @param \App\Billing $billing
     */
    public function __construct(Billing $billing)
    {
        $this->billing = $billing;
    
        $this->middleware('auth');
    }
    
    public function import(BillingListImport $import)
    {
        $fund_sources = $this->getFundSources();
    
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('billings')->truncate();
        
        $import->chunk(200, function($results) use ($fund_sources) {
            if (isset($results[0])) {
                
                $data = [];
                foreach ($results[0] as $row) {
                    $fund_source = $fund_sources[str_slug($row->izvor_sredstava)] ?? null;
                    
                    $data[] = [
                        'country' => $row->drzava,
                        'country_id' => $this->parseCountryId($row->drzava),
                        'fund_source' => $row->izvor_sredstava,
                        'fund_source_id' => $fund_source,
                        'kif' => $row->kif,
                        'payed' => $row->placeno,
                        'date_of_payment' => $row->datum ? $row->datum->toDateString() : null,
                        'person_id' => null,
                    ];
                }
        
                DB::table('billings')->insert($data);
            }
        });
    
        return 'Done';
    }
}
