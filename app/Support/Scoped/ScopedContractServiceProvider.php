<?php

namespace App\Support\Scoped;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

/**
 * Class ScopedContractServiceProvider
 *
 * @package App\Support\Scoped
 */
class ScopedContractServiceProvider extends ServiceProvider
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
        $this->app->singleton('ScopedContract', function($app) {
            return new ScopedContract(Auth::user());
        });
    }
}
