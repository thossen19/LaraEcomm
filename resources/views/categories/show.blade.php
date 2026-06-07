@extends('layouts.app')

@section('title', $category->name ?? 'Category')

@php
    $currentCurrency = \App\Models\Setting::get('currency', 'USD');
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2">
            <li>
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li>
                <span class="text-gray-500">/</span>
            </li>
            <li class="text-gray-900 font-medium" aria-current="page">
                {{ $category->name }}
            </li>
        </ol>
    </nav>

    <!-- Category Header -->
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
                @if($category->description)
                    <p class="text-gray-600 mt-2">{{ $category->description }}</p>
                @endif
            </div>
            
            <!-- Category Image -->
            @if($category->image)
                <div class="w-32 h-32 rounded-lg overflow-hidden">
                    <img src="{{ asset('storage/' . $category->image) }}" 
                         alt="{{ $category->name }}" 
                         class="w-full h-full object-cover">
                </div>
            @endif
        </div>
    </div>

    <!-- Products Grid -->
    <div class="bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">
                    Products in {{ $category->name }}
                    <span class="text-sm text-gray-500">({{ $category->products_count ?? $category->products->count() }} products)</span>
                </h2>
                
                <!-- Sorting Options -->
                <div class="flex items-center space-x-4">
                    <label class="text-sm text-gray-700">Sort by:</label>
                    <select class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="created_at">Latest</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                        <option value="name">Name: A to Z</option>
                    </select>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($category->products as $product)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden group cursor-pointer">
                        <a href="{{ route('products.show', $product->slug ?? $product->id) }}" class="block">
                            <div class="relative">
                                <!-- Product Image -->
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400 text-4xl"></i>
                                    </div>
                                @endif

                                <!-- Discount Badge -->
                                @if($product->compare_price && $product->compare_price > $product->price)
                                    <span class="absolute top-2 left-2 bg-red-600 text-white text-xs px-2 py-1 rounded">
                                        -{{ round((($product->compare_price - $product->price) / $product->compare_price) * 100) }}%
                                    </span>
                                @endif

                                <!-- New Badge -->
                                @if($product->created_at->diffInDays(now()) <= 7)
                                    <span class="absolute top-2 right-2 bg-green-600 text-white text-xs px-2 py-1 rounded">New</span>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="p-4">
                                <!-- Product Name -->
                                <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                    {{ $product->name }}
                                </h3>

                                <!-- Category -->
                                @if($product->categories && $product->categories->isNotEmpty())
                                    <p class="text-xs text-gray-500 mb-2">
                                        <i class="fas fa-tag mr-1"></i>
                                        {{ $product->categories->first()->name }}
                                    </p>
                                @endif

                                <!-- Price -->
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center">
                                        @if($product->sale_price && $product->sale_price < $product->price)
                                            <span class="text-2xl font-bold text-green-600">{{ \App\Helpers\CurrencyHelper::format($product->sale_price, $currentCurrency) }}</span>
                                            <span class="text-sm text-gray-500 line-through ml-2">{{ \App\Helpers\CurrencyHelper::format($product->price, $currentCurrency) }}</span>
                                        @else
                                            <span class="text-2xl font-bold text-gray-900">{{ \App\Helpers\CurrencyHelper::format($product->price, $currentCurrency) }}</span>
                                            @if($product->compare_price && $product->compare_price > $product->price)
                                                <span class="text-sm text-gray-500 line-through ml-2">{{ \App\Helpers\CurrencyHelper::format($product->compare_price, $currentCurrency) }}</span>
                                            @endif
                                        @endif
                                    </div>

                                    <!-- Rating -->
                                    <div class="flex items-center text-xs text-gray-600">
                                        <div class="flex text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= 4)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="ml-1">({{ rand(38, 48) }}/5.0)</span>
                                    </div>
                                </div>

                                <!-- Stock Status -->
                                <div class="flex items-center justify-between">
                                    <div class="text-xs text-gray-600">
                                        @if($product->track_quantity)
                                            @if($product->quantity > 0)
                                                @if($product->quantity > $product->low_stock_threshold)
                                                    <span class="text-orange-600">
                                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                                        Only {{ $product->quantity }} left
                                                    </span>
                                                @else
                                                    <span class="text-green-600">
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                        In Stock
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-gray-500">
                                                    <i class="fas fa-infinity mr-1"></i>
                                                    Unlimited Stock
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-gray-500">
                                                <i class="fas fa-box mr-1"></i>
                                                Available
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Add to Cart Button -->
                                    <button class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors duration-200 flex items-center justify-center">
                                        <i class="fas fa-shopping-cart mr-2"></i>
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="text-gray-500 mb-4">
                            <i class="fas fa-box-open text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">No products found</h3>
                        <p class="text-gray-600">There are currently no products available in this category.</p>
                        <p class="text-gray-600">Please check back later or browse other categories.</p>
                        
                        <!-- Browse Other Categories -->
                        <div class="mt-8">
                            <h4 class="text-lg font-semibold text-gray-700 mb-4">Browse Other Categories</h4>
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                                @php
                                    $otherCategories = \App\Models\Category::active()
                                        ->where('id', '!=', $category->id ?? 0)
                                        ->limit(8)
                                        ->get();
                                @endphp
                                
                                @foreach($otherCategories as $otherCategory)
                                    <a href="{{ route('categories.show', $otherCategory->slug) }}" 
                                       class="bg-white rounded-lg p-4 text-center hover:shadow-lg transition-shadow duration-300 block">
                                        @if($otherCategory->image)
                                            <img src="{{ asset('storage/' . $otherCategory->image) }}" 
                                                 alt="{{ $otherCategory->name }}" 
                                                 class="w-16 h-16 mx-auto mb-2 object-contain">
                                        @else
                                            <div class="w-16 h-16 mx-auto mb-2 bg-gray-200 rounded flex items-center justify-center">
                                                <i class="fas fa-folder text-gray-400 text-2xl"></i>
                                            </div>
                                        @endif
                                        <p class="text-sm text-gray-700 font-medium">{{ $otherCategory->name }}</p>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($category->products && $category->products->count() > 12)
                <div class="mt-8 text-center">
                    <p class="text-gray-600">Showing first 12 products. <a href="{{ route('categories.products', $category->slug) }}" class="text-blue-600 hover:text-blue-500">View all products</a></p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Related Categories -->
@if($relatedCategories->isNotEmpty())
    <div class="bg-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Categories</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($relatedCategories as $relatedCategory)
                    <a href="{{ route('categories.show', $relatedCategory->slug) }}" 
                       class="bg-white rounded-lg p-4 text-center hover:shadow-lg transition-shadow duration-300 block">
                        @if($relatedCategory->image)
                            <img src="{{ asset('storage/' . $relatedCategory->image) }}" 
                                 alt="{{ $relatedCategory->name }}" 
                                 class="w-16 h-16 mx-auto mb-2 object-contain">
                        @else
                            <div class="w-16 h-16 mx-auto mb-2 bg-gray-200 rounded flex items-center justify-center">
                                <i class="fas fa-folder text-gray-400 text-2xl"></i>
                            </div>
                        @endif
                        <p class="text-sm text-gray-700 font-medium">{{ $relatedCategory->name }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif
@endsection
