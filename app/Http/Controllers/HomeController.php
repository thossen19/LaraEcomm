<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\BannerImage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get featured products (active and in stock)
        $featuredProducts = Product::active()
            ->inStock()
            ->with(['categories', 'variants'])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Get new arrivals
        $newArrivals = Product::active()
            ->inStock()
            ->with(['categories', 'variants'])
            ->orderBy('created_at', 'desc')
            ->take(12)
            ->get();

        // Get all categories with products (not just parent categories)
        $categories = Category::where('is_active', true)
            ->whereHas('products', function($query) {
                $query->where('is_active', true)->where('stock_quantity', '>', 0);
            })
            ->withCount(['products' => function($query) {
                $query->where('is_active', true)->where('stock_quantity', '>', 0);
            }])
            ->orderBy('name')
            ->get();

        // Get best sellers (you could implement this based on order history)
        $bestSellers = Product::active()
            ->inStock()
            ->with(['categories', 'variants'])
            ->inRandomOrder()
            ->take(6)
            ->get();

        // Get active banner images for slider
        $bannerImages = BannerImage::active()->ordered()->get();

        return view('home', compact(
            'featuredProducts',
            'newArrivals', 
            'categories',
            'bestSellers',
            'bannerImages'
        ));
    }
}
