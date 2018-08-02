<?php

namespace App\Http\Middleware;

use Closure;

class HttpsProtocol
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
        $url = parse_url(getenv("APP_ENV"));
        if (!$request->secure() && $url === 'prod') {
            return redirect()->secure($request->getRequestUri());
        }
        return $next($request);
    }
}
