<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::with(['children', 'products'])
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('position', 'asc');

        if ($request->has('include_products')) {
            $query->with(['products' => function ($q) {
                $q->active()->inStock()->take(8);
            }]);
        }

        $categories = $query->get();

        return response()->json($categories);
    }

    public function tree(Request $request)
    {
        $categories = Category::with(['children' => function ($query) {
                $query->where('is_active', true)->orderBy('position', 'asc');
            }])
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('position', 'asc')
            ->get();

        return response()->json($categories);
    }

    public function show($id)
    {
        $category = Category::with(['children', 'products', 'parent'])
            ->findOrFail($id);

        return response()->json($category);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'icon' => 'nullable|string',
            'is_active' => 'boolean',
            'parent_id' => 'nullable|exists:categories,id',
            'position' => 'integer|min:0',
            'meta_data' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . uniqid(),
            'description' => $request->description,
            'image' => $request->image,
            'icon' => $request->icon,
            'is_active' => $request->is_active ?? true,
            'parent_id' => $request->parent_id,
            'position' => $request->position ?? 0,
            'meta_data' => $request->meta_data,
        ]);

        return response()->json($category->load(['parent', 'children']), 201);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'icon' => 'nullable|string',
            'is_active' => 'boolean',
            'parent_id' => 'nullable|exists:categories,id',
            'position' => 'integer|min:0',
            'meta_data' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category->update($request->only([
            'name', 'description', 'image', 'icon', 'is_active',
            'parent_id', 'position', 'meta_data'
        ]));

        // Update slug if name changed
        if ($category->wasChanged('name')) {
            $category->slug = Str::slug($request->name) . '-' . uniqid();
            $category->save();
        }

        return response()->json($category->load(['parent', 'children']));
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Check if category has children
        if ($category->children()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete category with subcategories'
            ], 422);
        }

        // Check if category has products
        if ($category->products()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete category with products'
            ], 422);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }

    public function products($id, Request $request)
    {
        $category = Category::findOrFail($id);
        
        $products = $category->products()
            ->with(['variants', 'shop'])
            ->active()
            ->inStock()
            ->paginate($request->get('per_page', 12));

        return response()->json($products);
    }

    public function featured(Request $request)
    {
        $categories = Category::with(['products' => function ($query) {
                $query->active()->inStock()->take(4);
            }])
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('position', 'asc')
            ->take($request->get('limit', 6))
            ->get();

        return response()->json($categories);
    }
}
