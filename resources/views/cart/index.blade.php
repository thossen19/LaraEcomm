@extends('layouts.app')

@section('title', 'Shopping Cart')

@php
    $currentCurrency = \App\Models\Setting::get('currency', 'USD');
@endphp

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Shopping Cart</h1>
            
            @if($cart->cartItems->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Your cart is empty</h3>
                    <p class="text-gray-500 mb-6">Looks like you haven't added any products to your cart yet.</p>
                    <a href="{{ route('products.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md text-sm font-medium">
                        Start Shopping
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Cart Items -->
                    <div class="lg:col-span-2">
                        <div class="space-y-4">
                            @foreach($cart->cartItems as $item)
                                <div class="bg-gray-50 rounded-lg p-4 flex items-center space-x-4">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        @if($item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="h-20 w-20 rounded object-cover">
                                        @else
                                            <div class="h-20 w-20 bg-gray-200 rounded flex items-center justify-center">
                                                <i class="fas fa-box text-gray-400 text-2xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Product Details -->
                                    <div class="flex-1">
                                        <h3 class="text-lg font-medium text-gray-900">
                                            {{ $item->product->name }}
                                        </h3>
                                        @if($item->productVariant)
                                            <p class="text-sm text-gray-500">{{ $item->productVariant->title }}</p>
                                        @endif
                                        <p class="text-sm text-gray-500">SKU: {{ $item->product->sku }}</p>
                                        
                                        <!-- Quantity Update Form -->
                                        <form method="POST" action="{{ route('cart.update', $item) }}" class="mt-2 inline-block">
                                            @csrf
                                            @method('PUT')
                                            <div class="flex items-center space-x-2">
                                                <label class="text-sm font-medium text-gray-700">Qty:</label>
                                                <input type="number" name="quantity" value="{{ $item->quantity }}" 
                                                       min="1" max="{{ $item->productVariant ? $item->productVariant->quantity : $item->product->quantity }}"
                                                       class="w-20 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                <button type="submit" class="text-blue-600 hover:text-blue-900 text-sm">
                                                    Update
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    <!-- Price and Actions -->
                                    <div class="text-right">
                                        <p class="text-lg font-medium text-gray-900">
                                            {{ \App\Helpers\CurrencyHelper::format($item->total, $currentCurrency) }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ \App\Helpers\CurrencyHelper::format($item->price, $currentCurrency) }} each
                                        </p>
                                        <form method="POST" action="{{ route('cart.remove', $item) }}?v={{ time() }}" 
                                              onsubmit="return confirm('Remove this item from cart?')" class="mt-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm">
                                                <i class="fas fa-trash"></i> Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Clear Cart Button -->
                        <div class="mt-6 text-right">
                            <form method="POST" action="{{ route('cart.clear') }}" 
                                  onsubmit="return confirm('Clear your entire cart?')" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm">
                                    <i class="fas fa-trash"></i> Clear Cart
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-gray-50 rounded-lg p-6 sticky top-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h3>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-medium">{{ \App\Helpers\CurrencyHelper::format($cart->subtotal, $currentCurrency) }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tax ({{ \App\Models\Setting::get('tax_rate', 10) }}%)</span>
                                    <span class="font-medium">{{ \App\Helpers\CurrencyHelper::format($cart->tax, $currentCurrency) }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Shipping</span>
                                    <span class="font-medium">
                                        @if($cart->shipping > 0)
                                            {{ \App\Helpers\CurrencyHelper::format($cart->shipping, $currentCurrency) }}
                                        @else
                                            FREE
                                        @endif
                                    </span>
                                </div>
                                
                                @if($cart->discount > 0)
                                    <div class="flex justify-between text-green-600">
                                        <span>Discount</span>
                                        <span class="font-medium">-{{ \App\Helpers\CurrencyHelper::format($cart->discount, $currentCurrency) }}</span>
                                    </div>
                                @endif
                                
                                <div class="border-t pt-3">
                                    <div class="flex justify-between">
                                        <span class="text-lg font-medium text-gray-900">Total</span>
                                        <span class="text-lg font-medium text-gray-900">{{ \App\Helpers\CurrencyHelper::format($cart->total, $currentCurrency) }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Coupon Code -->
                            <div class="mt-6">
                                <form id="couponForm" class="space-y-3">
                                    <label class="block text-sm font-medium text-gray-700">Coupon Code</label>
                                    <div class="flex space-x-2">
                                        <input type="text" name="coupon_code" id="couponCode" 
                                               placeholder="Enter coupon code"
                                               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                            Apply
                                        </button>
                                    </div>
                                </form>
                                <div id="couponMessage" class="mt-2 text-sm"></div>
                            </div>
                            
                            <!-- Checkout Button -->
                            <div class="mt-6">
                                <a href="{{ route('checkout.index') }}" 
                                   class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md text-sm font-medium text-center block">
                                    Proceed to Checkout
                                </a>
                            </div>
                            
                            <!-- Continue Shopping -->
                            <div class="mt-4 text-center">
                                <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-900 text-sm">
                                    Continue Shopping
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Coupon form submission
    document.getElementById('couponForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const couponCode = document.getElementById('couponCode').value;
        const messageDiv = document.getElementById('couponMessage');
        
        if (!couponCode) {
            messageDiv.innerHTML = '<span class="text-red-600">Please enter a coupon code.</span>';
            return;
        }
        
        fetch('{{ route("checkout.apply-coupon") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                coupon_code: couponCode
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageDiv.innerHTML = '<span class="text-green-600">' + data.message + '</span>';
                // Reload page to show updated totals
                setTimeout(() => location.reload(), 1500);
            } else {
                messageDiv.innerHTML = '<span class="text-red-600">' + data.message + '</span>';
            }
        })
        .catch(error => {
            messageDiv.innerHTML = '<span class="text-red-600">An error occurred. Please try again.</span>';
        });
    });
</script>
@endpush
