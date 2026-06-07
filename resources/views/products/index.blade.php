@extends('layouts.app')

@section('title', 'Products')

@php
    $currentCurrency = \App\Models\Setting::get('currency', 'USD');
@endphp

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl font-bold mb-4">Discover Amazing Products</h1>
                <p class="text-xl text-blue-100">Shop our curated collection of premium items</p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Enhanced Filters -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-filter mr-2 text-blue-600"></i>
                    Filter Products
                </h2>
                <button onclick="toggleFilters()" class="text-gray-500 hover:text-gray-700 lg:hidden">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <form method="GET" action="{{ route('products.index') }}" class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-search mr-1 text-gray-400"></i>
                        Search
                    </label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." 
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <i class="fas fa-search absolute left-3 top-11 text-gray-400"></i>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-th-large mr-1 text-gray-400"></i>
                        Category
                    </label>
                    <select name="category" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tag mr-1 text-gray-400"></i>
                        Brand
                    </label>
                    <select name="brand" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <option value="">All Brands</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-3 rounded-lg font-medium transition-all transform hover:scale-105 shadow-lg">
                        <i class="fas fa-search mr-2"></i>
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Results Count -->
        <div class="mb-6 flex items-center justify-between">
            <p class="text-gray-600">
                <span class="font-semibold">{{ $products->count() }}</span> products found
            </p>
            <div class="flex gap-2">
                <button onclick="changeView('grid')" class="view-btn px-3 py-2 rounded-lg bg-blue-100 text-blue-600">
                    <i class="fas fa-th"></i>
                </button>
                <button onclick="changeView('list')" class="view-btn px-3 py-2 rounded-lg text-gray-400 hover:bg-gray-100">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>
        
        <!-- Enhanced Products Grid -->
        <div id="productsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($products as $product)
                <div class="group bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-1">
                    <!-- Product Image with Overlay -->
                    <div class="relative overflow-hidden">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-64 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <i class="fas fa-box text-gray-400 text-5xl"></i>
                            </div>
                        @endif
                        
                        <!-- Quick Actions Overlay -->
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                            <div class="flex space-x-2">
                                <a href="{{ route('products.show', $product->slug ?? $product->id) }}" 
                                   class="bg-white text-blue-600 p-3 rounded-full hover:bg-blue-600 hover:text-white transition-all transform hover:scale-110 shadow-lg">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($product->quantity > 0)
                                    <form method="POST" action="{{ route('cart.add') }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="bg-white text-green-600 p-3 rounded-full hover:bg-green-600 hover:text-white transition-all transform hover:scale-110 shadow-lg">
                                            <i class="fas fa-cart-plus"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Badges -->
                        <div class="absolute top-3 left-3 flex flex-col space-y-2">
                            @if($product->quantity <= 0)
                                <span class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                                    Out of Stock
                                </span>
                            @endif
                            @if($product->compare_price && $product->compare_price > $product->price)
                                <span class="bg-green-600 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                                    -{{ round((($product->compare_price - $product->price) / $product->compare_price) * 100) }}%
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Product Info -->
                    <div class="p-5">
                        <!-- Product Title -->
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                            <a href="{{ route('products.show', $product->slug ?? $product->id) }}" class="hover:text-blue-600 transition-colors">
                                {{ $product->name }}
                            </a>
                        </h3>
                        
                        <!-- Description -->
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ Str::limit($product->description, 80) }}</p>
                        
                        <!-- Categories -->
                        <div class="flex flex-wrap gap-1 mb-4">
                            @foreach($product->categories->take(2) as $category)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                    <i class="fas fa-tag mr-1 text-xs"></i>
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        </div>
                        
                        <!-- Price Section -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex flex-col">
                                <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                    {{ \App\Helpers\CurrencyHelper::format($product->price, $currentCurrency) }}
                                </span>
                                @if($product->compare_price)
                                    <span class="text-sm text-gray-500 line-through">
                                        {{ \App\Helpers\CurrencyHelper::format($product->compare_price, $currentCurrency) }}
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Stock Status -->
                            <div class="text-right">
                                @if($product->quantity > 10)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        In Stock
                                    </span>
                                @elseif($product->quantity > 0)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Only {{ $product->quantity }} left
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Out of Stock
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16">
                    <div class="max-w-md mx-auto">
                        <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-2xl font-semibold text-gray-900 mb-2">No products found</h3>
                        <p class="text-gray-500 mb-6">Try adjusting your filters or search terms to find what you're looking for.</p>
                        <a href="{{ route('products.index') }}" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-3 rounded-lg font-medium transition-all transform hover:scale-105 shadow-lg inline-block">
                            <i class="fas fa-redo mr-2"></i>
                            Clear Filters
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
        
        <!-- Enhanced Pagination -->
        @if($products->hasPages())
            <div class="mt-12 flex justify-center">
                <div class="bg-white rounded-lg shadow-lg px-6 py-4">
                    {{ $products->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script>
function toggleFilters() {
    const filters = document.querySelector('form');
    filters.classList.toggle('hidden');
}

function changeView(view) {
    const grid = document.getElementById('productsGrid');
    const buttons = document.querySelectorAll('.view-btn');
    
    buttons.forEach(btn => {
        btn.classList.remove('bg-blue-100', 'text-blue-600');
        btn.classList.add('text-gray-400', 'hover:bg-gray-100');
    });
    
    event.target.closest('button').classList.remove('text-gray-400', 'hover:bg-gray-100');
    event.target.closest('button').classList.add('bg-blue-100', 'text-blue-600');
    
    if (view === 'list') {
        grid.classList.remove('grid-cols-1', 'sm:grid-cols-2', 'lg:grid-cols-3', 'xl:grid-cols-4');
        grid.classList.add('grid-cols-1');
    } else {
        grid.classList.remove('grid-cols-1');
        grid.classList.add('grid-cols-1', 'sm:grid-cols-2', 'lg:grid-cols-3', 'xl:grid-cols-4');
    }
}
</script>
@endsection
