<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['categories', 'brands']);
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        if ($request->filled('brand')) {
            $query->whereHas('brands', function ($q) use ($request) {
                $q->where('brands.id', $request->brand);
            });
        }

        if ($request->filled('status')) {
            $query->where('quantity', $request->status === 'in_stock' ? '>' : '=', 0);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(15);
        
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'sku' => 'required|string|max:100|unique:products,sku',
            'barcode' => 'nullable|string|max:100|unique:products,barcode',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'track_quantity' => 'boolean',
            'quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_best_fashion' => 'boolean',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        // Handle additional images
        $additionalImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $additionalImages[] = $path;
            }
            $validated['images'] = json_encode($additionalImages);
        }

        // Handle boolean fields (unchecked checkboxes send nothing)
        foreach (['is_active', 'is_featured', 'is_best_fashion', 'track_quantity'] as $field) {
            if (!array_key_exists($field, $validated)) {
                $validated[$field] = false;
            }
        }
        $validated['low_stock_threshold'] = $validated['low_stock_threshold'] ?? 10;

        // Remove fields that don't exist in database
        unset($validated['category_id']);
        unset($validated['brand_id']);

        $product = Product::create($validated);

        // Attach category and brand
        if ($request->filled('category_id')) {
            $product->categories()->sync([$request->category_id]);
        }
        if ($request->filled('brand_id')) {
            $product->brands()->sync([$request->brand_id]);
        }

        return redirect()->route('admin.products.index')
                    ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['categories', 'brands', 'variants']);
        
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $product->load(['categories', 'brands']);
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        
        // Get the first category and brand IDs for the form
        $selectedCategoryId = $product->categories->first()?->id;
        $selectedBrandId = $product->brands->first()?->id;
        
        return view('admin.products.edit', compact('product', 'categories', 'brands', 'selectedCategoryId', 'selectedBrandId'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'barcode' => 'nullable|string|max:100|unique:products,barcode,' . $product->id,
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'track_quantity' => 'boolean',
            'quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_best_fashion' => 'boolean',
            'is_digital' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'remove_main_image' => 'nullable|in:0,1',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'integer',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = \Str::slug($validated['name']);
        }

        // Handle boolean fields (unchecked checkboxes send nothing)
        foreach (['is_active', 'is_featured', 'is_best_fashion', 'is_digital', 'track_quantity'] as $field) {
            if (!array_key_exists($field, $validated)) {
                $validated[$field] = false;
            }
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            if ($product->image) {
                \Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Handle remove main image
        if (($validated['remove_main_image'] ?? '0') === '1') {
            if ($product->image) {
                \Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = null;
        }
        unset($validated['remove_main_image']);

        // Handle additional images removal
        if (!empty($validated['remove_images'])) {
            $existingImages = json_decode($product->images, true) ?: [];
            foreach ($validated['remove_images'] as $index) {
                if (isset($existingImages[$index])) {
                    \Storage::disk('public')->delete($existingImages[$index]);
                    unset($existingImages[$index]);
                }
            }
            $validated['images'] = json_encode(array_values($existingImages));
        }
        unset($validated['remove_images']);

        // Handle additional images upload
        if ($request->hasFile('images')) {
            $existingImages = json_decode($product->images, true) ?: [];
            foreach ($request->file('images') as $image) {
                $existingImages[] = $image->store('products', 'public');
            }
            $validated['images'] = json_encode($existingImages);
        }

        // Remove fields that don't exist in the products table
        unset($validated['category_id']);
        unset($validated['brand_id']);

        $product->update($validated);
        $product->categories()->sync([$request->category_id]);
        if ($request->brand_id) {
            $product->brands()->sync([$request->brand_id]);
        } else {
            $product->brands()->detach();
        }

        return redirect()->route('admin.products.index')
                    ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
                    ->with('success', 'Product deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
        ]);

        Product::whereIn('id', $validated['products'])->delete();

        return redirect()->route('admin.products.index')
                    ->with('success', 'Selected products deleted successfully.');
    }

    public function toggleStatus(Product $product)
    {
        $product->update(['quantity' => $product->quantity > 0 ? 0 : 1]);

        return redirect()->back()->with('success', 'Product status updated.');
    }
}
