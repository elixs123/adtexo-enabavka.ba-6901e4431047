<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes();
    
        /*
         * Authenticate the user's personal channel...
         */
        // Broadcast::channel('auth.{hash}', function ($user, $hash) {
        //     return hash('sha1', $user->id) === $hash;
        // });
    }
}
