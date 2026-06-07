@extends('admin.layouts.app')

@section('title', 'Customer Details')

@section('header', 'Customer Details')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6 lg:p-8">
            <!-- Customer Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0 h-16 w-16">
                        @if($customer->profile_image)
                            <img class="h-16 w-16 rounded-full" src="{{ asset('storage/' . $customer->profile_image) }}" alt="">
                        @else
                            <div class="h-16 w-16 rounded-full bg-gray-300 flex items-center justify-center">
                                <i class="fas fa-user text-gray-500 text-2xl"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $customer->first_name }} {{ $customer->last_name }}</h2>
                        <p class="text-sm text-gray-500">Customer ID: #{{ $customer->id }}</p>
                        <p class="text-sm text-gray-500">Member since {{ $customer->created_at->format('M j, Y') }}</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <button onclick="toggleCustomerStatus()" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-ban mr-2"></i>
                        {{ $customer->is_active ? 'Block' : 'Unblock' }} Customer
                    </button>
                    <a href="mailto:{{ $customer->email }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-envelope mr-2"></i>
                        Send Email
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Customers
                    </a>
                </div>
            </div>

            <!-- Customer Info Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Customer Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Personal Information -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                        <dl class="grid grid-cols-1 gap-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">First Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $customer->first_name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Last Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $customer->last_name }}</dd>
                                </div>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $customer->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $customer->phone ?? 'Not provided' }}</dd>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $customer->date_of_birth ? $customer->date_of_birth->format('M j, Y') : 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Gender</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $customer->gender ?? 'Not specified' }}</dd>
                                </div>
                            </div>
                        </dl>
                    </div>

                    <!-- Addresses -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Addresses</h3>
                        @if($customer->addresses && $customer->addresses->count() > 0)
                            <div class="space-y-4">
                                @foreach($customer->addresses as $address)
                                    <div class="border-l-4 {{ $address->is_default ? 'border-blue-500' : 'border-gray-300' }} pl-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="text-sm font-medium text-gray-900">
                                                {{ $address->type == 'billing' ? 'Billing' : 'Shipping' }} Address
                                                @if($address->is_default)
                                                    <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">Default</span>
                                                @endif
                                            </h4>
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            <p>{{ $address->first_name }} {{ $address->last_name }}</p>
                                            <p>{{ $address->address }}</p>
                                            <p>{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                                            <p>{{ $address->country }}</p>
                                            <p>{{ $address->phone }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">No addresses added</p>
                        @endif
                    </div>

                    <!-- Recent Orders -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Recent Orders</h3>
                            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                View All Orders
                            </a>
                        </div>
                        @if($customer->orders && $customer->orders->count() > 0)
                            <div class="space-y-4">
                                @foreach($customer->orders->take(5) as $order)
                                    <div class="flex items-center justify-between p-4 bg-white rounded border">
                                        <div class="flex items-center space-x-4">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $order->order_number }}</p>
                                                <p class="text-xs text-gray-500">{{ $order->created_at->format('M j, Y') }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">${{ number_format($order->total, 2) }}</p>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->status_color }}">
                                                    {{ $order->status_label }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">No orders yet</p>
                        @endif
                    </div>

                    <!-- Customer Activity -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activity</h3>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-shopping-cart text-blue-600 text-xs"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Placed Order #12345</p>
                                    <p class="text-xs text-gray-500">2 days ago</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                        <i class="fas fa-user text-green-600 text-xs"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Updated Profile</p>
                                    <p class="text-xs text-gray-500">1 week ago</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center">
                                        <i class="fas fa-sign-in-alt text-purple-600 text-xs"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Account Created</p>
                                    <p class="text-xs text-gray-500">{{ $customer->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Information -->
                <div class="space-y-6">
                    <!-- Account Status -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Account Status</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Account Status</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $customer->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $customer->is_active ? 'Active' : 'Blocked' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Email Verified</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $customer->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $customer->email_verified_at ? 'Verified' : 'Not Verified' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Last Login</span>
                                <span class="text-sm text-gray-900">{{ $customer->last_login_at ? $customer->last_login_at->format('M j, Y') : 'Never' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Statistics</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Total Orders</span>
                                <span class="text-sm text-gray-900">{{ $customer->orders_count ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Total Spent</span>
                                <span class="text-sm text-gray-900">${{ number_format($customer->total_spent ?? 0, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Average Order</span>
                                <span class="text-sm text-gray-900">${{ number_format($customer->average_order_value ?? 0, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Addresses</span>
                                <span class="text-sm text-gray-900">{{ $customer->addresses_count ?? 0 }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <form method="POST" action="{{ route('admin.customers.toggle-status', $customer) }}" class="inline">
                                @csrf
                                <button type="submit" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-ban mr-2"></i>
                                    {{ $customer->is_active ? 'Block Customer' : 'Unblock Customer' }}
                                </button>
                            </form>
                            
                            <a href="mailto:{{ $customer->email }}" 
                               class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium inline-block text-center">
                                <i class="fas fa-envelope mr-2"></i>
                                Send Email
                            </a>
                            
                            <button onclick="resetPassword()" 
                                    class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-key mr-2"></i>
                                Reset Password
                            </button>
                            
                            <a href="{{ route('admin.customers.index') }}" 
                               class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium inline-block text-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back to Customers
                            </a>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Admin Notes</h3>
                        <textarea rows="4" placeholder="Add notes about this customer..."
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $customer->admin_notes ?? '' }}</textarea>
                        <button onclick="saveNotes()" class="mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Save Notes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleCustomerStatus() {
        if (confirm('Are you sure you want to {{ $customer->is_active ? "block" : "unblock" }} this customer?')) {
            document.querySelector('form[action*="toggle-status"]').submit();
        }
    }
    
    function resetPassword() {
        if (confirm('Are you sure you want to reset this customer\'s password? A new password will be emailed to them.')) {
            // Implement password reset functionality
            fetch(`/admin/customers/{{ $customer->id }}/reset-password`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Password reset email sent successfully!');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
    
    function saveNotes() {
        const notes = document.querySelector('textarea').value;
        fetch(`/admin/customers/{{ $customer->id }}/notes`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ notes: notes })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Notes saved successfully!');
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
@endpush
@endsection
