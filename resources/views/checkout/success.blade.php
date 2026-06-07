@extends('layouts.app')

@section('title', 'Order Confirmation')

@php
    $currentCurrency = \App\Models\Setting::get('currency', 'USD');
@endphp

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <!-- Success Message -->
            <div class="text-center mb-8">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <i class="fas fa-check text-green-600 text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Order Placed Successfully!</h1>
                <p class="text-lg text-gray-600">Thank you for your order. We've received your order and will begin processing it.</p>
            </div>
            
            <!-- Order Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Order Information -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Order Information</h3>
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Order Number:</dt>
                            <dd class="font-medium">{{ $order->order_number }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Order Date:</dt>
                            <dd class="font-medium">{{ $order->created_at->format('M j, Y g:i A') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Status:</dt>
                            <dd>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->status_color }}">
                                    {{ $order->status_label }}
                                </span>
                            </dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Payment Method:</dt>
                            <dd class="font-medium">{{ ucfirst(str_replace('_', ' ', $order->payment->payment_method ?? 'N/A')) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Payment Status:</dt>
                            <dd>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->payment->status_color ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($order->payment->status ?? 'pending') }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
                
                <!-- Customer Information -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>
                    <dl class="space-y-2">
                        <div>
                            <dt class="text-gray-600">Name:</dt>
                            <dd class="font-medium">{{ $order->customer_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-600">Email:</dt>
                            <dd class="font-medium">{{ $order->customer_email }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-600">Phone:</dt>
                            <dd class="font-medium">{{ $order->customer_phone }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
            
            <!-- Order Items -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Order Items</h3>
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Product
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    SKU
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Price
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantity
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->orderItems as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $item->product_name }}</div>
                                            @if($item->product_variant_title)
                                                <div class="text-sm text-gray-500">{{ $item->product_variant_title }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->product_sku }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \App\Helpers\CurrencyHelper::format($item->price, $currentCurrency) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ \App\Helpers\CurrencyHelper::format($item->total, $currentCurrency) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Order Totals -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Addresses -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Shipping & Billing</h3>
                    
                    <div class="mb-4">
                        <h4 class="font-medium text-gray-900 mb-2">Shipping Address</h4>
                        @php
                            $shippingAddress = json_decode($order->shipping_address, true);
                        @endphp
                        <div class="text-sm text-gray-600">
                            <p>{{ $shippingAddress['first_name'] }} {{ $shippingAddress['last_name'] }}</p>
                            <p>{{ $shippingAddress['address_line_1'] }}</p>
                            @if($shippingAddress['address_line_2'])<p>{{ $shippingAddress['address_line_2'] }}</p>@endif
                            <p>{{ $shippingAddress['city'] }}, {{ $shippingAddress['state'] ?? '' }} {{ $shippingAddress['postal_code'] }}</p>
                            <p>{{ $shippingAddress['country'] }}</p>
                            <p>{{ $shippingAddress['phone'] ?? '' }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Billing Address</h4>
                        @php
                            $billingAddress = json_decode($order->billing_address, true);
                        @endphp
                        <div class="text-sm text-gray-600">
                            <p>{{ $billingAddress['first_name'] }} {{ $billingAddress['last_name'] }}</p>
                            <p>{{ $billingAddress['address_line_1'] }}</p>
                            @if($billingAddress['address_line_2'])<p>{{ $billingAddress['address_line_2'] }}</p>@endif
                            <p>{{ $billingAddress['city'] }}, {{ $billingAddress['state'] ?? '' }} {{ $billingAddress['postal_code'] }}</p>
                            <p>{{ $billingAddress['country'] }}</p>
                            <p>{{ $billingAddress['phone'] ?? '' }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Totals -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Order Totals</h3>
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Subtotal:</dt>
                            <dd class="font-medium">{{ \App\Helpers\CurrencyHelper::format($order->subtotal, $currentCurrency) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Tax:</dt>
                            <dd class="font-medium">{{ \App\Helpers\CurrencyHelper::format($order->tax, $currentCurrency) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-600">Shipping:</dt>
                            <dd class="font-medium">{{ \App\Helpers\CurrencyHelper::format($order->shipping, $currentCurrency) }}</dd>
                        </div>
                        @if($order->discount > 0)
                            <div class="flex justify-between text-green-600">
                                <dt>Discount:</dt>
                                <dd class="font-medium">-{{ \App\Helpers\CurrencyHelper::format($order->discount, $currentCurrency) }}</dd>
                            </div>
                        @endif
                        <div class="border-t pt-2">
                            <div class="flex justify-between">
                                <dt class="text-lg font-medium text-gray-900">Total:</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ \App\Helpers\CurrencyHelper::format($order->total, $currentCurrency) }}</dd>
                            </div>
                        </div>
                    </dl>
                    
                    @if($order->notes)
                        <div class="mt-4">
                            <h4 class="font-medium text-gray-900 mb-2">Order Notes</h4>
                            <p class="text-sm text-gray-600">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 mt-8">
                <a href="{{ route('customer.orders.index') }}" 
                   class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md text-sm font-medium text-center">
                    <i class="fas fa-list mr-2"></i>
                    View My Orders
                </a>
                <a href="{{ route('products.index') }}" 
                   class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-md text-sm font-medium text-center">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
