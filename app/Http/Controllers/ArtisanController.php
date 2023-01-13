<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

/**
 * Class ArtisanController
 *
 * @package App\Http\Controllers
 */
class ArtisanController extends Controller
{
    /**
     * @return string
     */
    public function cacheClear()
    {
        Artisan::call('cache:clear');
        
        return Artisan::output();
    }
    
    /**
     * @return string
     */
    public function viewClear()
    {
        Artisan::call('view:clear');
        
        return Artisan::output();
    }
    
    /**
     * @return string
     */
    public function configClear()
    {
        Artisan::call('config:clear');
        
        return Artisan::output();
    }
    
    /**
     * @return string
     */
    public function routeClear()
    {
        Artisan::call('route:clear');
        
        return Artisan::output();
    }
    
    /**
     * @return string
     */
    public function migrate()
    {
        Artisan::call('migrate', [
            '--force' => true,
        ]);
        
        return Artisan::output();
    }
    
    /**
     * @return string
     */
    public function opcacheReset()
    {
        return opcache_reset() ? 'true' : 'false';
    }
}
