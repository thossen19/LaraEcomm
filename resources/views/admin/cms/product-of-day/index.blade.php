@extends('admin.layouts.app')

@section('title', 'Product of the Day Management')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-star mr-2 text-yellow-500"></i>
                    Product of the Day Management
                </h1>
                <a href="{{ route('admin.cms.product-of-day.create') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-plus mr-1"></i>
                    Add Product of the Day
                </a>
            </div>

            <!-- Filters -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <form method="GET" action="{{ route('admin.cms.product-of-day.index') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="form-input mt-1">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-filter mr-1"></i>
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Products Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Product
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Discount
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Schedule
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($productsOfDay as $productOfDay)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($productOfDay->featured_image_url)
                                            <img src="{{ $productOfDay->featured_image_url }}" alt="{{ $productOfDay->title }}" 
                                                 class="h-12 w-12 object-cover rounded mr-3">
                                        @elseif($productOfDay->product && $productOfDay->product->featured_image)
                                            <img src="{{ asset('storage/' . $productOfDay->product->featured_image) }}" alt="{{ $productOfDay->title }}" 
                                                 class="h-12 w-12 object-cover rounded mr-3">
                                        @else
                                            <div class="h-12 w-12 bg-gradient-to-r from-yellow-400 to-orange-400 rounded mr-3 flex items-center justify-center">
                                                <i class="fas fa-star text-white text-lg"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $productOfDay->title }}</div>
                                            @if($productOfDay->product)
                                                <div class="text-xs text-gray-500">Linked to: {{ $productOfDay->product->name }}</div>
                                            @endif
                                            <div class="mt-1">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium" 
                                                      style="background-color: {{ $productOfDay->badge_color }}20; color: {{ $productOfDay->badge_color }}">
                                                    {{ $productOfDay->badge_text }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($productOfDay->discount_percentage)
                                        <div class="text-sm">
                                            <span class="text-lg font-bold text-green-600">{{ $productOfDay->formatted_discount }}</span>
                                            @if($productOfDay->original_price && $productOfDay->sale_price)
                                                <div class="text-xs text-gray-500">
                                                    {{ $productOfDay->formatted_original_price }} → {{ $productOfDay->formatted_sale_price }}
                                                </div>
                                                <div class="text-xs text-green-600 font-medium">
                                                    Save {{ $productOfDay->formatted_savings_amount }}
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">No discount</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($productOfDay->start_date || $productOfDay->end_date)
                                        <div>
                                            @if($productOfDay->start_date)
                                                <div>From: {{ $productOfDay->start_date->format('M d, Y') }}</div>
                                            @endif
                                            @if($productOfDay->end_date)
                                                <div>To: {{ $productOfDay->end_date->format('M d, Y') }}</div>
                                            @endif
                                        </div>
                                        <div class="text-xs mt-1">
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium 
                                                @if($productOfDay->urgency_level == 'critical') bg-red-100 text-red-800
                                                @elseif($productOfDay->urgency_level == 'high') bg-orange-100 text-orange-800
                                                @elseif($productOfDay->urgency_level == 'medium') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ $productOfDay->days_left }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-gray-400">No schedule</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($productOfDay->isActive())
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.cms.product-of-day.edit', $productOfDay) }}" 
                                           class="text-blue-600 hover:text-blue-900" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.cms.product-of-day.toggle-status', $productOfDay) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-yellow-600 hover:text-yellow-900" title="Toggle Status">
                                                <i class="fas fa-power-off"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.cms.product-of-day.duplicate', $productOfDay) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900" title="Duplicate">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.cms.product-of-day.destroy', $productOfDay) }}" class="inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this product of the day?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <i class="fas fa-star text-6xl text-gray-300 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Products of the Day found</h3>
                                    <p class="text-gray-500 mb-4">Feature your best products to increase visibility and sales.</p>
                                    <a href="{{ route('admin.cms.product-of-day.create') }}" 
                                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                                        <i class="fas fa-plus mr-1"></i>
                                        Add First Product
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($productsOfDay->hasPages())
                <div class="mt-6">
                    {{ $productsOfDay->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
