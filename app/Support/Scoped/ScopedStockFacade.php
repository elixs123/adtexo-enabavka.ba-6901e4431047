<?php

namespace App\Support\Scoped;

use Illuminate\Support\Facades\Facade;

/**
 * Class ScopedStockFacade
 *
 * @method static string langId()
 * @method static string priceCountryId()
 * @method static integer priceStockId()
 * @method static string currency()
 * @method static integer taxRate()
 *
 * @package App\Support\Scoped
 */
class ScopedStockFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'ScopedStock';
    }
}
