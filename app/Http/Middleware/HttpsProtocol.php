<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\URL;

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
            URL::forceSchema('https');
            return redirect()->secure($request->getRequestUri());
        }
        return $next($request);
    }
}
