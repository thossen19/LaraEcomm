@extends('admin.layouts.app')

@section('title', 'Product Details')

@section('header', 'Product Details')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6 lg:p-8">
            <!-- Product Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                             class="h-16 w-16 rounded object-cover">
                    @else
                        <div class="h-16 w-16 bg-gray-200 rounded flex items-center justify-center">
                            <i class="fas fa-box text-gray-400 text-2xl"></i>
                        </div>
                    @endif
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h2>
                        <p class="text-sm text-gray-500">SKU: {{ $product->sku }}</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.products.edit', $product) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Product
                    </a>
                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Are you sure you want to delete this product?')" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-trash mr-2"></i>
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            <!-- Product Info Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Product Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                        <dl class="grid grid-cols-1 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $product->description }}</dd>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Category</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $product->category->name ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Brand</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $product->brand->name ?? 'N/A' }}</dd>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Barcode</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $product->barcode ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Weight</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $product->weight ? $product->weight . ' kg' : 'N/A' }}</dd>
                                </div>
                            </div>
                        </dl>
                    </div>

                    <!-- Pricing Information -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Pricing</h3>
                        <dl class="grid grid-cols-1 gap-4">
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Regular Price</dt>
                                    <dd class="mt-1 text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Sale Price</dt>
                                    <dd class="mt-1 text-lg font-bold text-green-600">
                                        {{ $product->sale_price ? '$' . number_format($product->sale_price, 2) : 'N/A' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Cost Price</dt>
                                    <dd class="mt-1 text-lg font-bold text-gray-600">
                                        {{ $product->cost_price ? '$' . number_format($product->cost_price, 2) : 'N/A' }}
                                    </dd>
                                </div>
                            </div>
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Discount</dt>
                                    <dd class="mt-1 text-sm text-green-600">
                                        {{ round((($product->price - $product->sale_price) / $product->price) * 100, 1) }}% OFF
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Product Images -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Product Images</h3>
                        <div class="space-y-4">
                            <!-- Main Image -->
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Main Image</h4>
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                         class="h-48 w-48 rounded object-cover">
                                @else
                                    <div class="h-48 w-48 bg-gray-200 rounded flex items-center justify-center">
                                        <i class="fas fa-box text-gray-400 text-4xl"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Additional Images -->
                            @if($product->images && $product->images->count() > 0)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Additional Images</h4>
                                    <div class="grid grid-cols-4 gap-4">
                                        @foreach($product->images as $image)
                                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }}" 
                                                 class="h-24 w-24 rounded object-cover">
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- SEO Information -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Information</h3>
                        <dl class="grid grid-cols-1 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">URL Slug</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $product->slug }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Meta Title</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $product->meta_title ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Meta Description</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $product->meta_description ?? 'N/A' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Sidebar Information -->
                <div class="space-y-6">
                    <!-- Status Information -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Status</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Active Status</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Featured</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->is_featured ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $product->is_featured ? 'Featured' : 'Not Featured' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Stock Tracking</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->track_stock ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $product->track_stock ? 'Enabled' : 'Disabled' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory Information -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Inventory</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Current Stock</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->quantity > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $product->quantity }} units
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Low Stock Threshold</span>
                                <span class="text-sm text-gray-900">{{ $product->low_stock_threshold ?? 10 }} units</span>
                            </div>
                            @if($product->track_stock && $product->quantity <= $product->low_stock_threshold)
                                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-800">
                                                Low stock alert! Only {{ $product->quantity }} units remaining.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <form method="POST" action="{{ route('admin.products.toggle-status', $product) }}" class="inline">
                                @csrf
                                <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-toggle-on mr-2"></i>
                                    {{ $product->is_active ? 'Deactivate' : 'Activate' }} Product
                                </button>
                            </form>
                            
                            <button onclick="window.open('{{ route('products.show', $product) }}', '_blank')" 
                                    class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-eye mr-2"></i>
                                View on Store
                            </button>
                            
                            <a href="{{ route('admin.products.index') }}" 
                               class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium inline-block text-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back to Products
                            </a>
                        </div>
                    </div>

                    <!-- Timestamps -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Timestamps</h3>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Created</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $product->created_at->format('M j, Y g:i A') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $product->updated_at->format('M j, Y g:i A') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
