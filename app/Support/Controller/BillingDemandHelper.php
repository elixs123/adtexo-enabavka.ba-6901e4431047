<?php

namespace App\Support\Controller;

use Illuminate\Support\Facades\DB;

/**
 * Trait BillingDemandHelper
 *
 * @package App\Support\Controller
 */
trait BillingDemandHelper
{
    /**
     * @param string $country
     * @param string $dateStart
     * @param string $dateEnd
     * @return \Illuminate\Support\Collection
     */
    protected function getBillingsData($country, $dateStart, $dateEnd, $salesmanId = null)
    {
        $query = DB::table('billings')
            ->join('demands', 'demands.kif', '=', 'billings.kif')
            ->join('code_books', 'code_books.code', '=', 'billings.fund_source_id')
            ->join('clients', 'clients.id', '=', 'demands.client_id')
            ->where('billings.country_id', $country)
            ->whereBetween('billings.date_of_payment', [$dateStart, $dateEnd]);
        
        if (is_numeric($salesmanId)) {
            $query->where('demands.person_id', $salesmanId);
        }
        
        return $query->select(DB::raw('
                billings.*,
                code_books.name AS fund_source_name,
                demands.id AS demand_id,
                demands.document_id AS demand_document_id,
                demands.person_id AS demand_person_id,
                demands.date_of_payment AS demand_date_of_payment,
                clients.name as client_name,
                clients.code as client_code,
                DATEDIFF(billings.date_of_payment, demands.date_of_payment) AS overdue_num
            '))
            ->get();
    }
    
    /**
     * @param \Illuminate\Support\Collection $billings
     * @param \Illuminate\Support\Collection $billingsYesterday
     * @return array
     */
    protected function getBillingsPerFundSource($billings, $billingsYesterday)
    {
        $billings = $billings->groupBy('fund_source_id')->map(function ($billings) {
            return [
                'fund_source_name' => $billings->first()->fund_source_name,
                'payed' => $billings->sum('payed'),
            ];
        })->toArray();
        
        $billingsYesterday = $billingsYesterday->groupBy('fund_source_id')->map(function ($billings) {
            return [
                'fund_source_name' => $billings->first()->fund_source_name,
                'payed' => $billings->sum('payed'),
            ];
        })->toArray();
        
        $keys = array_unique(array_merge(array_keys($billings), array_keys($billingsYesterday)));
        
        $data = [];
        foreach ($keys as $key) {
            $data[$key] = [
                'title' => $billings[$key]['fund_source_name'] ?? 'none',
                'period' => $billings[$key]['payed'] ?? 0,
                'yesterday' => $billingsYesterday[$key]['payed'] ?? 0,
            ];
        }
        
        return $data;
    }
    
    /**
     * @param \Illuminate\Support\Collection $billings
     * @param \Illuminate\Support\Collection $billingsYesterday
     * @return array
     */
    protected function getBillingsPerOverduePeriod($billings, $billingsYesterday)
    {
        $billingsYesterday = $billingsYesterday->groupBy(function($billing) {
            return $this->getOverdueKey($billing->overdue_num);
        })->map(function($billings) {
            return $billings->sum('payed');
        })->toArray();
        
        
        return $billings->groupBy(function($billing) {
            return $this->getOverdueKey($billing->overdue_num);
        })->map(function($billings, $key) use ($billingsYesterday) {
            return [
                'period' => $billings->sum('payed'),
                'yesterday' => $billingsYesterday[$key] ?? 0,
            ];
        })->toArray();
    }
    
    /**
     * @param int $overdueNum
     * @return string
     */
    protected function getOverdueKey($overdueNum)
    {
        if ($overdueNum < 0) {
            return 'in_currency';
        }
    
        if ($overdueNum <= 15) {
            return 'in_15_days';
        }
    
        if ($overdueNum <= 30) {
            return 'over_15_days';
        }
    
        if ($overdueNum <= 45) {
            return 'over_30_days';
        }
    
        if ($overdueNum <= 60) {
            return 'over_45_days';
        }
    
        return 'over_60_days';
    }
    
    /**
     * @param \Illuminate\Support\Collection $billings
     * @return array
     */
    protected function getBillingsPerClient($billings)
    {
        return $billings->groupBy('client_code')->map(function($billings) {
            return [
                'client_name' => $billings->first()->client_name,
                'overdue' => $billings->groupBy(function($billing) {
                    return $this->getOverdueKey($billing->overdue_num);
                })->map(function($billings) {
                    return $billings->sum('payed');
                })->toArray(),
            ];
        })->toArray();
    }
    
    /**
     * @param \Illuminate\Support\Collection $billings
     * @return array
     */
    protected function getBillingsPerDocument($billings)
    {
        $demand_ids = $billings->pluck('demand_id')->unique()->toArray();
        
        $demands = DB::table('demands')->whereIn('id', $demand_ids)->whereNotNull('document_id')->get([
            'id',
            'kif',
            'document_id',
        ])->sortBy('document_id')->values();
    
        $billings_per_document = $billings->reject(function($billing) {
            return is_null($billing->demand_document_id);
        })->filter(function($billing) {
            return (int) $billing->demand_document_id > 0;
        })->groupBy('demand_document_id');
        
        $data = [];
        foreach ($demands as $demand) {
            if ($billings_per_document->has($demand->document_id)) {
                $data[$demand->document_id] = [];
                
                foreach ($billings_per_document[$demand->document_id] as $billing) {
                    $data[$demand->document_id][] = [
                        'kif' => $billing->kif,
                        'overdue_key' => $this->getOverdueKey($billing->overdue_num),
                        'payed' => (float) $billing->payed,
                    ];
                }
            }
        }
        
        return $data;
    }
    
    /**
     * @param string $country
     * @return array
     */
    protected function getSalesmanPersonsPerCountry($country)
    {
        return DB::table('persons')
            ->join('stocks', 'stocks.id', '=', 'persons.stock_id')
            ->where('persons.type_id', 'salesman_person')
            ->where('stocks.country_id', $country)
            ->get([
                'persons.id',
                'persons.name',
            ])->pluck('name', 'id')->prepend('Svi komercijalisti', '')->toArray();
    }
}
