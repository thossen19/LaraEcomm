<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        // Get recent orders
        $recentOrders = Order::where('user_id', $user->id)
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get cart items count
        $cart = Cart::where('user_id', $user->id)->first();
        $cartItemsCount = $cart ? $cart->items->sum('quantity') : 0;
        
        // Get wishlist count
        $wishlistCount = Wishlist::where('user_id', $user->id)->count();
        
        // Get order statistics
        $totalOrders = Order::where('user_id', $user->id)->count();
        $totalSpent = Order::where('user_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->sum('total');
        
        // Get recent wishlist items
        $recentWishlist = Wishlist::where('user_id', $user->id)
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        return view('customer.dashboard', compact(
            'recentOrders',
            'cartItemsCount',
            'wishlistCount',
            'totalOrders',
            'totalSpent',
            'recentWishlist'
        ));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('customer.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'bio' => 'nullable|string|max:1000',
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }
}
