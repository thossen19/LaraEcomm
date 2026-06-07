<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // Get all active root categories (categories without parent)
        $categories = Category::active()
            ->root()
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function show(Category $category)
    {
        // Load category with relationships (limited to 12 products for show page)
        $category->load(['products' => function($query) {
            $query->where('is_active', true)
                  ->orderBy('created_at', 'desc')
                  ->limit(12);
        }]);

        // Get related categories (siblings or children)
        $relatedCategories = Category::active()
            ->where(function($query) use ($category) {
                $query->where('parent_id', $category->parent_id)
                      ->orWhere('parent_id', $category->id);
            })
            ->where('id', '!=', $category->id)
            ->limit(4)
            ->get();

        return view('categories.show', compact('category', 'relatedCategories'));
    }

    public function products(Category $category)
    {
        $products = $category->products()
            ->where('is_active', true)
            ->with(['categories', 'brand'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('categories.products', compact('category', 'products'));
    }
}
