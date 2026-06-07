@extends('layouts.app')

@section('title', 'Online Shopping Site - Buy Mobiles, Electronics, Appliances & More')

@section('styles')
<style>
.flipkart-blue { background-color: #2874f0; }
.flipkart-dark-blue { background-color: #2874f0; }
.flipkart-light-bg { background-color: #f1f3f6; }
.text-flipkart-blue { color: #2874f0; }
.category-card { transition: all 0.3s ease; }
.category-card:hover { transform: translateY(-2px); box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
.product-card { transition: all 0.3s ease; }
.product-card:hover { transform: translateY(-2px); box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
.deal-timer { background: linear-gradient(180deg, #ffe69c 0%, #ffcc00 100%); }
.banner-gradient { background: linear-gradient(180deg, #fff1eb 0%, #ace0f9 100%); }
</style>
@endsection

@section('content')
</header>

<!-- Login Banner -->


<!-- Hero Banner Section -->
<section class="banner-gradient py-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Banner -->
            <div class="lg:col-span-2">
                @php
                    $mainBanner = \App\Models\Banner::where('position', 'main_banner')
                        ->where('status', 'active')
                        ->where(function($query) {
                            $query->whereNull('start_date')
                              ->orWhere('start_date', '<=', now());
                        })
                        ->where(function($query) {
                            $query->whereNull('end_date')
                              ->orWhere('end_date', '>=', now());
                        })
                        ->orderBy('sort_order', 'asc')
                        ->first();
                @endphp
                @if($mainBanner)
                    <a href="{{ $mainBanner->link }}" class="block">
                        <img src="{{ asset('storage/' . $mainBanner->image) }}" 
                             alt="{{ $mainBanner->title }}" 
                             class="w-full h-64 lg:h-96 object-cover rounded-lg shadow-lg">
                    </a>
                @else
                    <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=800&h=400&fit=crop" 
                         alt="Default Banner" 
                         class="w-full h-64 lg:h-96 object-cover rounded-lg shadow-lg">
                @endif
            </div>
            
            <!-- Side Banners -->
            <div class="space-y-6">
                @php
                    $sideBanner1 = \App\Models\Banner::where('position', 'side_banner_1')
                        ->where('status', 'active')
                        ->where(function($query) {
                            $query->whereNull('start_date')
                              ->orWhere('start_date', '<=', now());
                        })
                        ->where(function($query) {
                            $query->whereNull('end_date')
                              ->orWhere('end_date', '>=', now());
                        })
                        ->orderBy('sort_order', 'asc')
                        ->first();
                        
                    $sideBanner2 = \App\Models\Banner::where('position', 'side_banner_2')
                        ->where('status', 'active')
                        ->where(function($query) {
                            $query->whereNull('start_date')
                              ->orWhere('start_date', '<=', now());
                        })
                        ->where(function($query) {
                            $query->whereNull('end_date')
                              ->orWhere('end_date', '>=', now());
                        })
                        ->orderBy('sort_order', 'asc')
                        ->first();
                @endphp
                @if($sideBanner1)
                    <a href="{{ $sideBanner1->link }}" class="block">
                        <img src="{{ asset('storage/' . $sideBanner1->image) }}" 
                             alt="{{ $sideBanner1->title }}" 
                             class="w-full h-44 object-cover rounded-lg shadow-lg">
                    </a>
                @else
                    <img src="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&h=200&fit=crop" 
                         alt="Default Side Banner 1" 
                         class="w-full h-44 object-cover rounded-lg shadow-lg">
                @endif
                
                @if($sideBanner2)
                    <a href="{{ $sideBanner2->link }}" class="block">
                        <img src="{{ asset('storage/' . $sideBanner2->image) }}" 
                             alt="{{ $sideBanner2->title }}" 
                             class="w-full h-44 object-cover rounded-lg shadow-lg">
                    </a>
                @else
                    <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=400&h=200&fit=crop" 
                         alt="Default Side Banner 2" 
                         class="w-full h-44 object-cover rounded-lg shadow-lg">
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Deals of the Day Section -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Deals of the Day</h2>
            <div class="flex items-center space-x-4">
                <div class="bg-gradient-to-r from-red-500 to-orange-500 text-white px-3 py-1 rounded text-sm font-semibold">
                    <i class="fas fa-clock mr-1"></i>
                    <span id="deal-timer">23:59:59</span>
                </div>
                <div class="text-sm text-gray-600">
                    <i class="fas fa-bolt mr-1 text-yellow-500"></i>
                    Limited Time Offers
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @php
                $deals = \App\Models\Deal::active()->current()->ordered()->take(6)->get();
            @endphp
            
            @forelse($deals as $deal)
                <a href="{{ $deal->link ?: '#' }}" class="deal-card bg-white rounded-lg p-3 border border-gray-200 block hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="relative">
                        @if($deal->image)
                            <img src="{{ asset('storage/' . $deal->image) }}" 
                                 alt="{{ $deal->title }}" 
                                 class="w-full h-32 object-cover rounded">
                        @else
                            <div class="w-full h-32 bg-gray-200 rounded flex items-center justify-center">
                                <i class="fas fa-tag text-gray-400 text-2xl"></i>
                            </div>
                        @endif
                        
                        @if($deal->discount_percentage > 0)
                            <span class="absolute top-0 left-0 bg-red-600 text-white text-xs px-2 py-1 rounded">
                                {{ $deal->formatted_discount }}
                            </span>
                        @endif
                        
                        @if($deal->created_at->diffInDays(now()) <= 7)
                            <span class="absolute top-0 right-0 bg-green-600 text-white text-xs px-2 py-1 rounded">New</span>
                        @endif
                    </div>
                    <div class="mt-2">
                        <p class="text-sm font-medium text-gray-800 line-clamp-2">{{ $deal->title }}</p>
                        @if($deal->description)
                            <p class="text-xs text-gray-500 mt-1 line-clamp-1">{{ $deal->description }}</p>
                        @endif
                        <div class="flex items-center mt-2">
                            <span class="text-sm font-bold text-green-600">{{ $deal->formatted_deal_price }}</span>
                            @if($deal->original_price > $deal->deal_price)
                                <span class="text-xs text-gray-500 line-through ml-2">{{ $deal->formatted_original_price }}</span>
                            @endif
                        </div>
                        @if($deal->discount_percentage > 0)
                            <div class="text-xs text-red-600 mt-1">Save {{ $deal->formatted_discount }}</div>
                        @endif
                    </div>
                </a>
            @empty
                <!-- Fallback placeholder deals -->
                <a href="#" class="deal-card bg-white rounded-lg p-3 border border-gray-200 block hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=200&h=250&fit=crop" 
                             alt="Deal of the Day" 
                             class="w-full h-32 object-cover rounded">
                        <span class="absolute top-0 left-0 bg-red-600 text-white text-xs px-2 py-1 rounded">-60%</span>
                    </div>
                    <div class="mt-2">
                        <p class="text-sm font-medium text-gray-800 line-clamp-2">Flash Sale Item</p>
                        <div class="flex items-center mt-2">
                            <span class="text-sm font-bold text-green-600">₹399</span>
                            <span class="text-xs text-gray-500 line-through ml-2">₹999</span>
                        </div>
                        <div class="text-xs text-red-600 mt-1">Save 60%</div>
                    </div>
                </a>
                
                <a href="#" class="deal-card bg-white rounded-lg p-3 border border-gray-200 block hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=200&h=250&fit=crop" 
                             alt="Deal of the Day" 
                             class="w-full h-32 object-cover rounded">
                        <span class="absolute top-0 left-0 bg-red-600 text-white text-xs px-2 py-1 rounded">-45%</span>
                    </div>
                    <div class="mt-2">
                        <p class="text-sm font-medium text-gray-800 line-clamp-2">Limited Time Deal</p>
                        <div class="flex items-center mt-2">
                            <span class="text-sm font-bold text-green-600">₹549</span>
                            <span class="text-xs text-gray-500 line-through ml-2">₹999</span>
                        </div>
                        <div class="text-xs text-red-600 mt-1">Save 45%</div>
                    </div>
                </a>
                
                <a href="#" class="deal-card bg-white rounded-lg p-3 border border-gray-200 block hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=200&h=250&fit=crop" 
                             alt="Deal of the Day" 
                             class="w-full h-32 object-cover rounded">
                        <span class="absolute top-0 left-0 bg-red-600 text-white text-xs px-2 py-1 rounded">-70%</span>
                    </div>
                    <div class="mt-2">
                        <p class="text-sm font-medium text-gray-800 line-clamp-2">Mega Sale Item</p>
                        <div class="flex items-center mt-2">
                            <span class="text-sm font-bold text-green-600">₹299</span>
                            <span class="text-xs text-gray-500 line-through ml-2">₹999</span>
                        </div>
                        <div class="text-xs text-red-600 mt-1">Save 70%</div>
                    </div>
                </a>
                
                <a href="#" class="deal-card bg-white rounded-lg p-3 border border-gray-200 block hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=200&h=250&fit=crop" 
                             alt="Deal of the Day" 
                             class="w-full h-32 object-cover rounded">
                        <span class="absolute top-0 left-0 bg-red-600 text-white text-xs px-2 py-1 rounded">-50%</span>
                    </div>
                    <div class="mt-2">
                        <p class="text-sm font-medium text-gray-800 line-clamp-2">Best Seller Deal</p>
                        <div class="flex items-center mt-2">
                            <span class="text-sm font-bold text-green-600">₹499</span>
                            <span class="text-xs text-gray-500 line-through ml-2">₹999</span>
                        </div>
                        <div class="text-xs text-red-600 mt-1">Save 50%</div>
                    </div>
                </a>
                
                <a href="#" class="deal-card bg-white rounded-lg p-3 border border-gray-200 block hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=200&h=250&fit=crop" 
                             alt="Deal of the Day" 
                             class="w-full h-32 object-cover rounded">
                        <span class="absolute top-0 left-0 bg-red-600 text-white text-xs px-2 py-1 rounded">-40%</span>
                    </div>
                    <div class="mt-2">
                        <p class="text-sm font-medium text-gray-800 line-clamp-2">Hot Deal</p>
                        <div class="flex items-center mt-2">
                            <span class="text-sm font-bold text-green-600">₹699</span>
                            <span class="text-xs text-gray-500 line-through ml-2">₹1,199</span>
                        </div>
                        <div class="text-xs text-red-600 mt-1">Save 40%</div>
                    </div>
                </a>
                
                <a href="#" class="deal-card bg-white rounded-lg p-3 border border-gray-200 block hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1572569511254-d8f925e2c636?w=200&h=250&fit=crop" 
                             alt="Deal of the Day" 
                             class="w-full h-32 object-cover rounded">
                        <span class="absolute top-0 left-0 bg-red-600 text-white text-xs px-2 py-1 rounded">-55%</span>
                    </div>
                    <div class="mt-2">
                        <p class="text-sm font-medium text-gray-800 line-clamp-2">Premium Deal</p>
                        <div class="flex items-center mt-2">
                            <span class="text-sm font-bold text-green-600">₹899</span>
                            <span class="text-xs text-gray-500 line-through ml-2">₹1,999</span>
                        </div>
                        <div class="text-xs text-red-600 mt-1">Save 55%</div>
                    </div>
                </a>
            @endforelse
        </div>
        
        @if($deals->count() > 0)
            <div class="mt-6 text-center">
                <a href="/deals" class="text-blue-600 hover:text-blue-500 font-medium">
                    View All Deals →
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Categories Grid -->
<section class="py-8 flipkart-light-bg">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Shop by Category</h2>
        
        <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-9 gap-4">
            <!-- Category 1 -->
            <div class="category-card bg-white rounded-lg p-4 text-center border border-gray-200">
                <img src="https://images.unsplash.com/photo-1605462863863-10d9e47e15ee?w=100&h=100&fit=crop" 
                     alt="Grocery" 
                     class="w-16 h-16 mx-auto mb-2 object-contain">
                <p class="text-xs text-gray-700">Grocery</p>
            </div>
            
            <!-- Category 2 -->
            <div class="category-card bg-white rounded-lg p-4 text-center border border-gray-200">
                <img src="https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=100&h=100&fit=crop" 
                     alt="Mobiles" 
                     class="w-16 h-16 mx-auto mb-2 object-contain">
                <p class="text-xs text-gray-700">Mobiles</p>
            </div>
            
            <!-- Category 3 -->
            <div class="category-card bg-white rounded-lg p-4 text-center border border-gray-200">
                <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=100&h=100&fit=crop" 
                     alt="Fashion" 
                     class="w-16 h-16 mx-auto mb-2 object-contain">
                <p class="text-xs text-gray-700">Fashion</p>
            </div>
            
            <!-- Category 4 -->
            <div class="category-card bg-white rounded-lg p-4 text-center border border-gray-200">
                <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=100&h=100&fit=crop" 
                     alt="Electronics" 
                     class="w-16 h-16 mx-auto mb-2 object-contain">
                <p class="text-xs text-gray-700">Electronics</p>
            </div>
            
            <!-- Category 5 -->
            <div class="category-card bg-white rounded-lg p-4 text-center border border-gray-200">
                <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=100&h=100&fit=crop" 
                     alt="Home" 
                     class="w-16 h-16 mx-auto mb-2 object-contain">
                <p class="text-xs text-gray-700">Home</p>
            </div>
            
            <!-- Category 6 -->
            <div class="category-card bg-white rounded-lg p-4 text-center border border-gray-200">
                <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=100&h=100&fit=crop" 
                     alt="Appliances" 
                     class="w-16 h-16 mx-auto mb-2 object-contain">
                <p class="text-xs text-gray-700">Appliances</p>
            </div>
            
            <!-- Category 7 -->
            <div class="category-card bg-white rounded-lg p-4 text-center border border-gray-200">
                <img src="https://images.unsplash.com/photo-1490474418585-ba9bad8fd0ea?w=100&h=100&fit=crop" 
                     alt="Travel" 
                     class="w-16 h-16 mx-auto mb-2 object-contain">
                <p class="text-xs text-gray-700">Travel</p>
            </div>
            
            <!-- Category 8 -->
            <div class="category-card bg-white rounded-lg p-4 text-center border border-gray-200">
                <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=100&h=100&fit=crop" 
                     alt="Beauty" 
                     class="w-16 h-16 mx-auto mb-2 object-contain">
                <p class="text-xs text-gray-700">Beauty</p>
            </div>
            
            <!-- Category 9 -->
            <div class="category-card bg-white rounded-lg p-4 text-center border border-gray-200">
                <img src="https://images.unsplash.com/photo-1572569511254-d8f925e2c636?w=100&h=100&fit=crop" 
                     alt="Two Wheelers" 
                     class="w-16 h-16 mx-auto mb-2 object-contain">
                <p class="text-xs text-gray-700">Two Wheelers</p>
            </div>
        </div>
    </div>
</section>

<!-- Fashion Section -->
<section class="py-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Best of Fashion</h2>
            <a href="{{ route('products.index') }}?fashion=1" class="text-flipkart-blue font-semibold hover:underline">
                View All <i class="fas fa-chevron-right ml-1"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @php
                $bestFashionProducts = \App\Models\Product::where('is_best_fashion', true)
                    ->where('is_active', true)
                    ->with('categories')
                    ->orderBy('created_at', 'desc')
                    ->take(8)
                    ->get();
            @endphp
            
            @forelse($bestFashionProducts as $product)
                <a href="{{ route('products.show', $product->slug ?? $product->id) }}" class="product-card bg-white rounded-lg p-3 border border-gray-200 block hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="relative">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-40 object-cover">
                        @else
                            <img src="https://images.unsplash.com/photo-1445205170230-053b83016050?w=200&h=250&fit=crop" 
                                 alt="Fashion" 
                                 class="w-full h-40 object-cover">
                        @endif
                        
                        @if($product->compare_price && $product->compare_price > $product->price)
                            <span class="absolute top-0 left-0 bg-red-600 text-white text-xs px-2 py-1 rounded">
                                -{{ round((($product->compare_price - $product->price) / $product->compare_price) * 100) }}%
                            </span>
                        @elseif($product->created_at->diffInDays(now()) <= 7)
                            <span class="absolute top-0 left-0 bg-green-600 text-white text-xs px-2 py-1 rounded">New</span>
                        @endif
                    </div>
                    <div class="mt-2">
                        <p class="text-sm text-gray-800 line-clamp-2">{{ $product->name }}</p>
                        <div class="flex items-center mt-1">
                            <span class="text-lg font-bold">₹{{ number_format($product->price, 0) }}</span>
                            @if($product->compare_price && $product->compare_price > $product->price)
                                <span class="text-sm text-gray-500 line-through ml-2">₹{{ number_format($product->compare_price, 0) }}</span>
                            @endif
                        </div>
                        <div class="flex items-center text-xs text-gray-600 mt-1">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= 4)
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="ml-1">(4.{{ rand(0, 9) }})</span>
                        </div>
                    </div>
                </a>
            @empty
                <!-- Fallback static products if no best fashion products found -->
                <a href="{{ route('products.show', 'mens-casual-shirt') }}" class="product-card bg-white rounded-lg p-3 border border-gray-200 block hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1445205170230-053b83016050?w=200&h=250&fit=crop" 
                             alt="Fashion" 
                             class="w-full h-40 object-cover">
                        <span class="absolute top-0 left-0 bg-green-600 text-white text-xs px-2 py-1 rounded">New</span>
                    </div>
                    <div class="mt-2">
                        <p class="text-sm text-gray-800 line-clamp-2">Men's Casual Shirt Premium Quality</p>
                        <div class="flex items-center mt-1">
                            <span class="text-lg font-bold">₹499</span>
                            <span class="text-sm text-gray-500 line-through ml-2">₹1,299</span>
                        </div>
                        <div class="flex items-center text-xs text-gray-600 mt-1">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                            <span class="ml-1">(4.2)</span>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('products.show', 'womens-dress') }}" class="product-card bg-white rounded-lg p-3 border border-gray-200 block hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=200&h=250&fit=crop" 
                             alt="Fashion" 
                             class="w-full h-40 object-cover">
                        <span class="absolute top-0 left-0 bg-red-600 text-white text-xs px-2 py-1 rounded">-40%</span>
                    </div>
                    <div class="mt-2">
                        <p class="text-sm text-gray-800 line-clamp-2">Women's Ethnic Wear Collection</p>
                        <div class="flex items-center mt-1">
                            <span class="text-lg font-bold">₹799</span>
                            <span class="text-sm text-gray-500 line-through ml-2">₹1,399</span>
                        </div>
                        <div class="flex items-center text-xs text-gray-600 mt-1">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                            <span class="ml-1">(4.0)</span>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('products.show', 'mens-jeans') }}" class="product-card bg-white rounded-lg p-3 border border-gray-200 block hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=200&h=250&fit=crop" 
                             alt="Fashion" 
                             class="w-full h-40 object-cover">
                    </div>
                    <div class="mt-2">
                        <p class="text-sm text-gray-800 line-clamp-2">Sports Shoes for Men & Women</p>
                        <div class="flex items-center mt-1">
                            <span class="text-lg font-bold">₹899</span>
                            <span class="text-sm text-gray-500 line-through ml-2">₹1,499</span>
                        </div>
                        <div class="flex items-center text-xs text-gray-600 mt-1">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                            <span class="ml-1">(4.0)</span>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('products.show', 'womens-handbag') }}" class="product-card bg-white rounded-lg p-3 border border-gray-200 block hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=200&h=250&fit=crop" 
                             alt="Fashion" 
                             class="w-full h-40 object-cover">
                        <span class="absolute top-0 left-0 bg-orange-600 text-white text-xs px-2 py-1 rounded">Hot</span>
                    </div>
                    <div class="mt-2">
                        <p class="text-sm text-gray-800 line-clamp-2">Designer Handbag Collection</p>
                        <div class="flex items-center mt-1">
                            <span class="text-lg font-bold">₹1,299</span>
                            <span class="text-sm text-gray-500 line-through ml-2">₹2,499</span>
                        </div>
                        <div class="flex items-center text-xs text-gray-600 mt-1">
                            <div class="flex text-yellow-400">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="ml-1">(4.7)</span>
                        </div>
                    </div>
                </a>
            @endforelse
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="py-8 flipkart-light-bg">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Featured Products</h2>
            <a href="{{ route('products.index') }}" class="text-flipkart-blue font-semibold hover:underline">
                View All <i class="fas fa-chevron-right ml-1"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @php
                $featuredProducts = \App\Models\Product::where('is_featured', true)
                    ->where('is_active', true)
                    ->with('categories')
                    ->orderBy('created_at', 'desc')
                    ->take(8)
                    ->get();
            @endphp
            
            @forelse($featuredProducts as $product)
                <a href="{{ route('products.show', $product->slug ?? $product->id) }}" class="product-card bg-white rounded-lg p-3 border border-gray-200 block hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="relative">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-40 object-cover">
                        @else
                            <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200&h=250&fit=crop" 
                                 alt="Product" 
                                 class="w-full h-40 object-cover">
                        @endif
                        
                        @if($product->sale_price && $product->sale_price < $product->price)
                            @php
                                $discount = round((($product->price - $product->sale_price) / $product->price) * 100);
                            @endphp
                            <span class="absolute top-0 left-0 bg-red-600 text-white text-xs px-2 py-1 rounded">
                                -{{ $discount }}%
                            </span>
                        @endif
                        
                        @if($product->created_at->diffInDays(now()) <= 7)
                            <span class="absolute top-0 right-0 bg-green-600 text-white text-xs px-2 py-1 rounded">New</span>
                        @endif
                    </div>
                    <div class="mt-2">
                        <p class="text-sm text-gray-800 line-clamp-2">{{ $product->name }}</p>
                        @if($product->categories && $product->categories->isNotEmpty())
                            <p class="text-xs text-gray-500 mt-1">{{ $product->categories->first()->name }}</p>
                        @endif
                        <div class="flex items-center mt-1">
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <span class="text-lg font-bold">₹{{ number_format($product->sale_price, 2) }}</span>
                                <span class="text-sm text-gray-500 line-through ml-2">₹{{ number_format($product->price, 2) }}</span>
                            @else
                                <span class="text-lg font-bold">₹{{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>
                        @if($product->weight > 0)
                            <div class="text-xs text-green-600">Free delivery</div>
                        @endif
                        @if($product->track_quantity && $product->quantity > 0)
                            <div class="text-xs text-green-600">In Stock ({{ $product->quantity }})</div>
                        @elseif($product->track_quantity && $product->quantity <= 0)
                            <div class="text-xs text-red-600">Out of Stock</div>
                        @endif
                    </div>
                </a>
            @empty
                <!-- Fallback products if no featured products exist -->
                <a href="#" class="product-card bg-white rounded-lg p-3 border border-gray-200 block hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1598327105666-5b89327aff97?w=200&h=250&fit=crop" 
                             alt="Featured Product" 
                             class="w-full h-40 object-cover">
                        <span class="absolute top-0 left-0 bg-orange-600 text-white text-xs px-2 py-1 rounded">Featured</span>
                    </div>
                    <div class="mt-2">
                        <p class="text-sm text-gray-800 line-clamp-2">Premium Featured Product</p>
                        <div class="flex items-center mt-1">
                            <span class="text-lg font-bold">₹999</span>
                            <span class="text-sm text-gray-500 line-through ml-2">₹1,499</span>
                        </div>
                        <div class="text-xs text-green-600">Free delivery</div>
                    </div>
                </a>
                
                <a href="#" class="product-card bg-white rounded-lg p-3 border border-gray-200 block hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=200&h=250&fit=crop" 
                             alt="Featured Product" 
                             class="w-full h-40 object-cover">
                        <span class="absolute top-0 left-0 bg-orange-600 text-white text-xs px-2 py-1 rounded">Featured</span>
                    </div>
                    <div class="mt-2">
                        <p class="text-sm text-gray-800 line-clamp-2">Best Selling Item</p>
                        <div class="flex items-center mt-1">
                            <span class="text-lg font-bold">₹1,299</span>
                        </div>
                        <div class="text-xs text-green-600">Free delivery</div>
                    </div>
                </a>
                
                <a href="#" class="product-card bg-white rounded-lg p-3 border border-gray-200 block hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=200&h=250&fit=crop" 
                             alt="Featured Product" 
                             class="w-full h-40 object-cover">
                        <span class="absolute top-0 left-0 bg-orange-600 text-white text-xs px-2 py-1 rounded">Featured</span>
                    </div>
                    <div class="mt-2">
                        <p class="text-sm text-gray-800 line-clamp-2">Top Rated Product</p>
                        <div class="flex items-center mt-1">
                            <span class="text-lg font-bold">₹799</span>
                        </div>
                        <div class="text-xs text-green-600">Free delivery</div>
                    </div>
                </a>
                
                <a href="#" class="product-card bg-white rounded-lg p-3 border border-gray-200 block hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1598928506311-c55ded91e20b?w=200&h=250&fit=crop" 
                             alt="Featured Product" 
                             class="w-full h-40 object-cover">
                        <span class="absolute top-0 left-0 bg-orange-600 text-white text-xs px-2 py-1 rounded">Featured</span>
                    </div>
                    <div class="mt-2">
                        <p class="text-sm text-gray-800 line-clamp-2">Limited Time Offer</p>
                        <div class="flex items-center mt-1">
                            <span class="text-lg font-bold">₹599</span>
                            <span class="text-sm text-gray-500 line-through ml-2">₹999</span>
                        </div>
                        <div class="text-xs text-green-600">Free delivery</div>
                    </div>
                </a>
                <a href="#" class="deal-card bg-white rounded-lg p-3 border border-gray-200 block hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=200&h=250&fit=crop" 
                             alt="Deal of the Day" 
                             class="w-full h-32 object-cover rounded">
                        <span class="absolute top-0 left-0 bg-red-600 text-white text-xs px-2 py-1 rounded">-40%</span>
                    </div>
                    <div class="mt-2">
                        <p class="text-sm font-medium text-gray-800 line-clamp-2">Hot Deal</p>
                        <div class="flex items-center mt-2">
                            <span class="text-sm font-bold text-green-600">₹599</span>
                            <span class="text-xs text-gray-500 line-through ml-2">₹999</span>
                        </div>
                        <div class="text-xs text-red-600 mt-1">Save 40%</div>
                    </div>
                </a>
                
                <a href="#" class="deal-card bg-white rounded-lg p-3 border border-gray-200 block hover:shadow-lg transition-shadow cursor-pointer">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=200&h=250&fit=crop" 
                             alt="Deal of the Day" 
                             class="w-full h-32 object-cover rounded">
                        <span class="absolute top-0 left-0 bg-red-600 text-white text-xs px-2 py-1 rounded">-50%</span>
                    </div>
                    <div class="mt-2">
                        <p class="text-sm font-medium text-gray-800 line-clamp-2">Best Seller Deal</p>
                        <div class="flex items-center mt-2">
                            <span class="text-sm font-bold text-green-600">₹499</span>
                            <span class="text-xs text-gray-500 line-through ml-2">₹999</span>
                        </div>
                        <div class="text-xs text-red-600 mt-1">Save 50%</div>
                    </div>
                </a>
            @endforelse
        </div>
        
        @if($deals->count() > 0)
            <div class="mt-6 text-center">
                <a href="/deals" class="text-blue-600 hover:text-blue-500 font-medium">
                    View All Deals →
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-800 text-white py-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-lg font-bold mb-4">About</h3>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="#" class="hover:text-white">Contact Us</a></li>
                    <li><a href="#" class="hover:text-white">About Us</a></li>
                    <li><a href="#" class="hover:text-white">Careers</a></li>
                    <li><a href="#" class="hover:text-white">Flipkart Stories</a></li>
                    <li><a href="#" class="hover:text-white">Press</a></li>
                </ul>
            </div>
            
            <div>
                <h3 class="text-lg font-bold mb-4">Help</h3>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="#" class="hover:text-white">Payments</a></li>
                    <li><a href="#" class="hover:text-white">Shipping</a></li>
                    <li><a href="#" class="hover:text-white">Cancellation & Returns</a></li>
                    <li><a href="#" class="hover:text-white">FAQ</a></li>
                    <li><a href="#" class="hover:text-white">Report Infringement</a></li>
                </ul>
            </div>
            
            <div>
                <h3 class="text-lg font-bold mb-4">Policy</h3>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="#" class="hover:text-white">Return Policy</a></li>
                    <li><a href="#" class="hover:text-white">Terms Of Use</a></li>
                    <li><a href="#" class="hover:text-white">Security</a></li>
                    <li><a href="#" class="hover:text-white">Privacy</a></li>
                    <li><a href="#" class="hover:text-white">Sitemap</a></li>
                </ul>
            </div>
            
            <div>
                <h3 class="text-lg font-bold mb-4">Social</h3>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="#" class="hover:text-white">Facebook</a></li>
                    <li><a href="#" class="hover:text-white">Twitter</a></li>
                    <li><a href="#" class="hover:text-white">YouTube</a></li>
                </ul>
            </div>
        </div>
        
        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-400">
            <p>&copy; 2024 Flipkart Clone. All rights reserved.</p>
        </div>
    </div>
</footer>

<script>
// Deal Timer Countdown
function startDealTimer() {
    let hours = 22;
    let minutes = 4;
    let seconds = 31;
    
    setInterval(() => {
        seconds--;
        if (seconds < 0) {
            seconds = 59;
            minutes--;
            if (minutes < 0) {
                minutes = 59;
                hours--;
                if (hours < 0) {
                    hours = 23;
                }
            }
        }
        
        const timer = document.getElementById('deal-timer');
        if (timer) {
            timer.textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')} Left`;
        }
    }, 1000);
}

// Enhanced Search Functionality
function initializeSearch() {
    const searchInput = document.getElementById('searchInput');
    const searchSuggestions = document.getElementById('searchSuggestions');
    
    if (searchInput && searchSuggestions) {
        // Show suggestions when input is focused
        searchInput.addEventListener('focus', () => {
            searchSuggestions.classList.remove('hidden');
        });
        
        // Hide suggestions when clicking outside
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
                searchSuggestions.classList.add('hidden');
            }
        });
        
        // Handle search input
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.trim();
            if (query.length > 0) {
                // Here you can implement AJAX search
                console.log('Searching for:', query);
            }
        });
        
        // Handle search button click
        const searchButton = searchInput.nextElementSibling;
        if (searchButton) {
            searchButton.addEventListener('click', () => {
                const query = searchInput.value.trim();
                if (query) {
                    console.log('Performing search for:', query);
                    // Redirect to search results page
                    // window.location.href = `/search?q=${encodeURIComponent(query)}`;
                }
            });
        }
        
        // Handle Enter key
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                const query = searchInput.value.trim();
                if (query) {
                    console.log('Performing search for:', query);
                    // Redirect to search results page
                    // window.location.href = `/search?q=${encodeURIComponent(query)}`;
                }
            }
        });
    }
}

// Initialize search functionality
document.addEventListener('DOMContentLoaded', () => {
    startDealTimer();
    initializeSearch();
});
</script>
@endsection
