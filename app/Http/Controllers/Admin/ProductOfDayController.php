<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductOfDay;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductOfDayController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = ProductOfDay::with('product');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status === 'active');
        }

        $productsOfDay = $query->orderBy('sort_order', 'asc')
                              ->orderBy('created_at', 'desc')
                              ->paginate(20);

        // Get all products for dropdown
        $products = Product::where('is_active', 1)->orderBy('name')->get();

        return view('admin.cms.product-of-day.index', compact('productsOfDay', 'products'));
    }

    public function create()
    {
        $products = Product::where('is_active', 1)->orderBy('name')->get();
        return view('admin.cms.product-of-day.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'badge_text' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:7',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'original_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|url',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500'
        ]);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('product-of-day', 'public');
        }

        // Handle banner image upload
        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $request->file('banner_image')->store('product-of-day', 'public');
        }

        // Set default values
        $validated['status'] = $request->has('status');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['badge_text'] = $validated['badge_text'] ?? 'Product of the Day';
        $validated['badge_color'] = $validated['badge_color'] ?? '#FF6B6B';
        $validated['button_text'] = $validated['button_text'] ?? 'View Product';

        ProductOfDay::create($validated);

        return redirect()->route('admin.cms.product-of-day.index')
                         ->with('success', 'Product of the Day created successfully.');
    }

    public function edit(ProductOfDay $productOfDay)
    {
        $products = Product::where('status', 'active')->orderBy('name')->get();
        return view('admin.cms.product-of-day.edit', compact('productOfDay', 'products'));
    }

    public function update(Request $request, ProductOfDay $productOfDay)
    {
        $validated = $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'badge_text' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:7',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'original_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|url',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500'
        ]);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($productOfDay->featured_image) {
                Storage::disk('public')->delete($productOfDay->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('product-of-day', 'public');
        }

        // Handle banner image upload
        if ($request->hasFile('banner_image')) {
            // Delete old image
            if ($productOfDay->banner_image) {
                Storage::disk('public')->delete($productOfDay->banner_image);
            }
            $validated['banner_image'] = $request->file('banner_image')->store('product-of-day', 'public');
        }

        // Set boolean values
        $validated['status'] = $request->has('status');
        $validated['sort_order'] = $validated['sort_order'] ?? $productOfDay->sort_order;

        $productOfDay->update($validated);

        return redirect()->route('admin.cms.product-of-day.index')
                         ->with('success', 'Product of the Day updated successfully.');
    }

    public function destroy(ProductOfDay $productOfDay)
    {
        // Delete images
        if ($productOfDay->featured_image) {
            Storage::disk('public')->delete($productOfDay->featured_image);
        }
        if ($productOfDay->banner_image) {
            Storage::disk('public')->delete($productOfDay->banner_image);
        }

        $productOfDay->delete();

        return redirect()->route('admin.cms.product-of-day.index')
                         ->with('success', 'Product of the Day deleted successfully.');
    }

    public function toggleStatus(ProductOfDay $productOfDay)
    {
        $productOfDay->update([
            'status' => !$productOfDay->status
        ]);

        return back()->with('success', 'Product of the Day status updated successfully.');
    }

    public function duplicate(ProductOfDay $productOfDay)
    {
        $newProduct = $productOfDay->replicate();
        $newProduct->title = $productOfDay->title . ' (Copy)';
        $newProduct->status = false; // Set as inactive by default
        $newProduct->save();

        // Duplicate featured image if exists
        if ($productOfDay->featured_image) {
            $oldPath = $productOfDay->featured_image;
            $newPath = 'product-of-day/' . uniqid() . '.' . pathinfo($oldPath, PATHINFO_EXTENSION);
            
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->copy($oldPath, $newPath);
                $newProduct->update(['featured_image' => $newPath]);
            }
        }

        // Duplicate banner image if exists
        if ($productOfDay->banner_image) {
            $oldPath = $productOfDay->banner_image;
            $newPath = 'product-of-day/' . uniqid() . '.' . pathinfo($oldPath, PATHINFO_EXTENSION);
            
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->copy($oldPath, $newPath);
                $newProduct->update(['banner_image' => $newPath]);
            }
        }

        return redirect()->route('admin.cms.product-of-day.index')
                         ->with('success', 'Product of the Day duplicated successfully.');
    }
}
