<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClearCustomerLanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Clear customer site language session when accessing admin routes
        // This prevents RTL from affecting the admin panel
        if (!$request->is('restaurant/*') && !$request->is('restaurant') && !$request->is('table/*')) {
            session()->forget('locale');
            session()->forget('isRtl');
        }

        return $next($request);
    }
} 