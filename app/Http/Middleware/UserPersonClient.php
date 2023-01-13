<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;

/**
 * Class UserPersonClient
 *
 * @package App\Http\Middleware
 */
class UserPersonClient
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
        if (auth()->check() && auth()->user()->isClient() && is_null(auth()->user()->client)) {
            auth()->logout();

            return redirect('/login')->withErrors([
                'email' => 'Nemate definisanog klijenta',
            ]);
        }

        return $next($request);
    }

}
