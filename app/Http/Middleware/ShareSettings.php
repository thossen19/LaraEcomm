<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShareSettings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $headerLogo = \App\Models\Setting::getValue('header_logo');
        $footerLogo = \App\Models\Setting::getValue('footer_logo');
        
        view()->share('headerLogo', $headerLogo);
        view()->share('footerLogo', $footerLogo);
        
        return $next($request);
    }
}
