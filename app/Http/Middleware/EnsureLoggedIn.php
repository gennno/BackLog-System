<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureLoggedIn
{
    public function handle(Request $request, Closure $next)
    {
        // Exclude login page and homepage from middleware
        if ($request->is('/') || $request->is('login')) {
            return $next($request);
        }

        if (!auth()->check()) {
            return redirect('/')->with('error', 'Please log in first.');
        }

        return $next($request);
    }
}
