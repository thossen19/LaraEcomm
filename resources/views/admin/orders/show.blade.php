@extends('admin.layouts.app')

@section('title', 'Order Details')

@section('header', 'Order Details')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6 lg:p-8">
            <!-- Order Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Order #{{ $order->order_number }}</h2>
                    <p class="text-sm text-gray-500">Placed on {{ $order->created_at->format('M j, Y g:i A') }}</p>
                </div>
                <div class="flex space-x-3">
                    <button onclick="printInvoice()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-print mr-2"></i>
                        Print Invoice
                    </button>
                    <a href="{{ route('admin.orders.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Orders
                    </a>
                </div>
            </div>

            <!-- Order Status and Actions -->
            <div class="bg-gray-50 p-6 rounded-lg mb-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Order Status -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Order Status</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Current Status</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $order->status_color }}">
                                    {{ $order->status_label }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Payment Status</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $order->payment_status_color }}">
                                    {{ $order->payment_status_label }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Update Status Form -->
                        <div class="mt-4">
                            <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="flex items-center space-x-3">
                                @csrf
                                <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Update Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="processing">Processing</option>
                                    <option value="shipped">Shipped</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="cancelled">Cancelled</option>
                                    <option value="refunded">Refunded</option>
                                </select>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    Update
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Order Summary -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h3>
                        <dl class="space-y-2">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-600">Subtotal</dt>
                                <dd class="text-sm font-medium text-gray-900">${{ number_format($order->subtotal, 2) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-600">Tax</dt>
                                <dd class="text-sm font-medium text-gray-900">${{ number_format($order->tax_amount, 2) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-600">Shipping</dt>
                                <dd class="text-sm font-medium text-gray-900">${{ number_format($order->shipping_amount, 2) }}</dd>
                            </div>
                            @if($order->discount_amount > 0)
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Discount</dt>
                                    <dd class="text-sm font-medium text-red-600">-${{ number_format($order->discount_amount, 2) }}</dd>
                                </div>
                            @endif
                            <div class="border-t pt-2 flex justify-between">
                                <dt class="text-base font-medium text-gray-900">Total</dt>
                                <dd class="text-base font-bold text-gray-900">${{ number_format($order->total, 2) }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Billing Address -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Billing Information</h3>
                    <div class="space-y-2">
                        @php $ba = json_decode($order->billing_address, true); @endphp
                        <p class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</p>
                        @if($ba && !empty($ba['company']))
                            <p class="text-sm text-gray-600">{{ $ba['company'] }}</p>
                        @endif
                        <p class="text-sm text-gray-600">{{ $ba['address_line_1'] ?? $order->billing_address }}</p>
                        @if($ba && !empty($ba['address_line_2']))
                            <p class="text-sm text-gray-600">{{ $ba['address_line_2'] }}</p>
                        @endif
                        <p class="text-sm text-gray-600">{{ $order->billing_city }}, {{ $order->billing_state }} {{ $order->billing_postal_code }}</p>
                        <p class="text-sm text-gray-600">{{ $order->billing_country }}</p>
                        <p class="text-sm text-gray-600">{{ $order->customer_phone }}</p>
                        <p class="text-sm text-gray-600">{{ $order->customer_email }}</p>
                    </div>
                </div>
                
                <!-- Shipping Address -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Shipping Information</h3>
                    <div class="space-y-2">
                        @php $sa = json_decode($order->shipping_address, true); @endphp
                        <p class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</p>
                        @if($sa && !empty($sa['company']))
                            <p class="text-sm text-gray-600">{{ $sa['company'] }}</p>
                        @endif
                        <p class="text-sm text-gray-600">{{ $sa['address_line_1'] ?? $order->shipping_address }}</p>
                        @if($sa && !empty($sa['address_line_2']))
                            <p class="text-sm text-gray-600">{{ $sa['address_line_2'] }}</p>
                        @endif
                        <p class="text-sm text-gray-600">{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}</p>
                        <p class="text-sm text-gray-600">{{ $order->shipping_country }}</p>
                        @if($order->tracking_number)
                            <div class="mt-3 p-2 bg-blue-50 rounded">
                                <p class="text-sm font-medium text-blue-800">Tracking Number</p>
                                <p class="text-sm text-blue-600">{{ $order->tracking_number }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-gray-50 p-6 rounded-lg mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Order Items</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->orderItems as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" 
                                                     class="h-10 w-10 rounded object-cover mr-3">
                                            @else
                                                <div class="h-10 w-10 bg-gray-200 rounded flex items-center justify-center mr-3">
                                                    <i class="fas fa-box text-gray-400"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                                @if($item->variant)
                                                    <div class="text-xs text-gray-500">{{ $item->variant->name }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->product->sku }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ${{ number_format($item->price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        ${{ number_format($item->total, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Order Timeline</h3>
                <div class="space-y-4">
                    <!-- Order Created -->
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-shopping-cart text-green-600 text-xs"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900">Order Created</p>
                                <p class="text-xs text-gray-500">{{ $order->created_at->format('M j, Y H:i') }}</p>
                            </div>
                            <p class="text-sm text-gray-600">Order #{{ $order->order_number }} was placed by {{ $order->customer_name }}</p>
                        </div>
                    </div>

                    <!-- Order Processing -->
                    @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-cog text-blue-600 text-xs"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900">Order Processing</p>
                                <p class="text-xs text-gray-500">{{ $order->updated_at->format('M j, Y H:i') }}</p>
                            </div>
                            <p class="text-sm text-gray-600">Order is being processed and prepared for shipment</p>
                        </div>
                    </div>
                    @endif

                    <!-- Order Shipped -->
                    @if($order->shipped_at)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center">
                                <i class="fas fa-truck text-purple-600 text-xs"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900">Order Shipped</p>
                                <p class="text-xs text-gray-500">{{ $order->shipped_at->format('M j, Y H:i') }}</p>
                            </div>
                            <p class="text-sm text-gray-600">Order has been shipped and is on its way</p>
                            @if($order->tracking_number)
                            <p class="text-sm text-gray-600">Tracking: {{ $order->tracking_number }}</p>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Order Delivered -->
                    @if($order->delivered_at)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900">Order Delivered</p>
                                <p class="text-xs text-gray-500">{{ $order->delivered_at->format('M j, Y H:i') }}</p>
                            </div>
                            <p class="text-sm text-gray-600">Order has been successfully delivered</p>
                        </div>
                    </div>
                    @endif

                    <!-- Order Cancelled -->
                    @if($order->status === 'cancelled')
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center">
                                <i class="fas fa-times text-red-600 text-xs"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900">Order Cancelled</p>
                                <p class="text-xs text-gray-500">{{ $order->updated_at->format('M j, Y H:i') }}</p>
                            </div>
                            <p class="text-sm text-gray-600">Order was cancelled</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function printInvoice() {
        window.open('{{ route('admin.orders.invoice', $order) }}', '_blank');
    }
</script>
@endpush
@endsection
