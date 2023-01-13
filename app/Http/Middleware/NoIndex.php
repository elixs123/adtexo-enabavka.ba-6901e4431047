<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Class NoIndex
 *
 * @package App\Http\Middleware
 */
class NoIndex
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
        $response = $next($request);

        $response->header('X-Robots-Tag', 'noindex');

        return $response;
    }
}
