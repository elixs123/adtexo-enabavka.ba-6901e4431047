<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

/**
 * Class ValidatorServiceProvider
 *
 * @package App\Providers
 */
class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('username', function($attr, $value){
            return preg_match('/^[A-Za-z0-9_.]+$/', $value);
        });
        
        Validator::extend('routes', function($attr, $value){
            if (request('type_id') === 'private_client') {
                return true;
            }
            
            if (is_array($value) && empty($value)) {
                return false;
            }
            
            $valid = false;
            $num = 0;
            $size = userIsSalesman() ? 2 : 1;
            foreach ($value as $days) {
                foreach ($days as $day) {
                    if (!is_null($day)) {
                        $num++;
                        if ($num >= $size) {
                            $valid = true;
                            break 2;
                        }
                    }
                }
            }
            
            return $valid;
        });
    }
    
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
