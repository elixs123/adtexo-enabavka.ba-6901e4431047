<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;
use Config;
use Illuminate\Support\Facades\DB;

class Database
{

    private $openRoutes = [];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        switch($request->route('db')){
            case 'ba':
                Config::set('database.default', 'mysql1');
            break;
            case 'rs':
                Config::set('database.default', 'mysql2');
            break;
            case 'hr':
                Config::set('database.default', 'mysql3');
            break;
                
        }

        $request->route()->forgetParameter('db');
        DB::purge();

        return $next($request);
    }

}
