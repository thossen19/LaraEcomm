<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    /**
     * Display flash sale products.
     */
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)
            ->where(function($q) {
                $q->whereNotNull('compare_price')
                  ->where('compare_price', '>', 0);
            })
            ->with(['categories', 'shop']);
            
        // Apply filters
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'discount_desc':
                    $query->orderByRaw('(compare_price - price) / compare_price DESC');
                    break;
                case 'created_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                default:
                    $query->orderByRaw('(compare_price - price) / compare_price DESC');
            }
        } else {
            $query->orderByRaw('(compare_price - price) / compare_price DESC');
        }
        
        // Filter by discount percentage
        if ($request->has('discount_filter')) {
            switch ($request->discount_filter) {
                case '10_plus':
                    $query->havingRaw('(compare_price - price) / compare_price * 100 >= 10');
                    break;
                case '25_plus':
                    $query->havingRaw('(compare_price - price) / compare_price * 100 >= 25');
                    break;
                case '50_plus':
                    $query->havingRaw('(compare_price - price) / compare_price * 100 >= 50');
                    break;
                case '70_plus':
                    $query->havingRaw('(compare_price - price) / compare_price * 100 >= 70');
                    break;
            }
        }
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('description', 'LIKE', '%' . $searchTerm . '%');
        }
        
        $products = $query->paginate(12);
        
        // Calculate discount percentages for display
        $products->getCollection()->transform(function ($product) {
            if ($product->compare_price && $product->compare_price > $product->price) {
                $product->discount_percentage = round(($product->compare_price - $product->price) / $product->compare_price * 100, 1);
                $product->sale_price = $product->price;
            } else {
                $product->discount_percentage = 0;
                $product->sale_price = $product->price;
            }
            return $product;
        });
        
        return view('flash-sale.index', compact('products'));
    }
}
