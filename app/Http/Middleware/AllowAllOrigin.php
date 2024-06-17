<?php

namespace App\Http\Middleware;

use Closure;

class AllowAllOrigin
{
    protected $except = [
    ];

    public function handle($request, Closure $next) {
        $response = $next($request);
        $response->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET')
            ->header('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, X-Token-Auth, Authorization');
        return $response;
    }

}
