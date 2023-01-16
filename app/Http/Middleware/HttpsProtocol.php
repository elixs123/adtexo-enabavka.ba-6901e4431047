<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;

class HttpsProtocol
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
        

        if (starts_with($request->header('host'), 'www.') && env('APP_ENV') === 'production')
        {
            $host = str_replace('www.', '', $request->header('host'));
            $request->headers->set('host', $host);

            return Redirect::to($request->fullUrl(), 301);
        }

        if (!$request->secure() && env('APP_ENV') === 'production' && !in_array($request->path(), $this->openRoutes))
        {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        if (auth()->check() && auth()->user()->isBanned())
        {
            auth()->logout();

            return redirect('/login');
        }


        return $next($request);
    }

}
