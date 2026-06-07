@extends('layouts.app')

@section('title', 'Deals - E-Commerce Store')

@php
    $currentCurrency = \App\Models\Setting::get('currency', 'USD');
@endphp

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-red-600 to-orange-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl font-bold mb-4">Hot Deals & Offers</h1>
                <p class="text-xl text-red-100">Don't miss out on these amazing deals!</p>
            </div>
        </div>
    </div>

    <!-- Deals Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($deals->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($deals as $deal)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300">
                        <div class="relative">
                            @if($deal->image)
                                <img src="{{ asset('storage/' . $deal->image) }}" 
                                     alt="{{ $deal->title }}" 
                                     class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-red-400 to-orange-600 flex items-center justify-center">
                                    <i class="fas fa-tag text-white text-4xl"></i>
                                </div>
                            @endif
                            <div class="absolute top-4 left-4 bg-red-600 text-white px-3 py-1 rounded-full text-sm font-bold">
                                {{ $deal->formatted_discount_value }} OFF
                            </div>
                            @if($deal->days_remaining !== null && $deal->days_remaining <= 3)
                                <div class="absolute top-4 right-4 bg-orange-500 text-white px-3 py-1 rounded-full text-sm font-semibold animate-pulse">
                                    {{ $deal->days_remaining > 0 ? $deal->days_remaining . ' days left' : 'Ending Soon' }}
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <div class="mb-4">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $deal->title }}</h3>
                                <p class="text-gray-600 text-sm">{{ Str::limit($deal->description, 120) }}</p>
                            </div>
                            
                            @if($deal->min_purchase_amount)
                                <div class="mb-4 p-3 bg-yellow-50 rounded-lg">
                                    <p class="text-sm text-yellow-800">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Minimum purchase: {{ $deal->formatted_min_purchase }}
                                    </p>
                                </div>
                            @endif

                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    @if($deal->sale_price && $deal->original_price)
                                        <span class="text-2xl font-bold text-red-600">{{ \App\Helpers\CurrencyHelper::format($deal->sale_price, $currentCurrency) }}</span>
                                        <span class="text-lg text-gray-400 line-through ml-2">{{ \App\Helpers\CurrencyHelper::format($deal->original_price, $currentCurrency) }}</span>
                                    @else
                                        <span class="text-2xl font-bold text-red-600">Up to {{ $deal->formatted_discount_value }} OFF</span>
                                    @endif
                                </div>
                                <div class="text-right">
                                    @if($deal->days_remaining !== null)
                                        <p class="text-sm text-gray-500">
                                            {{ $deal->days_remaining > 0 ? $deal->days_remaining . ' days left' : 'Ended' }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex space-x-3">
                                @if($deal->link)
                                    <a href="{{ $deal->link }}" 
                                       target="_blank"
                                       class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors text-center">
                                        <i class="fas fa-shopping-cart mr-2"></i>
                                        Shop Now
                                    </a>
                                @endif
                                <a href="{{ route('deals.show', $deal) }}" 
                                   class="flex-1 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors text-center">
                                    <i class="fas fa-eye mr-2"></i>
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <div class="bg-gray-100 rounded-full p-8 w-24 h-24 mx-auto mb-6">
                    <i class="fas fa-tag text-gray-400 text-4xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No Active Deals</h3>
                <p class="text-gray-600 mb-6">There are no active deals at the moment. Check back soon!</p>
                <a href="{{ route('home') }}" class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition-colors inline-block">
                    Back to Home
                </a>
            </div>
        @endif
    </div>

    <!-- Newsletter Section -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Never Miss a Deal</h2>
                <p class="text-xl text-gray-600 mb-8">Subscribe to get exclusive offers delivered to your inbox</p>
                <div class="max-w-md mx-auto flex">
                    <input type="email" placeholder="Enter your email" 
                           class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <button class="bg-red-600 text-white px-6 py-3 rounded-r-lg hover:bg-red-700 transition-colors">
                        Subscribe
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
