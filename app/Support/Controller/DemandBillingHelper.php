<?php

namespace App\Support\Controller;

use Illuminate\Support\Facades\DB;

/**
 * Trait DemandBillingHelper
 *
 * @package App\Support\Controller
 */
trait DemandBillingHelper
{
    /**
     * @param string $country
     * @return array
     */
    protected function getDemandsPerFundSource($country)
    {
        $result =  DB::select("
select billings.fund_source_id,
SUM(IF(demands.overdue_days <= 0, demands.debt, 0)) as in_currency,
SUM(IF((demands.overdue_days >= -7) AND (demands.overdue_days <= 0), demands.debt, 0)) as in_currency_7_days,
SUM(IF((demands.overdue_days > 0) AND (demands.overdue_days <= 15), demands.debt, 0)) as in_15_days,
SUM(IF((demands.overdue_days > 15) AND (demands.overdue_days <= 30), demands.debt, 0)) as over_15_days,
SUM(IF((demands.overdue_days > 30) AND (demands.overdue_days <= 45), demands.debt, 0)) as over_30_days,
SUM(IF((demands.overdue_days > 45) AND (demands.overdue_days <= 60), demands.debt, 0)) as over_45_days,
SUM(IF(demands.overdue_days > 60, demands.debt, 0)) as over_60_days,
SUM(demands.debt) as debt
FROM billings, demands, clients
WHERE billings.kif = demands.kif AND demands.client_id = clients.id AND billings.country_id = ? AND demands.country_id = ?
GROUP BY billings.fund_source_id",
            [$country, $country]);
        
        return collect($result)->keyBy(function($result) {
            return is_null($result->fund_source_id) ? 'unknown' : $result->fund_source_id;
        })->map(function($result) {
            return (array) $result;
        })->toArray();
    }
    
    /**
     * @param string $country
     * @param string $fundSourceId
     * @return array
     */
    protected function getDemandsByFundSource($country, $fundSourceId)
    {
        $result = DB::select("
select demands.client_id, clients.name, clients.code, clients.status, clients.allowed_limit_outside, billings.fund_source_id,
SUM(IF(demands.overdue_days <= 0, demands.debt, 0)) as in_currency,
SUM(IF((demands.overdue_days >= -7) AND (demands.overdue_days <= 0), demands.debt, 0)) as in_currency_7_days,
SUM(IF((demands.overdue_days > 0) AND (demands.overdue_days <= 15), demands.debt, 0)) as in_15_days,
SUM(IF((demands.overdue_days > 15) AND (demands.overdue_days <= 30), demands.debt, 0)) as over_15_days,
SUM(IF((demands.overdue_days > 30) AND (demands.overdue_days <= 45), demands.debt, 0)) as over_30_days,
SUM(IF((demands.overdue_days > 45) AND (demands.overdue_days <= 60), demands.debt, 0)) as over_45_days,
SUM(IF(demands.overdue_days > 60, demands.debt, 0)) as over_60_days,
SUM(demands.debt) as debt
FROM billings, demands, clients
WHERE billings.kif = demands.kif AND demands.client_id = clients.id AND billings.fund_source_id = ? AND billings.country_id = ? AND demands.country_id = ? AND debt <> 0
GROUP BY demands.client_id",
            [$fundSourceId, $country, $country]);
        
        return collect($result)->map(function($result) {
            return (array) $result;
        })->toArray();
    }
}
