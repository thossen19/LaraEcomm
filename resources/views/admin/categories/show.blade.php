@extends('admin.layouts.app')

@section('title', 'Category Details')

@section('header', 'Category Details')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6 lg:p-8">
            <!-- Category Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" 
                             class="h-16 w-16 rounded object-cover">
                    @else
                        <div class="h-16 w-16 bg-gray-200 rounded flex items-center justify-center">
                            <i class="fas fa-tags text-gray-400 text-2xl"></i>
                        </div>
                    @endif
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $category->parent ? 'Subcategory of ' . $category->parent->name : 'Root Category' }}</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.categories.edit', $category) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Category
                    </a>
                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Are you sure you want to delete this category?')" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-trash mr-2"></i>
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            <!-- Category Info Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Category Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                        <dl class="grid grid-cols-1 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $category->description ?? 'No description provided' }}</dd>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Parent Category</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $category->parent->name ?? 'Root Category' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Sort Order</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $category->sort_order ?? 0 }}</dd>
                                </div>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">URL Slug</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $category->slug }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Category Image -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Category Image</h3>
                        <div class="space-y-4">
                            @if($category->image)
                                <div class="flex items-center space-x-6">
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" 
                                         class="h-32 w-32 rounded object-cover">
                                    <div>
                                        <p class="text-sm text-gray-600">Current category image</p>
                                        <p class="text-xs text-gray-500 mt-1">Path: {{ $category->image }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="h-24 w-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-tags text-gray-400 text-3xl"></i>
                                    </div>
                                    <p class="text-sm text-gray-500">No category image uploaded</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- SEO Information -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Information</h3>
                        <dl class="grid grid-cols-1 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Meta Title</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $category->meta_title ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Meta Description</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $category->meta_description ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">URL Slug</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $category->slug }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Subcategories -->
                    @if($category->children && $category->children->count() > 0)
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Subcategories ({{ $category->children->count() }})</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($category->children as $child)
                                    <div class="flex items-center justify-between p-3 bg-white rounded border">
                                        <div class="flex items-center space-x-3">
                                            @if($child->image)
                                                <img src="{{ asset('storage/' . $child->image) }}" alt="{{ $child->name }}" 
                                                     class="h-8 w-8 rounded object-cover">
                                            @else
                                                <div class="h-8 w-8 bg-gray-200 rounded flex items-center justify-center">
                                                    <i class="fas fa-tags text-gray-400 text-xs"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $child->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $child->products_count ?? 0 }} products</p>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.categories.show', $child) }}" class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>
                                            <a href="{{ route('admin.categories.edit', $child) }}" class="text-indigo-600 hover:text-indigo-800">
                                                <i class="fas fa-edit text-sm"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Products in Category -->
                    @if($category->products && $category->products->count() > 0)
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Products in Category ({{ $category->products->count() }})</h3>
                                <a href="{{ route('admin.products.index', ['category' => $category->id]) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    View All Products
                                </a>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($category->products->take(6) as $product)
                                    <div class="flex items-center justify-between p-3 bg-white rounded border">
                                        <div class="flex items-center space-x-3">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                                     class="h-8 w-8 rounded object-cover">
                                            @else
                                                <div class="h-8 w-8 bg-gray-200 rounded flex items-center justify-center">
                                                    <i class="fas fa-box text-gray-400 text-xs"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                                <p class="text-xs text-gray-500">${{ number_format($product->price, 2) }}</p>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.products.show', $product) }}" class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-800">
                                                <i class="fas fa-edit text-sm"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if($category->products->count() > 6)
                                <div class="mt-4 text-center">
                                    <a href="{{ route('admin.products.index', ['category' => $category->id]) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        View {{ $category->products->count() - 6 }} more products
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Sidebar Information -->
                <div class="space-y-6">
                    <!-- Status Information -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Status</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Active Status</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Featured</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->is_featured ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $category->is_featured ? 'Featured' : 'Not Featured' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Statistics</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Total Products</span>
                                <span class="text-sm text-gray-900">{{ $category->products_count ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Subcategories</span>
                                <span class="text-sm text-gray-900">{{ $category->children->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Level</span>
                                <span class="text-sm text-gray-900">{{ $category->parent ? 'Subcategory' : 'Root' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <form method="POST" action="{{ route('admin.categories.toggle-status', $category) }}" class="inline">
                                @csrf
                                <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-toggle-on mr-2"></i>
                                    {{ $category->is_active ? 'Deactivate' : 'Activate' }} Category
                                </button>
                            </form>
                            
                            <a href="{{ route('categories.show', $category) }}" target="_blank"
                               class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium inline-block text-center">
                                <i class="fas fa-eye mr-2"></i>
                                View on Store
                            </a>
                            
                            @if($category->children->count() == 0)
                                <a href="{{ route('admin.categories.create', ['parent_id' => $category->id]) }}" 
                                   class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium inline-block text-center">
                                    <i class="fas fa-plus mr-2"></i>
                                    Add Subcategory
                                </a>
                            @endif
                            
                            <a href="{{ route('admin.categories.index') }}" 
                               class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium inline-block text-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back to Categories
                            </a>
                        </div>
                    </div>

                    <!-- Timestamps -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Timestamps</h3>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Created</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $category->created_at->format('M j, Y g:i A') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $category->updated_at->format('M j, Y g:i A') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
