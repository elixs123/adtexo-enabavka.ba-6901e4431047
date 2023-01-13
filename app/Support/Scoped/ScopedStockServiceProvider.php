<?php

namespace App\Support\Scoped;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

/**
 * Class ScopedStockServiceProvider
 *
 * @package App\Support\Scoped
 */
class ScopedStockServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
    
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Bind our component into the IoC container.
        $this->app->singleton('ScopedStock', function($app) {
            return new ScopedStock(Auth::user(), ScopedDocumentFacade::getDocument());
        });
    }
}
