@extends('layouts.app')

@section('title', 'My Addresses')

@section('content')
<div class="max-w-6xl mx-auto py-8">
    <!-- Header -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
            <div class="max-w-3xl mx-auto flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">My Addresses</h1>
                <a href="{{ route('customer.addresses.create', ['type' => 'billing']) }}" 
                   class="bg-white text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                    <i class="fas fa-plus mr-2"></i>Add Billing Address
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Addresses Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Billing Addresses -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4">
                <h2 class="text-lg font-semibold text-white">
                    <i class="fas fa-credit-card mr-2"></i>Billing Addresses
                </h2>
            </div>
            
            <div class="p-6">
                @if($addresses->where('type', 'billing')->count() > 0)
                    <div class="space-y-4">
                        @foreach($addresses->where('type', 'billing') as $address)
                            <div class="border border-gray-200 rounded-lg p-4 {{ $address->is_default ? 'border-purple-500 bg-purple-50' : '' }}">
                                @if($address->is_default)
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="bg-purple-600 text-white text-xs px-2 py-1 rounded-full font-medium">
                                            <i class="fas fa-star mr-1"></i>Default
                                        </span>
                                    </div>
                                @endif
                                
                                <div class="space-y-2">
                                    <div class="font-semibold text-gray-900">
                                        {{ $address->full_name }}
                                    </div>
                                    
                                    @if($address->email)
                                        <div class="text-sm text-gray-600">
                                            <i class="fas fa-envelope mr-1"></i>{{ $address->email }}
                                        </div>
                                    @endif
                                    
                                    <div class="text-sm text-gray-600">
                                        <i class="fas fa-phone mr-1"></i>{{ $address->phone }}
                                    </div>
                                    
                                    <div class="text-sm text-gray-700">
                                        {{ $address->address_line_1 }}
                                        @if($address->address_line_2)
                                            <br>{{ $address->address_line_2 }}
                                        @endif
                                        <br>{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}
                                        <br>{{ $address->country }}
                                    </div>
                                </div>
                                
                                <div class="flex justify-end space-x-2 mt-4">
                                    <a href="{{ route('customer.addresses.edit', $address->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>
                                    
                                    <form action="{{ route('customer.addresses.destroy', $address->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('Are you sure you want to delete this address?')"
                                                class="text-red-600 hover:text-red-800 text-sm font-medium">
                                            <i class="fas fa-trash mr-1"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-credit-card text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 mb-4">No billing addresses found.</p>
                        <a href="{{ route('customer.addresses.create', ['type' => 'billing']) }}" 
                           class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                            <i class="fas fa-plus mr-2"></i>Add Billing Address
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Shipping Addresses -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
                <h2 class="text-lg font-semibold text-white">
                    <i class="fas fa-truck mr-2"></i>Shipping Addresses
                </h2>
            </div>
            
            <div class="p-6">
                @if($addresses->where('type', 'shipping')->count() > 0)
                    <div class="space-y-4">
                        @foreach($addresses->where('type', 'shipping') as $address)
                            <div class="border border-gray-200 rounded-lg p-4 {{ $address->is_default ? 'border-green-500 bg-green-50' : '' }}">
                                @if($address->is_default)
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="bg-green-600 text-white text-xs px-2 py-1 rounded-full font-medium">
                                            <i class="fas fa-star mr-1"></i>Default
                                        </span>
                                    </div>
                                @endif
                                
                                <div class="space-y-2">
                                    <div class="font-semibold text-gray-900">
                                        {{ $address->full_name }}
                                    </div>
                                    
                                    @if($address->email)
                                        <div class="text-sm text-gray-600">
                                            <i class="fas fa-envelope mr-1"></i>{{ $address->email }}
                                        </div>
                                    @endif
                                    
                                    <div class="text-sm text-gray-600">
                                        <i class="fas fa-phone mr-1"></i>{{ $address->phone }}
                                    </div>
                                    
                                    <div class="text-sm text-gray-700">
                                        {{ $address->address_line_1 }}
                                        @if($address->address_line_2)
                                            <br>{{ $address->address_line_2 }}
                                        @endif
                                        <br>{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}
                                        <br>{{ $address->country }}
                                    </div>
                                </div>
                                
                                <div class="flex justify-end space-x-2 mt-4">
                                    <a href="{{ route('customer.addresses.edit', $address->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>
                                    
                                    <form action="{{ route('customer.addresses.destroy', $address->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('Are you sure you want to delete this address?')"
                                                class="text-red-600 hover:text-red-800 text-sm font-medium">
                                            <i class="fas fa-trash mr-1"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-truck text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 mb-4">No shipping addresses found.</p>
                        <a href="{{ route('customer.addresses.create', ['type' => 'shipping']) }}" 
                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-200">
                            <i class="fas fa-plus mr-2"></i>Add Shipping Address
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-4">
            <i class="fas fa-info-circle mr-2"></i>Quick Actions
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('customer.addresses.create', ['type' => 'billing']) }}" 
               class="flex items-center p-4 bg-white rounded-lg border border-blue-200 hover:bg-blue-50 transition duration-200">
                <i class="fas fa-plus-circle text-blue-600 text-xl mr-3"></i>
                <div>
                    <div class="font-medium text-gray-900">Add Billing Address</div>
                    <div class="text-sm text-gray-600">For invoices and payments</div>
                </div>
            </a>
            
            <a href="{{ route('customer.addresses.create', ['type' => 'shipping']) }}" 
               class="flex items-center p-4 bg-white rounded-lg border border-green-200 hover:bg-green-50 transition duration-200">
                <i class="fas fa-plus-circle text-green-600 text-xl mr-3"></i>
                <div>
                    <div class="font-medium text-gray-900">Add Shipping Address</div>
                    <div class="text-sm text-gray-600">For product deliveries</div>
                </div>
            </a>
            
            <a href="{{ route('dashboard') }}" 
               class="flex items-center p-4 bg-white rounded-lg border border-gray-200 hover:bg-gray-50 transition duration-200">
                <i class="fas fa-arrow-left text-gray-600 text-xl mr-3"></i>
                <div>
                    <div class="font-medium text-gray-900">Back to Dashboard</div>
                    <div class="text-sm text-gray-600">Return to account overview</div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
