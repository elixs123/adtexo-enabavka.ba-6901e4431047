<?php

namespace App\Support\Html\Vuexy\Admin;

use Illuminate\Support\ServiceProvider;

/**
 * Class PortoAdminServiceProvider
 *
 * @package App\Support\Html\Vuexy\Admin
 */
class VuexyAdminServiceProvider extends ServiceProvider
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
        $this->app->singleton('vuexy.admin', function($app) {
            return new VuexyAdmin($app['html'], $app['request']);
        });
    }
}