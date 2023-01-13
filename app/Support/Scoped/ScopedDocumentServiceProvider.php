<?php

namespace App\Support\Scoped;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

/**
 * Class ScopedDocumentServiceProvider
 *
 * @package App\Support\Scoped
 */
class ScopedDocumentServiceProvider extends ServiceProvider
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
        $this->app->singleton('ScopedDocument', function($app) {
            return new ScopedDocument(Auth::user());
        });
    }
}
