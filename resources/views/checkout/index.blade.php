@extends('layouts.app')

@section('title', 'Checkout')

@php
    $currentCurrency = \App\Models\Setting::get('currency', 'USD');
@endphp

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Checkout</h1>
            
            <form method="POST" action="{{ route('checkout.process') }}">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column - Addresses and Payment -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Billing Address -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Billing Address</h3>
                            
                            @if($addresses->where('type', 'billing')->count() > 0)
                                <div class="space-y-3">
                                    @foreach($addresses->where('type', 'billing') as $address)
                                        <label class="flex items-start space-x-3 p-3 border rounded-lg cursor-pointer hover:bg-white {{ $defaultBilling && $defaultBilling->id == $address->id ? 'bg-white border-blue-500' : 'border-gray-200' }}">
                                            <input type="radio" name="billing_address_id" value="{{ $address->id }}" 
                                                   {{ $defaultBilling && $defaultBilling->id == $address->id ? 'checked' : '' }}
                                                   class="mt-1" required>
                                            <div class="flex-1">
                                                <p class="font-medium">{{ $address->full_name }}</p>
                                                <p class="text-sm text-gray-600">{{ $address->full_address }}</p>
                                                <p class="text-sm text-gray-600">{{ $address->phone }}</p>
                                                @if($address->is_default)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                                        Default
                                                    </span>
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <p class="text-gray-500 mb-3">No billing addresses found.</p>
                                    <a href="{{ route('customer.addresses.create', ['type' => 'billing']) }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                        Add Billing Address
                                    </a>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Shipping Address -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Shipping Address</h3>
                            
                            @if($addresses->where('type', 'shipping')->count() > 0)
                                <div class="space-y-3">
                                    @foreach($addresses->where('type', 'shipping') as $address)
                                        <label class="flex items-start space-x-3 p-3 border rounded-lg cursor-pointer hover:bg-white {{ $defaultShipping && $defaultShipping->id == $address->id ? 'bg-white border-blue-500' : 'border-gray-200' }}">
                                            <input type="radio" name="shipping_address_id" value="{{ $address->id }}" 
                                                   {{ $defaultShipping && $defaultShipping->id == $address->id ? 'checked' : '' }}
                                                   class="mt-1" required>
                                            <div class="flex-1">
                                                <p class="font-medium">{{ $address->full_name }}</p>
                                                <p class="text-sm text-gray-600">{{ $address->full_address }}</p>
                                                <p class="text-sm text-gray-600">{{ $address->phone }}</p>
                                                @if($address->is_default)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                                        Default
                                                    </span>
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <p class="text-gray-500 mb-3">No shipping addresses found.</p>
                                    <a href="{{ route('customer.addresses.create', ['type' => 'shipping']) }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                        Add Shipping Address
                                    </a>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Payment Method -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Method</h3>
                            @php
                                $firstEnabled = null;
                                $paymentMethods = [
                                    'credit_card' => ['label' => 'Credit Card', 'icon' => 'fas fa-credit-card', 'icon_class' => 'text-gray-600'],
                                    'paypal' => ['label' => 'PayPal', 'icon' => 'fab fa-paypal', 'icon_class' => 'text-blue-600'],
                                    'bank_transfer' => ['label' => 'Bank Transfer', 'icon' => 'fas fa-university', 'icon_class' => 'text-gray-600'],
                                    'cod' => ['label' => 'Cash on Delivery', 'icon' => 'fas fa-money-bill-wave', 'icon_class' => 'text-green-600'],
                                ];
                                // Map checkout payment method keys to admin gateway keys
                                $gatewayMap = [
                                    'credit_card' => 'stripe',
                                    'paypal' => 'paypal',
                                    'cod' => 'cod',
                                ];
                            @endphp
                            <div class="space-y-3">
                                @foreach($paymentMethods as $key => $method)
                                    @php
                                        $isGatewayEnabled = false;
                                        if ($key === 'bank_transfer') {
                                            $isGatewayEnabled = true; // always show bank transfer
                                        } elseif (isset($gatewayMap[$key])) {
                                            $gwKey = $gatewayMap[$key];
                                            $isGatewayEnabled = isset($gateways[$gwKey]['enabled']) && filter_var($gateways[$gwKey]['enabled'], FILTER_VALIDATE_BOOLEAN);
                                        }
                                    @endphp
                                    @if($isGatewayEnabled)
                                        @php
                                            if (!$firstEnabled) $firstEnabled = $key;
                                        @endphp
                                        <label class="flex items-center space-x-3 p-3 border rounded-lg cursor-pointer hover:bg-white border-gray-200">
                                            <input type="radio" name="payment_method" value="{{ $key }}" {{ $key === ($firstEnabled ?? 'credit_card') ? 'checked' : '' }} required>
                                            <div class="flex items-center space-x-3">
                                                <i class="{{ $method['icon'] }} {{ $method['icon_class'] }}"></i>
                                                <span>{{ $method['label'] }}</span>
                                            </div>
                                        </label>
                                    @endif
                                @endforeach
                                @if(!$firstEnabled)
                                    <p class="text-sm text-red-600">No payment methods are currently enabled. Please contact support.</p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Order Notes -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Order Notes (Optional)</h3>
                            <textarea name="notes" rows="3" 
                                      placeholder="Any special instructions for your order..."
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        </div>
                    </div>
                    
                    <!-- Right Column - Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-gray-50 rounded-lg p-6 sticky top-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h3>
                            
                            <!-- Cart Items -->
                            <div class="space-y-3 mb-6">
                                @foreach($cart->cartItems as $item)
                                    <div class="flex items-center space-x-3">
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
                                            <p class="text-sm font-medium truncate">{{ $item->product->name }}</p>
                                            <p class="text-xs text-gray-500">
                                                Qty: {{ $item->quantity }} × {{ \App\Helpers\CurrencyHelper::format($item->price, $currentCurrency) }}
                                            </p>
                                        </div>
                                        <p class="text-sm font-medium">{{ \App\Helpers\CurrencyHelper::format($item->total, $currentCurrency) }}</p>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Totals -->
                            <div class="border-t pt-4 space-y-2">
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
                                
                                <div class="border-t pt-2">
                                    <div class="flex justify-between">
                                        <span class="text-lg font-medium text-gray-900">Total</span>
                                        <span class="text-lg font-medium text-gray-900">{{ \App\Helpers\CurrencyHelper::format($cart->total, $currentCurrency) }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Coupon Code -->
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Coupon Code</label>
                                <input type="text" name="coupon_code" 
                                       placeholder="Enter coupon code"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            
                            <!-- Place Order Button -->
                            <div class="mt-6">
                                <button type="submit" 
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md text-sm font-medium">
                                    <i class="fas fa-lock mr-2"></i>
                                    Place Order
                                </button>
                                <p class="text-xs text-gray-500 text-center mt-2">
                                    By placing this order, you agree to our Terms of Service and Privacy Policy
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
