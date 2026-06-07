@extends('layouts.app')

@section('title', 'Online Shopping Site - Buy Mobiles, Electronics, Appliances & More')

@php
    $currentCurrency = \App\Models\Setting::get('currency', 'USD');
@endphp

@section('styles')
<style>
.flipkart-blue { background-color: #2874f0; }
.flipkart-dark-blue { background-color: #2874f0; }
.flipkart-light-bg { background-color: #f1f3f6; }
.text-flipkart-blue { color: #2874f0; }
.category-card { transition: all 0.3s ease; }
.category-card:hover { transform: translateY(-2px); box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
.product-card { 
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); 
    border-radius: 16px;
    overflow: hidden;
    background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
    box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
    position: relative;
}
.product-card:hover { 
    transform: translateY(-8px) scale(1.02); 
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
    background: linear-gradient(145deg, #ffffff 0%, #f1f5f9 100%);
}
.product-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}
.product-card:hover::before {
    transform: scaleX(1);
}

/* Enhanced Product Image Container */
.product-card .relative {
    position: relative;
    overflow: hidden;
    border-radius: 12px;
}

.product-card img {
    transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    filter: brightness(1);
}

.product-card:hover img {
    transform: scale(1.08);
    filter: brightness(1.05);
}

/* Enhanced Product Badges */
.product-card .absolute span {
    font-weight: 600;
    font-size: 11px;
    padding: 4px 8px;
    border-radius: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.product-card .bg-red-600 {
    background: linear-gradient(135deg, #ef4444, #dc2626) !important;
}

.product-card .bg-green-600 {
    background: linear-gradient(135deg, #10b981, #059669) !important;
}

.product-card .bg-orange-600 {
    background: linear-gradient(135deg, #f97316, #ea580c) !important;
}

/* Enhanced Product Content */
.product-card .mt-2 {
    padding: 0 4px;
}

.product-card .text-gray-800 {
    font-weight: 500;
    font-size: 14px;
    line-height: 1.4;
    color: #1f2937;
    margin-bottom: 8px;
    min-height: 2.8em;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-card .text-lg {
    font-weight: 700;
    font-size: 18px;
    color: #0f172a;
    background: linear-gradient(135deg, #0f172a, #334155);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.product-card .text-gray-500 {
    color: #64748b;
    font-weight: 400;
}

/* Enhanced Star Rating */
.product-card .text-yellow-400 {
    color: #fbbf24 !important;
    text-shadow: 0 1px 2px rgba(251, 191, 36, 0.3);
}

.product-card .text-xs {
    color: #64748b;
    font-weight: 500;
}

/* Quick Actions Overlay */
.product-card .relative::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.05) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-card:hover .relative::after {
    opacity: 1;
}

/* Enhanced Category Cards */
.category-card { 
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
    border-radius: 12px;
    background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
    box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
}
.category-card:hover { 
    transform: translateY(-4px) scale(1.05); 
    box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
    background: linear-gradient(145deg, #ffffff 0%, #f1f5f9 100%);
}

.category-card .w-16 {
    transition: all 0.3s ease;
}

.category-card:hover .w-16 {
    transform: scale(1.1);
}
.deal-timer { background: linear-gradient(180deg, #ffe69c 0%, #ffcc00 100%); }
.banner-gradient { background: linear-gradient(180deg, #fff1eb 0%, #ace0f9 100%); }

/* Banner Slider Styles */
.banner-slider {
    position: relative;
    overflow: hidden;
    border-radius: 0.5rem;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    height: 400px;
    width: 100%;
    display: block;
}

.banner-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 400px;
    opacity: 0;
    transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    transform: translateX(100%);
    pointer-events: none;
    display: flex;
    align-items: center;
    justify-content: center;
}

.banner-slide img {
    width: 100%;
    height: auto;
    max-height: 400px;
    object-fit: cover;
    display: block;
}

.banner-slide.active {
    opacity: 1;
    transform: translateX(0);
    position: relative;
    z-index: 2;
    pointer-events: auto;
}

.banner-slide.prev {
    transform: translateX(-100%);
    z-index: 1;
}

/* Ensure first slide is visible by default */
.banner-slider .banner-slide:first-child {
    position: relative;
    transform: translateX(0);
    opacity: 1;
    z-index: 2;
    pointer-events: auto;
}

/* Slider Effects */
.slider-effect-fade .banner-slide {
    transition: opacity 1s ease-in-out, transform 0s;
    transform: none;
}

.slider-effect-fade .banner-slide.active {
    opacity: 1;
}

.slider-effect-fade .banner-slide.prev {
    opacity: 0;
}

.slider-effect-slide .banner-slide {
    transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.8s ease;
}

.slider-effect-zoom .banner-slide {
    transition: transform 1s ease, opacity 0.8s ease;
}

.slider-effect-zoom .banner-slide.active {
    animation: zoomIn 1s ease-out;
}

.slider-effect-zoom .banner-slide.prev {
    transform: translateX(-100%) scale(0.8);
    opacity: 0;
}

@keyframes zoomIn {
    0% {
        transform: scale(1.2);
        opacity: 0;
    }
    50% {
        transform: scale(1.1);
        opacity: 0.8;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

/* Navigation Controls */
.slider-nav {
    position: absolute !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    background: rgba(0, 0, 0, 0.6);
    color: white;
    border: none;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    transition: all 0.3s ease;
    z-index: 100 !important;
    backdrop-filter: blur(5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
}

.slider-nav:hover {
    background: rgba(0, 0, 0, 0.8);
    transform: translateY(-50%) scale(1.1) !important;
    box-shadow: 0 6px 20px rgba(0,0,0,0.5);
}

.slider-nav.prev {
    left: 20px !important;
    right: auto !important;
    bottom: auto !important;
	
}

.slider-nav.next {
    right: 20px !important;
    left: auto !important;
    bottom: auto !important;
}

/* Banner Content Overlay */
.banner-content {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 100%);
    color: white;
    padding: 30px;
    transform: translateY(100%);
    transition: transform 0.5s ease;
}

.banner-slide.active .banner-content {
    transform: translateY(0);
}

.banner-content h3 {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 10px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}

.banner-content p {
    font-size: 1.1rem;
    margin-bottom: 20px;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
}

.banner-btn {
    display: inline-block;
    background: #2874f0;
    color: white;
    padding: 12px 24px;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(40, 116, 240, 0.3);
}

.banner-btn:hover {
    background: #1a5fcc;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(40, 116, 240, 0.4);
    color: white;
}

/* Responsive Design */
@media (max-width: 768px) {
    .slider-nav {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
    
    .slider-nav.prev {
        left: 10px;
    }
    
    .slider-nav.next {
        right: 10px;
    }
    
    .banner-content {
        padding: 20px;
    }
    
    .banner-content h3 {
        font-size: 1.5rem;
    }
    
    .banner-content p {
        font-size: 0.9rem;
    }
}
</style>
@endsection

@php
    $currentCurrency = \App\Models\Setting::get('currency', 'USD');
@endphp

@section('content')
</header>

<!-- Login Banner -->


<!-- Hero Banner Section -->
<section class="banner-gradient py-6">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
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
                        
                    // If no banner in database, create default one
                    if(!$mainBanner) {
                        $mainBanner = (object) [
                            'id' => 1,
                            'title' => 'Mega Sale Festival',
                            'image' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=800&h=400&fit=crop',
                            'link' => '/deals',
                            'description' => 'Up to 70% off on selected items'
                        ];
                    }
                @endphp
                
                <a href="{{ $mainBanner->link }}" class="block group">
                    <div class="relative overflow-hidden rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                        @if(isset($mainBanner->image) && str_starts_with($mainBanner->image, 'http'))
                            <img src="{{ $mainBanner->image }}" 
                                 alt="{{ $mainBanner->title }}"
                                 class="w-full h-96 object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <img src="{{ asset('storage/' . $mainBanner->image) }}" 
                                 alt="{{ $mainBanner->title }}"
                                 class="w-full h-96 object-cover group-hover:scale-110 transition-transform duration-500">
                        @endif
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-6 text-white transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                            <h3 class="text-2xl font-bold mb-2">{{ $mainBanner->title }}</h3>
                            <p class="text-base opacity-90 mb-3">{{ $mainBanner->description ?? 'Amazing deals waiting for you!' }}</p>
                            <span class="inline-flex items-center px-4 py-2 bg-white text-gray-900 rounded-full text-sm font-semibold hover:bg-gray-100 transition-colors">
                                Shop Now →
                            </span>
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- Side Banners -->
            <div class="space-y-4">
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
                    <a href="{{ $sideBanner1->link }}" class="block group">
                        <div class="relative overflow-hidden rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                            <img src="{{ asset('storage/' . $sideBanner1->image) }}" 
                                 alt="{{ $sideBanner1->title }}" 
                                 class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-4 text-white transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                <h3 class="font-bold text-sm mb-1">{{ $sideBanner1->title }}</h3>
                                <p class="text-xs opacity-90">Shop Now →</p>
                            </div>
                        </div>
                    </a>
                @else
                    <div class="relative overflow-hidden rounded-xl shadow-lg">
                        <img src="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=400&h=200&fit=crop" 
                             alt="Default Side Banner 1" 
                             class="w-full h-48 object-cover">
                    </div>
                @endif
                
                @if($sideBanner2)
                    <a href="{{ $sideBanner2->link }}" class="block group">
                        <div class="relative overflow-hidden rounded-xl shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                            <img src="{{ asset('storage/' . $sideBanner2->image) }}" 
                                 alt="{{ $sideBanner2->title }}" 
                                 class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-4 text-white transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                <h3 class="font-bold text-sm mb-1">{{ $sideBanner2->title }}</h3>
                                <p class="text-xs opacity-90">Shop Now →</p>
                            </div>
                        </div>
                    </a>
                @else
                    <div class="relative overflow-hidden rounded-xl shadow-lg">
                        <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=400&h=200&fit=crop" 
                             alt="Default Side Banner 2" 
                             class="w-full h-48 object-cover">
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Big Sale Event Section -->
@php
    $bigSaleEvent = \App\Models\BigSaleEvent::active()
        ->currentlyActive()
        ->featured()
        ->orderBy('sort_order', 'asc')
        ->first();
@endphp
@if($bigSaleEvent)
<section class="relative overflow-hidden" style="background: linear-gradient(135deg, {{ $bigSaleEvent->background_color }} 0%, {{ $bigSaleEvent->background_color }}dd 100%);">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            <div class="text-white space-y-6">
                @if($bigSaleEvent->subtitle)
                    <div class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full">
                        <i class="fas fa-fire mr-2"></i>
                        <span class="text-sm font-semibold">{{ $bigSaleEvent->subtitle }}</span>
                    </div>
                @endif
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight">
                    {{ $bigSaleEvent->title }}
                </h1>
                
                <p class="text-lg md:text-xl text-white/90 leading-relaxed">
                    {!! $bigSaleEvent->description !!}
                </p>
                
                @if($bigSaleEvent->discount_percentage || ($bigSaleEvent->original_price && $bigSaleEvent->sale_price))
                    <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                        @if($bigSaleEvent->discount_percentage)
                            <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4">
                                <div class="text-3xl md:text-4xl font-bold">{{ $bigSaleEvent->formatted_discount }}</div>
                                <div class="text-sm text-white/80">DISCOUNT</div>
                            </div>
                        @endif
                        
                        @if($bigSaleEvent->original_price && $bigSaleEvent->sale_price)
                            <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4">
                                <div class="flex items-baseline gap-2">
                                    <span class="text-2xl font-bold line-through text-white/60">{{ $bigSaleEvent->formatted_original_price }}</span>
                                    <span class="text-3xl md:text-4xl font-bold">{{ $bigSaleEvent->formatted_sale_price }}</span>
                                </div>
                                <div class="text-sm text-white/80">Save {{ $bigSaleEvent->formatted_savings_amount }}</div>
                            </div>
                        @endif
                    </div>
                @endif
                
                <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                    @if($bigSaleEvent->button_link)
                        <a href="{{ $bigSaleEvent->button_link }}" 
                           class="inline-flex items-center px-8 py-4 bg-white text-gray-900 rounded-full font-bold text-lg hover:bg-gray-100 transform hover:scale-105 transition-all duration-300 shadow-xl">
                            <i class="fas fa-shopping-cart mr-2"></i>
                            {{ $bigSaleEvent->button_text }}
                        </a>
                    @endif
                    
                    @if($bigSaleEvent->days_left)
                        <div class="flex items-center space-x-2 text-white">
                            <i class="fas fa-clock text-xl"></i>
                            <span class="text-lg font-semibold">{{ $bigSaleEvent->days_left }}</span>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="relative">
                @if($bigSaleEvent->banner_image)
                    <img src="{{ $bigSaleEvent->banner_image_url }}" 
                         alt="{{ $bigSaleEvent->title }}" 
                         class="rounded-2xl shadow-2xl transform hover:scale-105 transition-all duration-500">
                @else
                    <div class="aspect-video bg-white/10 backdrop-blur-sm rounded-2xl shadow-2xl flex items-center justify-center">
                        <div class="text-center text-white">
                            <i class="fas fa-fire text-6xl mb-4"></i>
                            <p class="text-xl font-semibold">Big Sale Event</p>
                        </div>
                    </div>
                @endif
                
                <!-- Floating elements -->
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-yellow-400 rounded-full opacity-20 animate-pulse"></div>
                <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-white rounded-full opacity-10 animate-pulse"></div>
            </div>
        </div>
    </div>
    
    <!-- Background pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-transparent via-white/20 to-transparent"></div>
    </div>
</section>
@endif



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
                            <span class="text-sm font-bold text-green-600">{{ \App\Helpers\CurrencyHelper::format(399, $currentCurrency) }}</span>
                            <span class="text-xs text-gray-500 line-through ml-2">{{ \App\Helpers\CurrencyHelper::format(999, $currentCurrency) }}</span>
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
                            <span class="text-sm font-bold text-green-600">{{ \App\Helpers\CurrencyHelper::format(549, $currentCurrency) }}</span>
                            <span class="text-xs text-gray-500 line-through ml-2">{{ \App\Helpers\CurrencyHelper::format(999, $currentCurrency) }}</span>
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
                            <span class="text-sm font-bold text-green-600">{{ \App\Helpers\CurrencyHelper::format(299, $currentCurrency) }}</span>
                            <span class="text-xs text-gray-500 line-through ml-2">{{ \App\Helpers\CurrencyHelper::format(999, $currentCurrency) }}</span>
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
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Shop by Category</h2>
            <a href="{{ route('categories.index') }}" class="text-flipkart-blue font-semibold hover:underline">
                View All <i class="fas fa-chevron-right ml-1"></i>
            </a>
        </div>
        
        @php
            $categories = \App\Models\Category::active()
                ->root()
                ->orderBy('sort_order', 'asc')
                ->orderBy('name', 'asc')
                ->take(9)
                ->get();
        @endphp
        
        <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-9 gap-4">
            @forelse($categories as $category)
                <a href="{{ route('categories.show', $category->slug) }}" 
                   class="category-card bg-white rounded-lg p-4 text-center border border-gray-200 hover:shadow-lg transition-shadow cursor-pointer">
                    @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" 
                             alt="{{ $category->name }}" 
                             class="w-16 h-16 mx-auto mb-2 object-contain">
                    @else
                        <div class="w-16 h-16 mx-auto mb-2 bg-gradient-to-br from-blue-400 to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-layer-group text-white text-2xl"></i>
                        </div>
                    @endif
                    <p class="text-xs text-gray-700 font-medium">{{ $category->name }}</p>
                </a>
            @empty
                <!-- Fallback static categories if no categories exist -->
                <div class="category-card bg-white rounded-lg p-4 text-center border border-gray-200">
                    <div class="w-16 h-16 mx-auto mb-2 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-shopping-basket text-white text-2xl"></i>
                    </div>
                    <p class="text-xs text-gray-700">Grocery</p>
                </div>
                
                <div class="category-card bg-white rounded-lg p-4 text-center border border-gray-200">
                    <div class="w-16 h-16 mx-auto mb-2 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-mobile-alt text-white text-2xl"></i>
                    </div>
                    <p class="text-xs text-gray-700">Mobiles</p>
                </div>
                
                <div class="category-card bg-white rounded-lg p-4 text-center border border-gray-200">
                    <div class="w-16 h-16 mx-auto mb-2 bg-gradient-to-br from-pink-400 to-pink-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-tshirt text-white text-2xl"></i>
                    </div>
                    <p class="text-xs text-gray-700">Fashion</p>
                </div>
                
                <div class="category-card bg-white rounded-lg p-4 text-center border border-gray-200">
                    <div class="w-16 h-16 mx-auto mb-2 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-laptop text-white text-2xl"></i>
                    </div>
                    <p class="text-xs text-gray-700">Electronics</p>
                </div>
                
                <div class="category-card bg-white rounded-lg p-4 text-center border border-gray-200">
                    <div class="w-16 h-16 mx-auto mb-2 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-home text-white text-2xl"></i>
                    </div>
                    <p class="text-xs text-gray-700">Home</p>
                </div>
                
                <div class="category-card bg-white rounded-lg p-4 text-center border border-gray-200">
                    <div class="w-16 h-16 mx-auto mb-2 bg-gradient-to-br from-red-400 to-red-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-blender text-white text-2xl"></i>
                    </div>
                    <p class="text-xs text-gray-700">Appliances</p>
                </div>
                
                <div class="category-card bg-white rounded-lg p-4 text-center border border-gray-200">
                    <div class="w-16 h-16 mx-auto mb-2 bg-gradient-to-br from-teal-400 to-teal-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-plane text-white text-2xl"></i>
                    </div>
                    <p class="text-xs text-gray-700">Travel</p>
                </div>
                
                <div class="category-card bg-white rounded-lg p-4 text-center border border-gray-200">
                    <div class="w-16 h-16 mx-auto mb-2 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-spa text-white text-2xl"></i>
                    </div>
                    <p class="text-xs text-gray-700">Beauty</p>
                </div>
                
                <div class="category-card bg-white rounded-lg p-4 text-center border border-gray-200">
                    <div class="w-16 h-16 mx-auto mb-2 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-motorcycle text-white text-2xl"></i>
                    </div>
                    <p class="text-xs text-gray-700">Two Wheelers</p>
                </div>
            @endforelse
        </div>
        
        @if($categories->count() > 0)
            <div class="mt-6 text-center">
                <a href="{{ route('categories.index') }}" class="text-blue-600 hover:text-blue-500 font-medium">
                    View All Categories →
                </a>
            </div>
        @endif
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
                <a href="{{ route('products.show', $product->slug ?? $product->id) }}" class="product-card bg-white rounded-xl p-4 border border-gray-100 block hover:shadow-2xl transition-all duration-300 cursor-pointer group">
                    <div class="relative overflow-hidden rounded-lg">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-44 object-cover rounded-lg">
                        @else
                            <img src="https://images.unsplash.com/photo-1445205170230-053b83016050?w=200&h=250&fit=crop" 
                                 alt="Fashion" 
                                 class="w-full h-44 object-cover rounded-lg">
                        @endif
                        
                        <!-- Enhanced Badges -->
                        <div class="absolute top-2 left-2 flex flex-col gap-1">
                            @if($product->compare_price && $product->compare_price > $product->price)
                                <span class="bg-red-600 text-white text-xs px-3 py-1.5 rounded-lg font-bold shadow-lg">
                                    -{{ round((($product->compare_price - $product->price) / $product->compare_price) * 100) }}%
                                </span>
                            @endif
                            @if($product->created_at->diffInDays(now()) <= 7)
                                <span class="bg-green-600 text-white text-xs px-3 py-1.5 rounded-lg font-bold shadow-lg">New</span>
                            @endif
                        </div>
                        
                        <!-- Quick View Button -->
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button class="bg-white/90 backdrop-blur-sm text-gray-800 p-2 rounded-full shadow-lg hover:bg-white transition-colors duration-200">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                        
                        <!-- Wishlist Button -->
                        <div class="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button onclick="toggleWishlist(this, {{ $product->id ?? 'null' }})" 
                                    class="bg-white/90 backdrop-blur-sm text-red-500 p-2 rounded-full shadow-lg hover:bg-white transition-colors duration-200 wishlist-btn"
                                    data-product-id="{{ $product->id ?? 'null' }}"
                                    data-product-name="{{ $product->name ?? 'Product' }}"
                                    data-product-image="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=200&h=250&fit=crop' }}"
                                    data-product-price="{{ $product->price ?? '0' }}">
                                <i class="far fa-heart text-sm"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 mb-2 group-hover:text-blue-600 transition-colors duration-200">
                            {{ $product->name }}
                        </h3>
                        
                        <!-- Enhanced Price Display -->
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                {{ \App\Helpers\CurrencyHelper::format($product->price, $currentCurrency) }}
                            </span>
                            @if($product->compare_price && $product->compare_price > $product->price)
                                <span class="text-sm text-gray-400 line-through">{{ \App\Helpers\CurrencyHelper::format($product->compare_price, $currentCurrency) }}</span>
                            @endif
                        </div>
                        
                        <!-- Enhanced Rating -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1">
                                <div class="flex text-yellow-400 text-sm">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= 4)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-xs text-gray-600 ml-1">(4.{{ rand(0, 9) }})</span>
                            </div>
                            
                            <!-- Delivery Indicator -->
                            <div class="flex items-center text-xs text-green-600 font-medium">
                                <i class="fas fa-truck mr-1"></i>
                                Free
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <!-- Fallback static products if no best fashion products found -->
                <a href="{{ route('products.show', 'mens-casual-shirt') }}" class="product-card bg-white rounded-xl p-4 border border-gray-100 block hover:shadow-2xl transition-all duration-300 cursor-pointer group">
                    <div class="relative overflow-hidden rounded-lg">
                        <img src="https://images.unsplash.com/photo-1445205170230-053b83016050?w=200&h=250&fit=crop" 
                             alt="Fashion" 
                             class="w-full h-44 object-cover rounded-lg">
                        
                        <div class="absolute top-2 left-2 flex flex-col gap-1">
                            <span class="bg-green-600 text-white text-xs px-3 py-1.5 rounded-lg font-bold shadow-lg">New</span>
                        </div>
                        
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button class="bg-white/90 backdrop-blur-sm text-gray-800 p-2 rounded-full shadow-lg hover:bg-white transition-colors duration-200">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                        
                        <div class="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button class="bg-white/90 backdrop-blur-sm text-red-500 p-2 rounded-full shadow-lg hover:bg-white transition-colors duration-200">
                                <i class="far fa-heart text-sm"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 mb-2 group-hover:text-blue-600 transition-colors duration-200">
                            Men's Casual Shirt Premium Quality
                        </h3>
                        
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                ₹499
                            </span>
                            <span class="text-sm text-gray-400 line-through">₹1,299</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1">
                                <div class="flex text-yellow-400 text-sm">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                                <span class="text-xs text-gray-600 ml-1">(4.2)</span>
                            </div>
                            
                            <div class="flex items-center text-xs text-green-600 font-medium">
                                <i class="fas fa-truck mr-1"></i>
                                Free
                            </div>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('products.show', 'womens-dress') }}" class="product-card bg-white rounded-xl p-4 border border-gray-100 block hover:shadow-2xl transition-all duration-300 cursor-pointer group">
                    <div class="relative overflow-hidden rounded-lg">
                        <img src="https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=200&h=250&fit=crop" 
                             alt="Fashion" 
                             class="w-full h-44 object-cover rounded-lg">
                        
                        <div class="absolute top-2 left-2 flex flex-col gap-1">
                            <span class="bg-red-600 text-white text-xs px-3 py-1.5 rounded-lg font-bold shadow-lg">-40%</span>
                        </div>
                        
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button class="bg-white/90 backdrop-blur-sm text-gray-800 p-2 rounded-full shadow-lg hover:bg-white transition-colors duration-200">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                        
                        <div class="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button class="bg-white/90 backdrop-blur-sm text-red-500 p-2 rounded-full shadow-lg hover:bg-white transition-colors duration-200">
                                <i class="far fa-heart text-sm"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 mb-2 group-hover:text-blue-600 transition-colors duration-200">
                            Women's Ethnic Wear Collection
                        </h3>
                        
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                ₹799
                            </span>
                            <span class="text-sm text-gray-400 line-through">₹1,399</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1">
                                <div class="flex text-yellow-400 text-sm">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                                <span class="text-xs text-gray-600 ml-1">(4.0)</span>
                            </div>
                            
                            <div class="flex items-center text-xs text-green-600 font-medium">
                                <i class="fas fa-truck mr-1"></i>
                                Free
                            </div>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('products.show', 'mens-jeans') }}" class="product-card bg-white rounded-xl p-4 border border-gray-100 block hover:shadow-2xl transition-all duration-300 cursor-pointer group">
                    <div class="relative overflow-hidden rounded-lg">
                        <img src="https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=200&h=250&fit=crop" 
                             alt="Fashion" 
                             class="w-full h-44 object-cover rounded-lg">
                        
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button class="bg-white/90 backdrop-blur-sm text-gray-800 p-2 rounded-full shadow-lg hover:bg-white transition-colors duration-200">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                        
                        <div class="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button class="bg-white/90 backdrop-blur-sm text-red-500 p-2 rounded-full shadow-lg hover:bg-white transition-colors duration-200">
                                <i class="far fa-heart text-sm"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 mb-2 group-hover:text-blue-600 transition-colors duration-200">
                            Sports Shoes for Men & Women
                        </h3>
                        
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                ₹899
                            </span>
                            <span class="text-sm text-gray-400 line-through">₹1,499</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1">
                                <div class="flex text-yellow-400 text-sm">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                                <span class="text-xs text-gray-600 ml-1">(4.0)</span>
                            </div>
                            
                            <div class="flex items-center text-xs text-green-600 font-medium">
                                <i class="fas fa-truck mr-1"></i>
                                Free
                            </div>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('products.show', 'womens-handbag') }}" class="product-card bg-white rounded-xl p-4 border border-gray-100 block hover:shadow-2xl transition-all duration-300 cursor-pointer group">
                    <div class="relative overflow-hidden rounded-lg">
                        <img src="https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=200&h=250&fit=crop" 
                             alt="Fashion" 
                             class="w-full h-44 object-cover rounded-lg">
                        
                        <div class="absolute top-2 left-2 flex flex-col gap-1">
                            <span class="bg-orange-600 text-white text-xs px-3 py-1.5 rounded-lg font-bold shadow-lg">Hot</span>
                        </div>
                        
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button class="bg-white/90 backdrop-blur-sm text-gray-800 p-2 rounded-full shadow-lg hover:bg-white transition-colors duration-200">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                        
                        <div class="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button class="bg-white/90 backdrop-blur-sm text-red-500 p-2 rounded-full shadow-lg hover:bg-white transition-colors duration-200">
                                <i class="far fa-heart text-sm"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 mb-2 group-hover:text-blue-600 transition-colors duration-200">
                            Designer Handbag Collection
                        </h3>
                        
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                ₹1,299
                            </span>
                            <span class="text-sm text-gray-400 line-through">₹2,499</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1">
                                <div class="flex text-yellow-400 text-sm">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <span class="text-xs text-gray-600 ml-1">(4.7)</span>
                            </div>
                            
                            <div class="flex items-center text-xs text-green-600 font-medium">
                                <i class="fas fa-truck mr-1"></i>
                                Free
                            </div>
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
                <a href="{{ route('products.show', $product->slug ?? $product->id) }}" class="product-card bg-white rounded-xl p-4 border border-gray-100 block hover:shadow-2xl transition-all duration-300 cursor-pointer group">
                    <div class="relative overflow-hidden rounded-lg">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-44 object-cover rounded-lg">
                        @else
                            <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200&h=250&fit=crop" 
                                 alt="Product" 
                                 class="w-full h-44 object-cover rounded-lg">
                        @endif
                        
                        <!-- Enhanced Badges -->
                        <div class="absolute top-2 left-2 flex flex-col gap-1">
                            @if($product->sale_price && $product->sale_price < $product->price)
                                @php
                                    $discount = round((($product->price - $product->sale_price) / $product->price) * 100);
                                @endphp
                                <span class="bg-red-600 text-white text-xs px-3 py-1.5 rounded-lg font-bold shadow-lg">
                                    -{{ $discount }}%
                                </span>
                            @endif
                            @if($product->created_at->diffInDays(now()) <= 7)
                                <span class="bg-green-600 text-white text-xs px-3 py-1.5 rounded-lg font-bold shadow-lg">New</span>
                            @endif
                        </div>
                        
                        <!-- Quick View Button -->
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button class="bg-white/90 backdrop-blur-sm text-gray-800 p-2 rounded-full shadow-lg hover:bg-white transition-colors duration-200">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                        
                        <!-- Wishlist Button -->
                        <div class="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button onclick="toggleWishlist(this, {{ $product->id ?? 'null' }})" 
                                    class="bg-white/90 backdrop-blur-sm text-red-500 p-2 rounded-full shadow-lg hover:bg-white transition-colors duration-200 wishlist-btn"
                                    data-product-id="{{ $product->id ?? 'null' }}"
                                    data-product-name="{{ $product->name ?? 'Product' }}"
                                    data-product-image="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=200&h=250&fit=crop' }}"
                                    data-product-price="{{ $product->price ?? '0' }}">
                                <i class="far fa-heart text-sm"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 mb-2 group-hover:text-blue-600 transition-colors duration-200">
                            {{ $product->name }}
                        </h3>
                        
                        @if($product->categories && $product->categories->isNotEmpty())
                            <p class="text-xs text-gray-500 mb-2">{{ $product->categories->first()->name }}</p>
                        @endif
                        
                        <!-- Enhanced Price Display -->
                        <div class="flex items-center gap-2 mb-2">
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                    {{ \App\Helpers\CurrencyHelper::format($product->sale_price, $currentCurrency) }}
                                </span>
                                <span class="text-sm text-gray-400 line-through">{{ \App\Helpers\CurrencyHelper::format($product->price, $currentCurrency) }}</span>
                            @else
                                <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                    {{ \App\Helpers\CurrencyHelper::format($product->price, $currentCurrency) }}
                                </span>
                            @endif
                        </div>
                        
                        <!-- Stock and Delivery Info -->
                        <div class="flex items-center justify-between text-xs">
                            @if($product->weight > 0)
                                <div class="text-green-600 font-medium">
                                    <i class="fas fa-truck mr-1"></i>
                                    Free delivery
                                </div>
                            @endif
                            @if($product->track_quantity && $product->quantity > 0)
                                <div class="text-green-600 font-medium">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    In Stock ({{ $product->quantity }})
                                </div>
                            @elseif($product->track_quantity && $product->quantity <= 0)
                                <div class="text-red-600 font-medium">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Out of Stock
                                </div>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <!-- Fallback products if no featured products exist -->
                <a href="#" class="product-card bg-white rounded-xl p-4 border border-gray-100 block hover:shadow-2xl transition-all duration-300 cursor-pointer group">
                    <div class="relative overflow-hidden rounded-lg">
                        <img src="https://images.unsplash.com/photo-1598327105666-5b89327aff97?w=200&h=250&fit=crop" 
                             alt="Featured Product" 
                             class="w-full h-44 object-cover rounded-lg">
                        
                        <div class="absolute top-2 left-2 flex flex-col gap-1">
                            <span class="bg-orange-600 text-white text-xs px-3 py-1.5 rounded-lg font-bold shadow-lg">Featured</span>
                        </div>
                        
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button class="bg-white/90 backdrop-blur-sm text-gray-800 p-2 rounded-full shadow-lg hover:bg-white transition-colors duration-200">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                        
                        <div class="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button class="bg-white/90 backdrop-blur-sm text-red-500 p-2 rounded-full shadow-lg hover:bg-white transition-colors duration-200">
                                <i class="far fa-heart text-sm"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 mb-2 group-hover:text-blue-600 transition-colors duration-200">
                            Premium Featured Product
                        </h3>
                        
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                ₹999
                            </span>
                            <span class="text-sm text-gray-400 line-through">₹1,499</span>
                        </div>
                        
                        <div class="flex items-center justify-between text-xs">
                            <div class="text-green-600 font-medium">
                                <i class="fas fa-truck mr-1"></i>
                                Free delivery
                            </div>
                        </div>
                    </div>
                </a>
                
                <a href="#" class="product-card bg-white rounded-xl p-4 border border-gray-100 block hover:shadow-2xl transition-all duration-300 cursor-pointer group">
                    <div class="relative overflow-hidden rounded-lg">
                        <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=200&h=250&fit=crop" 
                             alt="Featured Product" 
                             class="w-full h-44 object-cover rounded-lg">
                        
                        <div class="absolute top-2 left-2 flex flex-col gap-1">
                            <span class="bg-orange-600 text-white text-xs px-3 py-1.5 rounded-lg font-bold shadow-lg">Featured</span>
                        </div>
                        
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button class="bg-white/90 backdrop-blur-sm text-gray-800 p-2 rounded-full shadow-lg hover:bg-white transition-colors duration-200">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                        
                        <div class="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button class="bg-white/90 backdrop-blur-sm text-red-500 p-2 rounded-full shadow-lg hover:bg-white transition-colors duration-200">
                                <i class="far fa-heart text-sm"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 mb-2 group-hover:text-blue-600 transition-colors duration-200">
                            Best Selling Item
                        </h3>
                        
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                ₹1,299
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between text-xs">
                            <div class="text-green-600 font-medium">
                                <i class="fas fa-truck mr-1"></i>
                                Free delivery
                            </div>
                        </div>
                    </div>
                </a>
                
                <a href="#" class="product-card bg-white rounded-xl p-4 border border-gray-100 block hover:shadow-2xl transition-all duration-300 cursor-pointer group">
                    <div class="relative overflow-hidden rounded-lg">
                        <img src="https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=200&h=250&fit=crop" 
                             alt="Featured Product" 
                             class="w-full h-44 object-cover rounded-lg">
                        
                        <div class="absolute top-2 left-2 flex flex-col gap-1">
                            <span class="bg-orange-600 text-white text-xs px-3 py-1.5 rounded-lg font-bold shadow-lg">Featured</span>
                        </div>
                        
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button class="bg-white/90 backdrop-blur-sm text-gray-800 p-2 rounded-full shadow-lg hover:bg-white transition-colors duration-200">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                        
                        <div class="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button class="bg-white/90 backdrop-blur-sm text-red-500 p-2 rounded-full shadow-lg hover:bg-white transition-colors duration-200">
                                <i class="far fa-heart text-sm"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 mb-2 group-hover:text-blue-600 transition-colors duration-200">
                            Top Rated Product
                        </h3>
                        
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                ₹799
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between text-xs">
                            <div class="text-green-600 font-medium">
                                <i class="fas fa-truck mr-1"></i>
                                Free delivery
                            </div>
                        </div>
                    </div>
                </a>
                
                <a href="#" class="product-card bg-white rounded-xl p-4 border border-gray-100 block hover:shadow-2xl transition-all duration-300 cursor-pointer group">
                    <div class="relative overflow-hidden rounded-lg">
                        <img src="https://images.unsplash.com/photo-1598928506311-c55ded91e20b?w=200&h=250&fit=crop" 
                             alt="Featured Product" 
                             class="w-full h-44 object-cover rounded-lg">
                        
                        <div class="absolute top-2 left-2 flex flex-col gap-1">
                            <span class="bg-orange-600 text-white text-xs px-3 py-1.5 rounded-lg font-bold shadow-lg">Featured</span>
                        </div>
                        
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button class="bg-white/90 backdrop-blur-sm text-gray-800 p-2 rounded-full shadow-lg hover:bg-white transition-colors duration-200">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                        
                        <div class="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button class="bg-white/90 backdrop-blur-sm text-red-500 p-2 rounded-full shadow-lg hover:bg-white transition-colors duration-200">
                                <i class="far fa-heart text-sm"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 mb-2 group-hover:text-blue-600 transition-colors duration-200">
                            Limited Time Offer
                        </h3>
                        
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                ₹599
                            </span>
                            <span class="text-sm text-gray-400 line-through">₹999</span>
                        </div>
                        
                        <div class="flex items-center justify-between text-xs">
                            <div class="text-green-600 font-medium">
                                <i class="fas fa-truck mr-1"></i>
                                Free delivery
                            </div>
                        </div>
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
        
     </div>
</section>

    <div class="max-w-7xl mx-auto px-4">
                
        <!-- Product of the Day Section -->
@php
    $productOfDay = \App\Models\ProductOfDay::active()
        ->currentlyActive()
        ->orderBy('sort_order', 'asc')
        ->first();
@endphp
@if($productOfDay)
<section class="py-16 bg-gradient-to-br from-yellow-50 via-orange-50 to-red-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <div class="inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full mb-4">
                <i class="fas fa-star mr-2"></i>
                <span class="font-semibold">{{ $productOfDay->badge_text }}</span>
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Today's Featured Product</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Discover our handpicked selection of amazing products at unbeatable prices</p>
        </div>
        
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2">
                <!-- Product Image -->
                <div class="relative bg-gradient-to-br from-yellow-100 to-orange-100 p-8">
                    @if($productOfDay->featured_image_url)
                        <img src="{{ $productOfDay->featured_image_url }}" 
                             alt="{{ $productOfDay->title }}" 
                             class="w-full h-80 object-contain rounded-2xl shadow-lg">
                    @elseif($productOfDay->product && $productOfDay->product->featured_image)
                        <img src="{{ asset('storage/' . $productOfDay->product->featured_image) }}" 
                             alt="{{ $productOfDay->title }}" 
                             class="w-full h-80 object-contain rounded-2xl shadow-lg">
                    @else
                        <div class="w-full h-80 bg-white/50 rounded-2xl shadow-lg flex items-center justify-center">
                            <i class="fas fa-star text-6xl text-yellow-400"></i>
                        </div>
                    @endif
                    
                    <!-- Floating badge -->
                    <div class="absolute top-4 right-4" style="background-color: {{ $productOfDay->badge_color }};">
                        <div class="px-4 py-2 text-white font-bold rounded-full shadow-lg transform rotate-12">
                            {{ $productOfDay->badge_text }}
                        </div>
                    </div>
                </div>
                
                <!-- Product Details -->
                <div class="p-8 lg:p-12 space-y-6">
                    <div>
                        <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">{{ $productOfDay->title }}</h3>
                        @if($productOfDay->product)
                            <p class="text-gray-600 mb-4">{{ $productOfDay->product->short_description ?? '' }}</p>
                        @endif
                    </div>
                    
                    <div class="prose prose-lg text-gray-600">
                        {!! $productOfDay->description !!}
                    </div>
                    
                    @if($productOfDay->discount_percentage || ($productOfDay->original_price && $productOfDay->sale_price))
                        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center bg-gradient-to-r from-yellow-50 to-orange-50 rounded-2xl p-6">
                            @if($productOfDay->discount_percentage)
                                <div class="text-center">
                                    <div class="text-3xl font-bold text-orange-600">{{ $productOfDay->formatted_discount }}</div>
                                    <div class="text-sm text-gray-600">OFF</div>
                                </div>
                            @endif
                            
                            @if($productOfDay->original_price && $productOfDay->sale_price)
                                <div class="flex-1 text-center sm:text-left">
                                    <div class="flex items-baseline gap-2 justify-center sm:justify-start">
                                        <span class="text-xl text-gray-500 line-through">{{ $productOfDay->formatted_original_price }}</span>
                                        <span class="text-3xl font-bold text-gray-900">{{ $productOfDay->formatted_sale_price }}</span>
                                    </div>
                                    <div class="text-sm text-green-600 font-medium">You save {{ $productOfDay->formatted_savings_amount }}</div>
                                </div>
                            @endif
                        </div>
                    @endif
                    
                    <div class="flex flex-col sm:flex-row gap-4 items-center">
                        @if($productOfDay->button_link)
                            <a href="{{ $productOfDay->button_link }}" 
                               class="flex-1 inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-white rounded-full font-bold text-lg hover:from-yellow-500 hover:to-orange-600 transform hover:scale-105 transition-all duration-300 shadow-xl">
                                <i class="fas fa-shopping-cart mr-2"></i>
                                {{ $productOfDay->button_text }}
                            </a>
                        @endif
                        
                        @if($productOfDay->days_left)
                            <div class="flex items-center space-x-2 text-orange-600">
                                <i class="fas fa-clock"></i>
                                <span class="font-semibold">{{ $productOfDay->days_left }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Trust badges -->
                    <div class="flex items-center justify-center space-x-6 text-gray-500">
                        <div class="flex items-center space-x-1">
                            <i class="fas fa-truck"></i>
                            <span class="text-sm">Free Shipping</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <i class="fas fa-shield-alt"></i>
                            <span class="text-sm">Secure Payment</span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <i class="fas fa-undo"></i>
                            <span class="text-sm">Easy Returns</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

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

// Copy coupon code to clipboard
function copyCouponCode(code, button) {
    // Create temporary input element
    const tempInput = document.createElement('input');
    tempInput.value = code;
    document.body.appendChild(tempInput);
    tempInput.select();
    
    try {
        // Copy the text
        document.execCommand('copy');
        
        // Update button text temporarily
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check mr-1"></i>Copied!';
        button.classList.remove('bg-purple-600', 'hover:bg-purple-700', 'bg-green-600', 'hover:bg-green-700', 'bg-orange-600', 'hover:bg-orange-700', 'bg-blue-600', 'hover:bg-blue-700');
        button.classList.add('bg-green-600', 'hover:bg-green-700');
        
        // Show success notification
        showNotification(`Coupon code "${code}" copied to clipboard!`, 'success');
        
        // Reset button after 2 seconds
        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('bg-green-600', 'hover:bg-green-700');
            button.classList.add('bg-purple-600', 'hover:bg-purple-700');
        }, 2000);
        
    } catch (err) {
        console.error('Failed to copy coupon code:', err);
        showNotification('Failed to copy coupon code', 'error');
    }
    
    // Remove temporary input
    document.body.removeChild(tempInput);
}

// Show notification
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full`;
    
    // Set color based on type
    if (type === 'success') {
        notification.classList.add('bg-green-500', 'text-white');
    } else if (type === 'error') {
        notification.classList.add('bg-red-500', 'text-white');
    } else {
        notification.classList.add('bg-blue-500', 'text-white');
    }
    
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
        notification.classList.add('translate-x-0');
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.remove('translate-x-0');
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Global functions for onclick handlers
function changeSlide(direction) {
    if (window.bannerSlider) {
        window.bannerSlider.changeSlide(direction);
    }
}

// Banner Slider Functionality
class BannerSlider {
    constructor(sliderId) {
        this.slider = document.getElementById(sliderId);
        if (!this.slider) {
            console.error('Slider element not found:', sliderId);
            return;
        }
        
        this.slides = this.slider.querySelectorAll('.banner-slide');
        this.currentSlide = 0;
        this.totalSlides = this.slides.length;
        this.autoPlayInterval = null;
        this.autoPlayDelay = 5000; // 5 seconds
        this.isPaused = false;
        
        console.log('BannerSlider initialized with', this.totalSlides, 'slides');
        
        this.init();
    }
    
    init() {
        if (this.totalSlides <= 1) {
            console.log('Only one slide found, disabling slider functionality');
            return;
        }
        
        // Set initial slide after a small delay
        setTimeout(() => {
            this.showSlide(0);
        }, 100);
        
        // Add event listeners
        const prevBtn = this.slider.querySelector('.slider-nav.prev');
        const nextBtn = this.slider.querySelector('.slider-nav.next');
        
        if (prevBtn) {
            prevBtn.addEventListener('click', () => this.changeSlide(-1));
            console.log('Previous button event listener added');
        }
        if (nextBtn) {
            nextBtn.addEventListener('click', () => this.changeSlide(1));
            console.log('Next button event listener added');
        }
        
        // Add hover events for pause/resume
        this.slider.addEventListener('mouseenter', () => this.pauseAutoPlay());
        this.slider.addEventListener('mouseleave', () => this.resumeAutoPlay());
        
        // Add keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') this.changeSlide(-1);
            if (e.key === 'ArrowRight') this.changeSlide(1);
        });
        
        // Add touch/swipe support
        this.addTouchSupport();
        
        // Start auto-play after a delay
        setTimeout(() => this.startAutoPlay(), 3000);
        
        // Add effect rotation
        this.rotateEffects();
        
        console.log('Slider initialization complete');
    }
    
    showSlide(index) {
        // Ensure index is within bounds
        index = (index + this.totalSlides) % this.totalSlides;
        
        console.log('Transitioning to slide:', index, 'from slide:', this.currentSlide);
        
        // Hide all slides first
        this.slides.forEach((slide, i) => {
            slide.classList.remove('active', 'prev');
            slide.style.position = 'absolute';
            slide.style.opacity = '0';
            slide.style.transform = 'translateX(100%)';
            slide.style.zIndex = '0';
            slide.style.pointerEvents = 'none';
            slide.style.transition = 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
        });
        
        // Show current slide with a small delay for smooth transition
        setTimeout(() => {
            const currentSlideElement = this.slides[index];
            if (currentSlideElement) {
                currentSlideElement.classList.add('active');
                currentSlideElement.style.position = 'relative';
                currentSlideElement.style.opacity = '1';
                currentSlideElement.style.transform = 'translateX(0)';
                currentSlideElement.style.zIndex = '2';
                currentSlideElement.style.pointerEvents = 'auto';
            }
        }, 50);
        
        this.currentSlide = index;
        console.log('Now showing slide:', index);
    }
    
    changeSlide(direction) {
        const newIndex = this.currentSlide + direction;
        this.showSlide(newIndex);
        this.resetAutoPlay();
    }
    
    startAutoPlay() {
        if (this.autoPlayInterval || this.totalSlides <= 1) return;
        
        this.autoPlayInterval = setInterval(() => {
            if (!this.isPaused) {
                this.changeSlide(1);
            }
        }, this.autoPlayDelay);
        console.log('Auto-play started');
    }
    
    pauseAutoPlay() {
        this.isPaused = true;
    }
    
    resumeAutoPlay() {
        this.isPaused = false;
    }
    
    resetAutoPlay() {
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
            this.autoPlayInterval = null;
        }
        this.startAutoPlay();
    }
    
    addTouchSupport() {
        let startX = 0;
        let endX = 0;
        
        this.slider.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
        });
        
        this.slider.addEventListener('touchend', (e) => {
            endX = e.changedTouches[0].clientX;
            const diff = startX - endX;
            
            if (Math.abs(diff) > 50) { // Minimum swipe distance
                if (diff > 0) {
                    this.changeSlide(1); // Swipe left, go to next
                } else {
                    this.changeSlide(-1); // Swipe right, go to previous
                }
            }
        });
    }
    
    rotateEffects() {
        const effects = ['slider-effect-slide', 'slider-effect-fade', 'slider-effect-zoom'];
        let currentEffect = 0;
        
        // Change effect every 15 seconds
        setInterval(() => {
            // Remove current effect
            this.slider.classList.remove(effects[currentEffect]);
            
            // Move to next effect
            currentEffect = (currentEffect + 1) % effects.length;
            
            // Add new effect
            this.slider.classList.add(effects[currentEffect]);
            console.log('Effect changed to:', effects[currentEffect]);
        }, 15000);
    }
}

// Initialize search functionality
document.addEventListener('DOMContentLoaded', () => {
    startDealTimer();
    initializeSearch();
    
    // Debug: Check if slider element exists
    const sliderElement = document.getElementById('mainBannerSlider');
    console.log('Slider element found:', !!sliderElement);
    if (sliderElement) {
        console.log('Slider classes:', sliderElement.className);
        console.log('Slider slides found:', sliderElement.querySelectorAll('.banner-slide').length);
    }
    
    // Initialize banner slider
    window.bannerSlider = new BannerSlider('mainBannerSlider');
    
    // Debug: Check if slider was initialized
    setTimeout(() => {
        console.log('BannerSlider instance:', window.bannerSlider);
        console.log('Current slide:', window.bannerSlider ? window.bannerSlider.currentSlide : 'undefined');
    }, 1000);
});

// Wishlist functionality is handled by the global layout (app.blade.php)

</script>
@endsection
