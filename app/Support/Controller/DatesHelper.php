<?php

namespace App\Support\Controller;

use Carbon\Carbon;

/**
 * Trait DatesHelper
 *
 * @package App\Support\Controller
 */
trait DatesHelper
{
    /**
     * @return array
     */
    private function getDatesData()
    {
        return [
            'start_date' => is_null($start = request('start')) ? (userIsWarehouse() ? now()->subDays(30) : now()->startOfMonth()) : Carbon::createFromFormat('Y-m-d', $start),
            'end_date' => is_null($end = request('end')) ? now()->endOfMonth() : Carbon::createFromFormat('Y-m-d', $end),
        ];
    }
}
