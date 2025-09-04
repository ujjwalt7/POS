<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Restaurant;
use App\Models\LanguageSetting;

class CustomerSiteLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the restaurant hash from the route
        $hash = $request->route('hash');
        
        if ($hash) {
            // Find the restaurant
            $restaurant = Restaurant::where('hash', $hash)->first();
            
            if ($restaurant && $restaurant->customer_site_language) {
                // Set the locale based on restaurant's customer site language setting
                App::setLocale($restaurant->customer_site_language);
                
                // Set RTL if the language is RTL
                $language = LanguageSetting::where('language_code', $restaurant->customer_site_language)->first();
                if ($language && $language->is_rtl) {
                    session(['isRtl' => true]);
                } else {
                    session(['isRtl' => false]);
                }
            } else {
                // Fallback to default language
                App::setLocale('en');
                session(['isRtl' => false]);
            }
        } else {
            // Fallback to default language
            App::setLocale('en');
            session(['isRtl' => false]);
        }

        return $next($request);
    }
} 