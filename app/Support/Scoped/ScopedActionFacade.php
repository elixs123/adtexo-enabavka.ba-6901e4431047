<?php

namespace App\Support\Scoped;

use Illuminate\Support\Facades\Facade;

/**
 * Class ScopedActionFacade
 *
 * @package App\Support\Scoped
 *
 * @method static array rolesWithAccess
 * @method static \Illuminate\Database\Query\Builder|mixed getQuery
 * @method static \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection getActions
 * @method static bool hasActions
 */
class ScopedActionFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'ScopedAction';
    }
}
