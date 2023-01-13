<?php

namespace App\Http\Controllers\Demand;

use App\Demand;
use App\Http\Controllers\Controller;
use App\Support\Excel\DemandListsImport;
use App\Support\Excel\ImportHelper;
use Illuminate\Support\Facades\DB;

/**
 * Class ImportController
 *
 * @package App\Http\Controllers\Demand
 */
class ImportController extends Controller
{
    use ImportHelper;
    
    /**
     * @var \App\Demand
     */
    private $demand;
    
    /**
     * ImportController constructor.
     *
     * @param \App\Demand $demand
     */
    public function __construct(Demand $demand)
    {
        $this->demand = $demand;
    
        $this->middleware('auth');
    }
    
    public function import(DemandListsImport $import)
    {
        $clients = $this->getClients();
        
        $persons = $this->getPersons('salesman_person');
    
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('demands')->truncate();
        
        $import->chunk(200, function($results) use ($clients, $persons) {
            if (isset($results[0])) {
                $data = [];
                foreach ($results[0] as $row) {
                    $client = $clients[$row->kupac] ?? [];
                    $person = $persons[$row->komercijalista] ?? null;
                    
                    $data[] = [
                        'country' => $row->drzava,
                        'country_id' => $this->parseCountryId($row->drzava),
                        'kif' => $row->kif,
                        'binding_document' => $row->vezni_dokument,
                        'document' => $row->brnaloga,
                        'document_id' => $row->brnaloga ? (int) $row->brnaloga : null,
                        'salesman_person' => $row->komercijalista,
                        'person_id' => $person ?: array_get($client, 'salesman_person_id', null),
                        'client' => $row->kupac,
                        'client_id' => array_get($client, 'id', null),
                        'date_of_document' => $row->datum_racuna->toDateString(),
                        'date_of_payment' => $row->datum_valute->toDateString(),
                        'amount' => $row->iznos,
                        'payed' => $row->placeno,
                        'debt' => $row->preostalidug,
                        'overdue_days' => (int) $row->danaprekovalute,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
        
                DB::table('demands')->insert($data);
            }
        });
        
        return 'Done';
    }
}
