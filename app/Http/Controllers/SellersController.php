<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class SellersController extends Controller
{
    public function index()
    {
        // Get all users with seller role
        $sellers = User::whereHas('roles', function($query) {
            $query->where('name', 'seller');
        })
        ->with(['shop'])
        ->get();

        // Add products, orders count and rating to each seller
        foreach ($sellers as $seller) {
            $seller->products_count = $seller->shop ? $seller->shop->products()->count() : 0;
            
            $seller->orders_count = $seller->shop ? $seller->shop->orders_count : 0;
            
            // Calculate rating based on orders or reviews if available
            $seller->rating = 0;
            if ($seller->shop) {
                // For now, using a default rating or calculating from available data
                // In a real application, you might have a reviews system
                $seller->rating = 4.5; // Default rating
            }
        }

        return view('sellers.index', compact('sellers'));
    }

    public function show($id)
    {
        $seller = User::whereHas('roles', function($query) {
            $query->where('name', 'seller');
        })
        ->with(['shop'])
        ->findOrFail($id);

        $seller->orders_count = $seller->shop ? $seller->shop->orders_count : 0;
        
        // Calculate rating for the seller
        $seller->rating = $seller->shop ? 4.5 : 0; // Default rating
        
        // Get products for this seller through their shop
        $seller->load(['shop.products' => function($query) {
            $query->with(['categories']);
        }]);

        $products = $seller->shop ? $seller->shop->products : collect();

        return view('sellers.show', compact('seller', 'products'));
    }
}