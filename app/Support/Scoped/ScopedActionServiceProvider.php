<?php

namespace App\Support\Scoped;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

/**
 * Class ScopedActionServiceProvider
 *
 * @package App\Support\Scoped
 */
class ScopedActionServiceProvider extends ServiceProvider
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
        $this->app->singleton('ScopedAction', function($app) {
            return new ScopedAction(Auth::user());
        });
    }
}
