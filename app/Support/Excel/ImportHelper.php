<?php

namespace App\Support\Excel;

use Illuminate\Support\Facades\DB;

/**
 * Trait ImportHelper
 *
 * @package App\Support\Excel
 */
trait ImportHelper
{
    /**
     * @param array $clientCodes
     * @return array
     */
    protected function getClients(array $clientCodes = [])
    {
        $query = DB::table('clients')->whereNotNull('code');
        
        if (isset($clientCodes[0])) {
            $query->whereIn('code', $clientCodes);
        }
        
        return $query->get([
            'id',
            'code',
            'client_person_id',
            'salesman_person_id',
        ])->groupBy(function($client) {
            return ''.$client->code;
        })->map(function($clients) {
            $client = $clients->sortByDesc('salesman_person_id')->first();
            
            return [
                'id' => $client->id,
                'code' => $client->code,
                'client_person_id' => $client->client_person_id,
                'salesman_person_id' => $client->salesman_person_id,
            ];
        })->toArray();
    }
    
    /**
     * @return array
     */
    protected function getPersons($typeId)
    {
        return DB::table('persons')->where('type_id', $typeId)->whereNotNull('code')->get([
            'id',
            'code',
        ])->pluck('id', 'code')->toArray();
    }
    
    /**
     * @return array
     */
    protected function getFundSources()
    {
        return get_codebook_opts('fund_sources')->keyBy(function($codeBook) {
            return str_slug($codeBook->name);
        })->map(function($codeBook) {
            return $codeBook->code;
        })->toArray();
    }
    
    /**
     * @param string $country
     * @return string|null
     */
    protected function parseCountryId($country)
    {
        $country = strtolower($country);
        
        if ($country == 'ba') {
            return 'bih';
        }
        
        if ($country == 'rs') {
            return 'srb';
        }
        
        return null;
    }
}
