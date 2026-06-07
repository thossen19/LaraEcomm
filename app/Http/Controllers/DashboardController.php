<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function admin()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }
        
        return view('dashboard.admin');
    }
    
    public function seller()
    {
        if (!Auth::user()->isSeller()) {
            abort(403, 'Unauthorized access');
        }
        
        return view('dashboard.seller');
    }
    
    public function customer()
    {
        if (!Auth::user()->isCustomer()) {
            abort(403, 'Unauthorized access');
        }
        
        return view('dashboard.customer');
    }
}
