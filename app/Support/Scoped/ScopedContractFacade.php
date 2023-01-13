<?php

namespace App\Support\Scoped;

use Illuminate\Support\Facades\Facade;

/**
 * Class ScopedContractFacade
 *
 * @package App\Support\Scoped
 *
 * @method static bool check()
 * @method static bool hasClient()
 * @method static \App\Client|null getClient()
 * @method static bool hasContract()
 * @method static \App\Contract|null getContract()
 * @method static int|null id()
 * @method static bool hasProducts()
 * @method static \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection getProducts()
 * @method static bool hasProduct(int $productId)
 * @method static \App\ContractProduct|null getProduct(int $productId)
 * @method static void setDocument($document)
 */
class ScopedContractFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'ScopedContract';
    }
}
