<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    /**
     * Display a listing of flash sale products.
     */
    public function index(Request $request)
    {
        $query = Product::with(['categories', 'shop'])
            ->where(function($q) {
                $q->whereNotNull('compare_price')
                  ->where('compare_price', '>', 0);
            });
            
        // Apply filters
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where('name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('description', 'LIKE', '%' . $searchTerm . '%');
        }
        
        if ($request->has('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }
        
        if ($request->has('shop')) {
            $query->where('shop_id', $request->shop);
        }
        
        $products = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Calculate discount percentages
        $products->getCollection()->transform(function ($product) {
            if ($product->compare_price && $product->compare_price > $product->price) {
                $product->discount_percentage = round(($product->compare_price - $product->price) / $product->compare_price * 100, 1);
            } else {
                $product->discount_percentage = 0;
            }
            return $product;
        });
        
        return view('admin.flash-sale.index', compact('products'));
    }
    
    /**
     * Show the form for creating a new flash sale product.
     */
    public function create()
    {
        $products = Product::where('is_active', true)
            ->with(['categories', 'shop'])
            ->orderBy('name')
            ->get();
            
        return view('admin.flash-sale.create', compact('products'));
    }
    
    /**
     * Store a newly created flash sale product in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'compare_prices' => 'required|array',
            'compare_prices.*' => 'required|numeric|min:0',
        ]);
        
        foreach ($request->product_ids as $index => $productId) {
            $product = Product::find($productId);
            if ($product && isset($request->compare_prices[$index])) {
                $comparePrice = $request->compare_prices[$index];
                if ($comparePrice > $product->price) {
                    $product->update(['compare_price' => $comparePrice]);
                }
            }
        }
        
        return redirect()->route('admin.flash-sale.index')
            ->with('success', 'Flash sale products added successfully!');
    }
    
    /**
     * Show the form for editing the specified flash sale product.
     */
    public function edit(Product $product)
    {
        $product->load(['categories', 'shop']);
        
        if ($product->compare_price && $product->compare_price > $product->price) {
            $product->discount_percentage = round(($product->compare_price - $product->price) / $product->compare_price * 100, 1);
        } else {
            $product->discount_percentage = 0;
        }
        
        return view('admin.flash-sale.edit', compact('product'));
    }
    
    /**
     * Update the specified flash sale product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'compare_price' => 'required|numeric|min:0',
        ]);
        
        if ($request->compare_price > $product->price) {
            $product->update(['compare_price' => $request->compare_price]);
            
            return redirect()->route('admin.flash-sale.index')
                ->with('success', 'Flash sale product updated successfully!');
        } else {
            return redirect()->back()
                ->with('error', 'Compare price must be greater than regular price!')
                ->withInput();
        }
    }
    
    /**
     * Remove the specified flash sale product from flash sale.
     */
    public function destroy(Product $product)
    {
        $product->update(['compare_price' => null]);
        
        return redirect()->route('admin.flash-sale.index')
            ->with('success', 'Product removed from flash sale successfully!');
    }
    
    /**
     * Toggle flash sale status for a product.
     */
    public function toggleStatus(Product $product)
    {
        if ($product->compare_price) {
            $product->update(['compare_price' => null]);
            $message = 'Product removed from flash sale';
        } else {
            // Add a default 20% discount
            $comparePrice = $product->price * 1.2;
            $product->update(['compare_price' => $comparePrice]);
            $message = 'Product added to flash sale with 20% discount';
        }
        
        return redirect()->route('admin.flash-sale.index')
            ->with('success', $message . ' successfully!');
    }
}
