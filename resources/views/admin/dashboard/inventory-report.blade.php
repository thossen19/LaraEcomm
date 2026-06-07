@extends('admin.layouts.app')

@section('title', 'Inventory Report')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Inventory Report</h1>
        <p class="mt-1 text-sm text-gray-600">View inventory levels and stock analysis</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-full p-3">
                    <i class="fas fa-box text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Products</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $products->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-100 rounded-full p-3">
                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Low Stock</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $lowStockProducts->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-red-100 rounded-full p-3">
                    <i class="fas fa-times-circle text-red-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Out of Stock</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $outOfStockProducts->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-full p-3">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">In Stock</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $products->where('quantity', '>', 10)->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Alerts -->
    @if($lowStockProducts->count() > 0)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex items-center mb-3">
                <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                <h3 class="text-lg font-medium text-yellow-800">Low Stock Alerts</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($lowStockProducts->take(6) as $product)
                    <div class="flex items-center justify-between bg-white p-3 rounded border border-yellow-200">
                        <div class="flex items-center space-x-3">
                            <div class="h-8 w-8 bg-gray-200 rounded flex items-center justify-center">
                                <i class="fas fa-box text-gray-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                <p class="text-xs text-gray-500">{{ $product->quantity }} left</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.inventory.adjust', $product) }}" 
                           class="text-blue-600 hover:text-blue-900 text-sm">Adjust</a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Out of Stock Products -->
    @if($outOfStockProducts->count() > 0)
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex items-center mb-3">
                <i class="fas fa-times-circle text-red-600 mr-2"></i>
                <h3 class="text-lg font-medium text-red-800">Out of Stock Products</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($outOfStockProducts->take(6) as $product)
                    <div class="flex items-center justify-between bg-white p-3 rounded border border-red-200">
                        <div class="flex items-center space-x-3">
                            <div class="h-8 w-8 bg-gray-200 rounded flex items-center justify-center">
                                <i class="fas fa-box text-gray-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                <p class="text-xs text-red-600">Out of stock</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.inventory.adjust', $product) }}" 
                           class="text-blue-600 hover:text-blue-900 text-sm">Restock</a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Products Table -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">All Products Inventory</h3>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($products as $product)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $product->sku ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $product->categories->pluck('name')->implode(', ') ?: 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ${{ number_format($product->price, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium 
                                        {{ $product->quantity == 0 ? 'text-red-600' : 
                                           ($product->quantity <= 10 ? 'text-yellow-600' : 'text-green-600') }}">
                                        {{ $product->quantity }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $product->quantity == 0 ? 'bg-red-100 text-red-800' : 
                                           ($product->quantity <= 10 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                        {{ $product->quantity == 0 ? 'Out of Stock' : 
                                           ($product->quantity <= 10 ? 'Low Stock' : 'In Stock') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.inventory.adjust', $product) }}" 
                                       class="text-blue-600 hover:text-blue-900">Adjust</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
