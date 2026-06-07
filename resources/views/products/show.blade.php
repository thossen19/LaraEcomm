@extends('layouts.app')

@section('title', $product->name)

@php
    $currentCurrency = \App\Models\Setting::get('currency', 'USD');
@endphp

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Breadcrumb -->
    <nav class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <ol class="flex items-center space-x-2 text-sm">
                <li>
                    <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Home</a>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-gray-700">Products</a>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-gray-900 font-medium">{{ $product->name }}</span>
                </li>
            </ol>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Product Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Product Images Gallery -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Main Image -->
                        <div class="md:col-span-2">
                            <div class="relative group">
                                @if($product->image)
                                    <img id="mainImage" 
                                         src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-96 object-cover rounded-xl transition-transform duration-300 group-hover:scale-105">
                                @else
                                    <div class="w-full h-96 bg-gray-100 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400 text-6xl"></i>
                                    </div>
                                @endif
                                
                                <!-- Image Zoom Indicator -->
                                <div class="absolute top-4 right-4 bg-black bg-opacity-50 text-white px-3 py-1 rounded-full text-sm">
                                    <i class="fas fa-search-plus mr-1"></i> Hover to zoom
                                </div>
                            </div>
                        </div>
                        
                        <!-- Thumbnail Gallery -->
                        <div class="md:col-span-2 grid grid-cols-4 gap-2 mt-4">
                            @if($product->image)
                                <div class="cursor-pointer hover:opacity-80 transition-opacity">
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-20 object-cover rounded-lg"
                                         onclick="document.getElementById('mainImage').src='{{ asset('storage/' . $product->image) }}'">
                                </div>
                            @endif
                            <!-- Additional placeholder thumbnails -->
                            @for($i = 1; $i <= 3; $i++)
                                <div class="cursor-pointer hover:opacity-80 transition-opacity">
                                    <div class="w-full h-20 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <!-- Product Title & Rating -->
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                            <div class="flex items-center space-x-4">
                                <!-- Rating Stars -->
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                    <span class="ml-2 text-sm text-gray-600">(4.5)</span>
                                </div>
                                
                                <!-- Reviews Count -->
                                <a href="#reviews" class="text-sm text-blue-600 hover:text-blue-700">
                                    128 Reviews
                                </a>
                            </div>
                        </div>
                        
                        <!-- Wishlist & Share -->
                        <div class="flex items-center space-x-2">
                            <button onclick="toggleWishlist(this, {{ $product->id }})" 
                                    class="p-2 text-gray-400 hover:text-red-500 transition-colors wishlist-btn"
                                    data-product-id="{{ $product->id }}"
                                    data-product-name="{{ $product->name }}"
                                    data-product-image="{{ $product->image ? asset('storage/' . $product->image) : '' }}"
                                    data-product-price="{{ $product->price }}">
                                <i class="far fa-heart text-xl"></i>
                            </button>
                            <button class="p-2 text-gray-400 hover:text-blue-500 transition-colors">
                                <i class="fas fa-share-alt text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Categories & Tags -->
                    <div class="flex flex-wrap gap-2 mb-6">
                        @foreach($product->categories as $category)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                <i class="fas fa-tag mr-1 text-xs"></i>
                                {{ $category->name }}
                            </span>
                        @endforeach
                        @foreach($product->brands as $brand)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                <i class="fas fa-certificate mr-1 text-xs"></i>
                                {{ $brand->name }}
                            </span>
                        @endforeach
                    </div>

                    <!-- Price Section -->
                    <div class="flex items-center justify-between mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl">
                        <div>
                            <div class="flex items-baseline space-x-3">
                                <span class="text-4xl font-bold text-gray-900">{{ \App\Helpers\CurrencyHelper::format($product->price, $currentCurrency) }}</span>
                                @if($product->compare_price)
                                    <span class="text-xl text-gray-500 line-through">{{ \App\Helpers\CurrencyHelper::format($product->compare_price, $currentCurrency) }}</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-2">
                                        {{ round((($product->compare_price - $product->price) / $product->compare_price) * 100) }}% OFF
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Stock Status -->
                            <div class="mt-2">
                                @if($product->quantity > 10)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        In Stock ({{ $product->quantity }}+ available)
                                    </span>
                                @elseif($product->quantity > 0)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        Only {{ $product->quantity }} left
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-2"></i>
                                        Out of Stock
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- SKU -->
                        <div class="text-right">
                            <p class="text-sm text-gray-500">SKU: {{ $product->sku }}</p>
                            @if($product->weight)
                                <p class="text-sm text-gray-500">Weight: {{ $product->weight }}kg</p>
                            @endif
                        </div>
                    </div>

                    <!-- Product Description -->
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Description</h3>
                        <div class="prose prose-lg text-gray-600 leading-relaxed">
                            {!! $product->description !!}
                        </div>
                    </div>

                    <!-- Product Features -->
                    <div class="mb-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Key Features</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center space-x-3 p-3 bg-blue-50 rounded-lg">
                                <i class="fas fa-shipping-fast text-blue-600 text-xl"></i>
                                <div>
                                    <p class="font-medium text-gray-900">Free Shipping</p>
                                    <p class="text-sm text-gray-600">On orders over {{ \App\Helpers\CurrencyHelper::format(50, $currentCurrency) }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3 p-3 bg-green-50 rounded-lg">
                                <i class="fas fa-shield-alt text-green-600 text-xl"></i>
                                <div>
                                    <p class="font-medium text-gray-900">Warranty</p>
                                    <p class="text-sm text-gray-600">2 Year Guarantee</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3 p-3 bg-purple-50 rounded-lg">
                                <i class="fas fa-undo text-purple-600 text-xl"></i>
                                <div>
                                    <p class="font-medium text-gray-900">Easy Returns</p>
                                    <p class="text-sm text-gray-600">30 Day Policy</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3 p-3 bg-orange-50 rounded-lg">
                                <i class="fas fa-headset text-orange-600 text-xl"></i>
                                <div>
                                    <p class="font-medium text-gray-900">24/7 Support</p>
                                    <p class="text-sm text-gray-600">Customer Service</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add to Cart Section -->
                    <div class="border-t pt-6">
                        <form method="POST" action="{{ route('cart.add') }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            
                            <!-- Quantity Selector -->
                            <div class="flex items-center space-x-4">
                                <label class="text-sm font-medium text-gray-700">Quantity:</label>
                                <div class="flex items-center border border-gray-300 rounded-lg">
                                    <button type="button" onclick="decreaseQuantity()" class="px-3 py-2 text-gray-600 hover:bg-gray-100">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->quantity }}"
                                           class="w-20 text-center border-0 focus:ring-0">
                                    <button type="button" onclick="increaseQuantity()" class="px-3 py-2 text-gray-600 hover:bg-gray-100">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row gap-3">
                                @if($product->quantity > 0)
                                    <button type="submit" 
                                            class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-xl font-semibold text-lg transition-all duration-200 transform hover:scale-105">
                                        <i class="fas fa-shopping-cart mr-3"></i>
                                        Add to Cart
                                    </button>
                                    <button type="button" 
                                            class="flex-1 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white px-6 py-3 rounded-xl font-semibold text-lg transition-all duration-200 transform hover:scale-105">
                                        <i class="fas fa-bolt mr-3"></i>
                                        Buy Now
                                    </button>
                                @else
                                    <button disabled class="flex-1 bg-gray-300 text-gray-500 px-6 py-3 rounded-xl font-semibold text-lg cursor-not-allowed">
                                        <i class="fas fa-times mr-3"></i>
                                        Out of Stock
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Product Specifications -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Specifications</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($product->weight)
                            <div class="flex justify-between py-3 border-b">
                                <span class="text-gray-600">Weight:</span>
                                <span class="font-medium">{{ $product->weight }} kg</span>
                            </div>
                        @endif
                        @if($product->length && $product->width && $product->height)
                            <div class="flex justify-between py-3 border-b">
                                <span class="text-gray-600">Dimensions:</span>
                                <span class="font-medium">{{ $product->length }} × {{ $product->width }} × {{ $product->height }} cm</span>
                            </div>
                        @endif
                        <div class="flex justify-between py-3 border-b">
                            <span class="text-gray-600">Material:</span>
                            <span class="font-medium">Premium Quality</span>
                        </div>
                        <div class="flex justify-between py-3 border-b">
                            <span class="text-gray-600">Origin:</span>
                            <span class="font-medium">Imported</span>
                        </div>
                        <div class="flex justify-between py-3">
                            <span class="text-gray-600">Warranty:</span>
                            <span class="font-medium">2 Years</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Info -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Info</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">SKU:</span>
                            <span class="font-mono text-sm">{{ $product->sku }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Availability:</span>
                            @if($product->quantity > 0)
                                <span class="text-green-600 font-medium">In Stock</span>
                            @else
                                <span class="text-red-600 font-medium">Out of Stock</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Shipping:</span>
                            <span class="font-medium">Free</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Delivery:</span>
                            <span class="font-medium">2-3 Days</span>
                        </div>
                    </div>
                </div>

                <!-- Related Products -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Related Products</h3>
                    <div class="space-y-4">
                        <!-- Placeholder related products -->
                        @for($i = 1; $i <= 3; $i++)
                            <div class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer">
                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-box text-gray-400"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">Related Product {{ $i }}</p>
                                    <p class="text-sm text-gray-600">$99.99</p>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Customer Reviews -->
                <div id="reviews" class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Reviews</h3>
                    <div class="space-y-4">
                        <!-- Sample Review -->
                        <div class="border-b pb-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-2">
                                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-gray-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">John Doe</p>
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500">2 days ago</span>
                            </div>
                            <p class="text-gray-600">Great product! Exactly as described and fast shipping.</p>
                        </div>
                        
                        <div class="text-center">
                            <button class="text-blue-600 hover:text-blue-700 font-medium">
                                View All Reviews
                            </button>
                        </div>
                    </div>
                    <div class="flex items-start space-x-2">
                        <i class="fas fa-undo text-green-600 mt-1"></i>
                        <div>
                            <p class="font-medium text-gray-900">30-Day Returns</p>
                            <p class="text-sm text-gray-600">Easy returns policy</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-2">
                        <i class="fas fa-shield-alt text-purple-600 mt-1"></i>
                        <div>
                            <p class="font-medium text-gray-900">Secure Payment</p>
                            <p class="text-sm text-gray-600">SSL encrypted checkout</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Share -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Share this Product</h3>
                <div class="flex space-x-3">
                    <a href="#" class="text-blue-600 hover:text-blue-800">
                        <i class="fab fa-facebook text-2xl"></i>
                    </a>
                    <a href="#" class="text-blue-400 hover:text-blue-600">
                        <i class="fab fa-twitter text-2xl"></i>
                    </a>
                    <a href="#" class="text-pink-600 hover:text-pink-800">
                        <i class="fab fa-instagram text-2xl"></i>
                    </a>
                    <a href="#" class="text-blue-700 hover:text-blue-900">
                        <i class="fab fa-linkedin text-2xl"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Products</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                        <!-- Product Image -->
                        <div class="relative">
                            @if($relatedProduct->image)
                                <img src="{{ asset('storage/' . $relatedProduct->image) }}" 
                                     alt="{{ $relatedProduct->name }}" 
                                     class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-box text-gray-400 text-3xl"></i>
                                </div>
                            @endif
                            
                            @if($relatedProduct->quantity <= 0)
                                <div class="absolute top-2 right-2 bg-red-600 text-white px-2 py-1 rounded text-xs font-medium">
                                    Out of Stock
                                </div>
                            @endif
                        </div>
                        
                        <!-- Product Info -->
                        <div class="p-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">
                                <a href="{{ route('products.show', $relatedProduct) }}" class="hover:text-blue-600">
                                    {{ $relatedProduct->name }}
                                </a>
                            </h3>
                            
                            <p class="text-gray-600 text-sm mb-3">{{ Str::limit($relatedProduct->description, 80) }}</p>
                            
                            <!-- Price and Add to Cart -->
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-lg font-bold text-gray-900">${{ number_format($relatedProduct->price, 2) }}</p>
                                    @if($relatedProduct->compare_price)
                                        <p class="text-sm text-gray-500 line-through">${{ number_format($relatedProduct->compare_price, 2) }}</p>
                                    @endif
                                </div>
                                
                                @if($relatedProduct->quantity > 0)
                                    <form method="POST" action="{{ route('cart.add') }}" class="inline-block">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $relatedProduct->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm font-medium">
                                            <i class="fas fa-cart-plus"></i>
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="bg-gray-300 text-gray-500 px-3 py-2 rounded-md text-sm font-medium cursor-not-allowed">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
