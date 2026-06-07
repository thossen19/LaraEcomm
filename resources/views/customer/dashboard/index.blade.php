@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-lg p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">
                    Welcome back, {{ Auth::user()->first_name }}!
                </h1>
                <p class="text-blue-100">
                    Here's what's happening with your orders and account today.
                </p>
            </div>
            <div class="text-right">
                <a href="{{ route('profile.edit') }}" class="bg-white text-blue-600 px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-50">
                    <i class="fas fa-user-edit mr-2"></i>
                    Edit Profile
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                    <i class="fas fa-shopping-bag text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                    <i class="fas fa-dollar-sign text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Spent</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($totalSpent, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                    <i class="fas fa-shopping-cart text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Cart Items</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $cartItemsCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                    <i class="fas fa-heart text-white text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Wishlist</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $wishlistCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Orders -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-medium text-gray-900">Recent Orders</h2>
                        <a href="{{ route('customer.orders.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            View All
                        </a>
                    </div>
                </div>
                
                <div class="p-6">
                    @forelse($recentOrders)
                        <div class="space-y-4">
                            @foreach($recentOrders as $order)
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3">
                                            <h3 class="text-lg font-medium text-gray-900">
                                                {{ $order->order_number }}
                                            </h3>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->status_color }}">
                                                {{ $order->status_label }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ $order->created_at->format('M j, Y g:i A') }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            {{ $order->orderItems->count() }} items • ${{ number_format($order->total, 2) }}
                                        </p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('customer.orders.show', $order) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            View
                                        </a>
                                        @if(in_array($order->status, ['pending', 'processing']))
                                            <form method="POST" action="{{ route('customer.orders.cancel', $order) }}" 
                                                  onsubmit="return confirm('Are you sure you want to cancel this order?')" class="inline-block">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                    Cancel
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-shopping-bag text-4xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No orders yet</h3>
                            <p class="text-gray-500 mb-4">Start shopping to see your orders here.</p>
                            <a href="{{ route('products.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Start Shopping
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Wishlist -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-medium text-gray-900">Recent Wishlist</h2>
                        <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            View All
                        </a>
                    </div>
                </div>
                
                <div class="p-6">
                    @forelse($recentWishlist)
                        <div class="space-y-4">
                            @foreach($recentWishlist as $item)
                                <div class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                                             alt="{{ $item->product->name }}" 
                                             class="h-12 w-12 rounded object-cover">
                                    @else
                                        <div class="h-12 w-12 bg-gray-200 rounded flex items-center justify-center">
                                            <i class="fas fa-box text-gray-400"></i>
                                        </div>
                                    @endif
                                    
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900 truncate">
                                            {{ $item->product->name }}
                                        </h4>
                                        <p class="text-sm text-gray-600">${{ number_format($item->product->price, 2) }}</p>
                                    </div>
                                    
                                    <div class="flex space-x-2">
                                        <a href="{{ route('products.show', $item->product) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm">
                                            View
                                        </a>
                                        <form method="POST" action="{{ route('cart.add') }}" class="inline-block">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="text-green-600 hover:text-green-800 text-sm">
                                                <i class="fas fa-cart-plus"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-heart text-4xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Your wishlist is empty</h3>
                            <p class="text-gray-500 mb-4">Add products you love to your wishlist.</p>
                            <a href="{{ route('products.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Browse Products
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('products.index') }}" class="flex items-center space-x-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                <i class="fas fa-search text-blue-600 text-xl"></i>
                <div>
                    <h3 class="text-sm font-medium text-gray-900">Browse Products</h3>
                    <p class="text-sm text-gray-600">Discover new items</p>
                </div>
            </a>
            
            <a href="{{ route('cart.index') }}" class="flex items-center space-x-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                <i class="fas fa-shopping-cart text-green-600 text-xl"></i>
                <div>
                    <h3 class="text-sm font-medium text-gray-900">View Cart</h3>
                    <p class="text-sm text-gray-600">{{ $cartItemsCount }} items</p>
                </div>
            </a>
            
            <a href="{{ route('customer.addresses.index') }}" class="flex items-center space-x-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                <i class="fas fa-map-marker-alt text-purple-600 text-xl"></i>
                <div>
                    <h3 class="text-sm font-medium text-gray-900">Manage Addresses</h3>
                    <p class="text-sm text-gray-600">Update shipping info</p>
                </div>
            </a>
            
            <a href="{{ route('customer.orders.index') }}" class="flex items-center space-x-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                <i class="fas fa-history text-orange-600 text-xl"></i>
                <div>
                    <h3 class="text-sm font-medium text-gray-900">Order History</h3>
                    <p class="text-sm text-gray-600">View all orders</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
