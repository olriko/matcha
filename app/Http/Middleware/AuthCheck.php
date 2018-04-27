<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AuthCheck
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('user')) {
            return $next($request);
        }
        return abort(401);
    }
}
