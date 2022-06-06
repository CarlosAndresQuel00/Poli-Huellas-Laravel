<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $request->header('Access-Control-Allow-Origin', '*'); // Resources can be accessed by origins that we indicate there.
        $request->header('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH,
DELETE, OPTIONS'); // Methods that are known
        $request->header('Access-Control-Allow-Headers', 'Content-Type,
Authorization'); // Headers | cabezeras permitidas
        return $next($request);
    }
}
