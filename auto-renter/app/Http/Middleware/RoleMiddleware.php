<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
public function handle($request, Closure $next, $role)
{
    if (!Auth::check()) {
        abort(403, 'Access denied');
    }
    if (Auth::user()->role !== $role) {
        return redirect()->route('home');   // send the customer to the home page here
    }
    return $next($request);
}

    
}