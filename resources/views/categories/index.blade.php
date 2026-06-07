@extends('layouts.app')

@section('title', 'Categories - E-Commerce Store')

@php
use Illuminate\Support\Str;
@endphp

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl font-bold mb-4">Shop by Category</h1>
                <p class="text-xl text-purple-100">Find exactly what you're looking for in our curated collections</p>
            </div>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($categories->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category) }}" class="group bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 cursor-pointer block">
                        <div class="relative">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" 
                                     alt="{{ $category->name }}" 
                                     class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-indigo-400 to-purple-600 flex items-center justify-center">
                                    <i class="fas fa-folder text-white text-4xl"></i>
                                </div>
                            @endif
                            @if($category->is_featured)
                                <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                    Featured
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <div class="flex items-center mb-3">
                                <div class="bg-indigo-100 rounded-full p-3 mr-3">
                                    <i class="fas fa-tag text-indigo-600 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">{{ $category->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $category->products_count }} Products</p>
                                </div>
                            </div>
                            @if($category->description)
                                <p class="text-gray-600 mb-4">{{ Str::limit($category->description, 100) }}</p>
                            @else
                                <p class="text-gray-600 mb-4">Browse our collection of {{ $category->name }}</p>
                            @endif
                            <div class="flex items-center justify-between">
                                <span class="text-2xl font-bold text-indigo-600">Shop Now</span>
                                <div class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-16">
                <div class="bg-gray-100 rounded-full p-8 w-24 h-24 mx-auto mb-6">
                    <i class="fas fa-folder-open text-gray-400 text-4xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No Categories Found</h3>
                <p class="text-gray-600 mb-6">There are no categories available at the moment.</p>
                <a href="{{ route('home') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors inline-block">
                    Back to Home
                </a>
            </div>
        @endif
    </div>

    <!-- Featured Categories Section -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Featured Categories</h2>
                <p class="text-xl text-gray-600">Handpicked collections for you</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-8 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl">
                    <div class="bg-blue-600 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-fire text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Trending Now</h3>
                    <p class="text-gray-600 mb-4">Hottest products this season</p>
                    <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                        Explore Trending
                    </button>
                </div>
                
                <div class="text-center p-8 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl">
                    <div class="bg-green-600 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-tag text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Best Deals</h3>
                    <p class="text-gray-600 mb-4">Unbeatable prices guaranteed</p>
                    <button class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors">
                        View Deals
                    </button>
                </div>
                
                <div class="text-center p-8 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl">
                    <div class="bg-purple-600 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-star text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Premium Picks</h3>
                    <p class="text-gray-600 mb-4">Curated premium selection</p>
                    <button class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition-colors">
                        Shop Premium
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
