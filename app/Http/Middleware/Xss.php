<?php

namespace App\Http\Middleware;

use Closure;
use App\Libraries\Xss as XssLib;

class Xss
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {	
	XssLib::globalClean($request);

        return $next($request);
    }
}
