<?php

namespace App\Providers;

use App\Permission;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        // 'App\Hearing' => 'App\Policies\HearingPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //Passport::routes();
        
        if(Schema::hasTable('roles'))
        {
            // Dynamically register permissions with Laravel's Gate.
            foreach ($this->getPermissions() as $permission) 
            {   
                Gate::define($permission->name, function ($user) use ($permission) {
                    return $user->hasPermission($permission);
                });                
            }  
        }          
    }
    
    /**
     * Fetch the collection of site permissions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getPermissions()
    {
        return Permission::with('roles')->get();
    }    
}