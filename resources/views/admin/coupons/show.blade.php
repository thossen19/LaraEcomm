@extends('admin.layouts.app')

@section('title', 'Coupon Details')

@section('header', 'Coupon Details')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6 lg:p-8">
            <!-- Coupon Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $coupon->name }}</h2>
                    <p class="text-sm text-gray-500">Code: <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $coupon->code }}</span></p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.coupons.edit', $coupon) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Coupon
                    </a>
                    <button onclick="duplicateCoupon()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-copy mr-2"></i>
                        Duplicate
                    </button>
                    <a href="{{ route('admin.coupons.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Coupons
                    </a>
                </div>
            </div>

            <!-- Coupon Info Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Coupon Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                        <dl class="grid grid-cols-1 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $coupon->description ?? 'No description provided' }}</dd>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Discount Type</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($coupon->type) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Discount Value</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if($coupon->type == 'percentage')
                                            <span class="text-green-600 font-bold">{{ $coupon->value }}%</span>
                                        @else
                                            <span class="text-blue-600 font-bold">${{ number_format($coupon->value, 2) }}</span>
                                        @endif
                                    </dd>
                                </div>
                            </div>
                            @if($coupon->minimum_amount)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Minimum Order Amount</dt>
                                    <dd class="mt-1 text-sm text-gray-900">${{ number_format($coupon->minimum_amount, 2) }}</dd>
                                </div>
                            @endif
                            @if($coupon->maximum_discount)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Maximum Discount</dt>
                                    <dd class="mt-1 text-sm text-gray-900">${{ number_format($coupon->maximum_discount, 2) }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Date Restrictions -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Date Restrictions</h3>
                        <dl class="grid grid-cols-1 gap-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $coupon->starts_at ? $coupon->starts_at->format('M j, Y g:i A') : 'Immediate' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Expiry Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $coupon->expires_at ? $coupon->expires_at->format('M j, Y g:i A') : 'No expiry' }}
                                    </dd>
                                </div>
                            </div>
                            @if($coupon->expires_at)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Days Remaining</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $coupon->expires_at->diffInDays(now()) }} days
                                        @if($coupon->expires_at->isPast())
                                    <span class="text-red-600">(Expired)</span>
                                        @endif
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Usage Limits -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Usage Limits</h3>
                        <dl class="grid grid-cols-1 gap-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Usage Limit per Customer</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $coupon->usage_limit_per_customer ?? 'Unlimited' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Total Usage Limit</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $coupon->usage_limit ?? 'Unlimited' }}
                                    </dd>
                                </div>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Current Usage</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $coupon->times_used ?? 0 }} times used</dd>
                            </div>
                            @if($coupon->usage_limit)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Usage Progress</dt>
                                    <dd class="mt-1">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ min(($coupon->times_used / $coupon->usage_limit) * 100, 100) }}%"></div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">{{ $coupon->times_used }} / {{ $coupon->usage_limit }}</p>
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Product/Category Restrictions -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Applicable Products/Categories</h3>
                        <div class="space-y-4">
                            @if($coupon->products && $coupon->products->count() > 0)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Specific Products ({{ $coupon->products->count() }})</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        @foreach($coupon->products as $product)
                                            <div class="flex items-center space-x-2 p-2 bg-white rounded border">
                                                @if($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                                         class="h-6 w-6 rounded object-cover">
                                                @else
                                                    <div class="h-6 w-6 bg-gray-200 rounded flex items-center justify-center">
                                                        <i class="fas fa-box text-gray-400 text-xs"></i>
                                                    </div>
                                                @endif
                                                <span class="text-sm text-gray-900">{{ $product->name }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            @if($coupon->categories && $coupon->categories->count() > 0)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Categories ({{ $coupon->categories->count() }})</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($coupon->categories as $category)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $category->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            @if($coupon->excluded_products && $coupon->excluded_products->count() > 0)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Excluded Products ({{ $coupon->excluded_products->count() }})</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        @foreach($coupon->excluded_products as $product)
                                            <div class="flex items-center space-x-2 p-2 bg-white rounded border">
                                                @if($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                                         class="h-6 w-6 rounded object-cover">
                                                @else
                                                    <div class="h-6 w-6 bg-gray-200 rounded flex items-center justify-center">
                                                        <i class="fas fa-box text-gray-400 text-xs"></i>
                                                    </div>
                                                @endif
                                                <span class="text-sm text-gray-900">{{ $product->name }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            @if((!$coupon->products || !$coupon->products->count()) && (!$coupon->categories || !$coupon->categories->count()) && (!$coupon->excluded_products || !$coupon->excluded_products->count()))
                                <p class="text-sm text-gray-500">Applies to all products</p>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Usage -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Usage</h3>
                        @if($coupon->recent_usage && $coupon->recent_usage->count() > 0)
                            <div class="space-y-3">
                                @foreach($coupon->recent_usage as $usage)
                                    <div class="flex items-center justify-between p-3 bg-white rounded border">
                                        <div class="flex items-center space-x-3">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Order #{{ $usage->order_number }}</p>
                                                <p class="text-xs text-gray-500">{{ $usage->customer_name }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">${{ number_format($usage->discount_amount, 2) }}</p>
                                                <p class="text-xs text-gray-500">{{ $usage->created_at->format('M j, Y g:i A') }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('admin.orders.show', $usage->order_id) }}" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">No usage history yet</p>
                        @endif
                    </div>
                </div>

                <!-- Sidebar Information -->
                <div class="space-y-6">
                    <!-- Status Information -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Status</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Coupon Status</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $coupon->status_color }}">
                                    {{ $coupon->status_label }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Visibility</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Private
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Statistics</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Times Used</span>
                                <span class="text-sm text-gray-900">{{ $coupon->times_used ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Total Savings</span>
                                <span class="text-sm text-gray-900">${{ number_format($coupon->total_savings ?? 0, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Unique Customers</span>
                                <span class="text-sm text-gray-900">{{ $coupon->unique_customers ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Average Discount</span>
                                <span class="text-sm text-gray-900">
                                    ${{ number_format($coupon->times_used ? ($coupon->total_savings / $coupon->times_used) : 0, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <form method="POST" action="{{ route('admin.coupons.toggle-status', $coupon) }}" class="inline">
                                @csrf
                                <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-toggle-on mr-2"></i>
                                    {{ $coupon->is_active ? 'Deactivate' : 'Activate' }} Coupon
                                </button>
                            </form>
                            
                            <button onclick="duplicateCoupon()" 
                                    class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-copy mr-2"></i>
                                Duplicate Coupon
                            </button>
                            
                            <button onclick="sendTestEmail()" 
                                    class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-envelope mr-2"></i>
                                Send Test Email
                            </button>
                            
                            <a href="{{ route('admin.coupons.index') }}" 
                               class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium inline-block text-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back to Coupons
                            </a>
                        </div>
                    </div>

                    <!-- Timestamps -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Timestamps</h3>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Created</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $coupon->created_at->format('M j, Y g:i A') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $coupon->updated_at->format('M j, Y g:i A') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function duplicateCoupon() {
        if (confirm('Are you sure you want to duplicate this coupon?')) {
            fetch(`/admin/coupons/{{ $coupon->id }}/duplicate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = `/admin/coupons/${data.coupon_id}/edit`;
                } else {
                    alert('Error duplicating coupon: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
    
    function sendTestEmail() {
        if (confirm('Send a test email with this coupon to your email address?')) {
            fetch(`/admin/coupons/{{ $coupon->id }}/send-test`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Test email sent successfully!');
                } else {
                    alert('Error sending test email: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
</script>
@endpush
@endsection
