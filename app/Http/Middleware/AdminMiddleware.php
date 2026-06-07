<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access the admin panel.');
        }

        // Check if user has admin role
        $user = Auth::user();
        if (!$user->hasRole(['super_admin', 'admin'])) {
            // If user has accountant role, redirect to accountant dashboard
            if ($user->hasRole(['accountant'])) {
                return redirect()->route('accountant.dashboard.index')->with('error', 'Access denied. You are an accountant.');
            }
            
            // For any other role, show access denied
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access the admin panel.');
        }

        // Add admin user data to all views
        view()->share('adminUser', $user);
        view()->share('isAdmin', true);
        
        return $next($request);
    }
}
