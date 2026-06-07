@extends('layouts.app')

@section('title', 'Order ' . $order->order_number)

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('customer.orders.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
            <i class="fas fa-arrow-left mr-1"></i> Back to Orders
        </a>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Order {{ $order->order_number }}</h1>
                    <p class="text-sm text-gray-600 mt-1">Placed on {{ $order->created_at->format('M j, Y g:i A') }}</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-800">
                    {{ ucfirst($order->status) }}
                </span>
            </div>

            <div class="border-t border-gray-200 pt-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($order->orderItems as $item)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->product_name }}</div>
                                    @if($item->product_variant_title)
                                        <div class="text-sm text-gray-500">{{ $item->product_variant_title }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">${{ number_format($item->price, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">${{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-200 mt-6 pt-6">
                <dl class="space-y-2">
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Subtotal:</dt>
                        <dd class="font-medium">${{ number_format($order->subtotal, 2) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Tax:</dt>
                        <dd class="font-medium">${{ number_format($order->tax, 2) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Shipping:</dt>
                        <dd class="font-medium">${{ number_format($order->shipping, 2) }}</dd>
                    </div>
                    @if($order->discount > 0)
                        <div class="flex justify-between text-green-600">
                            <dt>Discount:</dt>
                            <dd class="font-medium">-${{ number_format($order->discount, 2) }}</dd>
                        </div>
                    @endif
                    <div class="border-t pt-2 flex justify-between">
                        <dt class="text-lg font-medium text-gray-900">Total:</dt>
                        <dd class="text-lg font-medium text-gray-900">${{ number_format($order->total, 2) }}</dd>
                    </div>
                </dl>
            </div>

            @if($order->notes)
                <div class="border-t border-gray-200 mt-6 pt-6">
                    <h4 class="font-medium text-gray-900 mb-2">Order Notes</h4>
                    <p class="text-sm text-gray-600">{{ $order->notes }}</p>
                </div>
            @endif

            @if(in_array($order->status, ['pending', 'processing']))
                <div class="border-t border-gray-200 mt-6 pt-6">
                    <form method="POST" action="{{ route('customer.orders.cancel', $order) }}" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-times mr-2"></i>
                            Cancel Order
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
