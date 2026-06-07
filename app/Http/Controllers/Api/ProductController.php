<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['categories', 'variants', 'shop'])
            ->active()
            ->inStock();

        // Filter by category
        if ($request->has('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        // Filter by shop
        if ($request->has('shop_id')) {
            $query->where('shop_id', $request->shop_id);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $products = $query->paginate($request->get('per_page', 12));

        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::with(['categories', 'variants', 'shop'])
            ->findOrFail($id);

        return response()->json($product);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'sku' => 'required|string|unique:products',
            'barcode' => 'nullable|string|unique:products',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'track_quantity' => 'boolean',
            'is_active' => 'boolean',
            'is_digital' => 'boolean',
            'requires_shipping' => 'boolean',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|array',
            'product_type' => 'required|in:physical,digital,classified',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'variants' => 'nullable|array',
            'shop_id' => 'nullable|exists:shops,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . uniqid(),
            'description' => $request->description,
            'short_description' => $request->short_description,
            'sku' => $request->sku,
            'barcode' => $request->barcode,
            'price' => $request->price,
            'compare_price' => $request->compare_price,
            'cost_price' => $request->cost_price,
            'stock_quantity' => $request->stock_quantity,
            'track_quantity' => $request->track_quantity ?? true,
            'is_active' => $request->is_active ?? true,
            'is_digital' => $request->is_digital ?? false,
            'requires_shipping' => $request->requires_shipping ?? true,
            'weight' => $request->weight,
            'dimensions' => $request->dimensions,
            'product_type' => $request->product_type,
            'status' => 'active',
            'shop_id' => $request->shop_id,
        ]);

        // Attach categories
        if ($request->has('category_ids')) {
            $product->categories()->attach($request->category_ids);
        }

        // Create variants if provided
        if ($request->has('variants') && is_array($request->variants)) {
            foreach ($request->variants as $variant) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $variant['sku'],
                    'title' => $variant['title'],
                    'price' => $variant['price'],
                    'compare_price' => $variant['compare_price'] ?? null,
                    'stock_quantity' => $variant['stock_quantity'] ?? 0,
                    'is_active' => $variant['is_active'] ?? true,
                    'barcode' => $variant['barcode'] ?? null,
                    'weight' => $variant['weight'] ?? null,
                    'options' => $variant['options'] ?? null,
                    'image' => $variant['image'] ?? null,
                    'position' => $variant['position'] ?? 0,
                ]);
            }
        }

        return response()->json($product->load(['categories', 'variants']), 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'barcode' => 'nullable|string|unique:products,barcode,' . $product->id,
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'track_quantity' => 'boolean',
            'is_active' => 'boolean',
            'is_digital' => 'boolean',
            'requires_shipping' => 'boolean',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|array',
            'product_type' => 'required|in:physical,digital,classified',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'shop_id' => 'nullable|exists:shops,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product->update($request->only([
            'name', 'description', 'short_description', 'sku', 'barcode',
            'price', 'compare_price', 'cost_price', 'stock_quantity',
            'track_quantity', 'is_active', 'is_digital', 'requires_shipping',
            'weight', 'dimensions', 'product_type', 'shop_id'
        ]));

        // Update slug if name changed
        if ($product->wasChanged('name')) {
            $product->slug = Str::slug($request->name) . '-' . uniqid();
            $product->save();
        }

        // Sync categories
        if ($request->has('category_ids')) {
            $product->categories()->sync($request->category_ids);
        }

        return response()->json($product->load(['categories', 'variants']));
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

    public function featured(Request $request)
    {
        $products = Product::with(['categories', 'shop'])
            ->active()
            ->inStock()
            ->orderBy('created_at', 'desc')
            ->take($request->get('limit', 8))
            ->get();

        return response()->json($products);
    }

    public function related($id)
    {
        $product = Product::findOrFail($id);
        
        $related = Product::with(['categories', 'shop'])
            ->active()
            ->inStock()
            ->where('id', '!=', $product->id)
            ->whereHas('categories', function ($query) use ($product) {
                $query->whereIn('categories.id', $product->categories->pluck('id'));
            })
            ->take(8)
            ->get();

        return response()->json($related);
    }
}
