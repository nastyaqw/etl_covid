<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {//dd($request);
        if(auth('api') && auth('api')->user()->role == 'admin'){
           // dd(auth('api')->user());
            return $next($request);
          }
            return response()->json(['message' => 'You have not admin access'], 403);
    }
}
