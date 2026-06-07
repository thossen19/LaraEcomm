@extends('admin.layouts.app')

@section('title', 'Adjust Inventory')

@section('header', 'Adjust Inventory')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6 lg:p-8">
            <!-- Product Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                             class="h-16 w-16 rounded object-cover">
                    @else
                        <div class="h-16 w-16 bg-gray-200 rounded flex items-center justify-center">
                            <i class="fas fa-box text-gray-400 text-2xl"></i>
                        </div>
                    @endif
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h2>
                        <p class="text-sm text-gray-500">SKU: {{ $product->sku }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.inventory.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Inventory
                </a>
            </div>

            <!-- Current Stock Status -->
            <div class="bg-gray-50 p-6 rounded-lg mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Current Stock Status</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Current Stock</dt>
                        <dd class="mt-1 text-2xl font-bold text-gray-900">{{ $product->quantity }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Low Stock Threshold</dt>
                        <dd class="mt-1 text-2xl font-bold text-gray-900">{{ $product->low_stock_threshold ?? 10 }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Stock Status</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ 
                                $product->quantity == 0 ? 'bg-red-100 text-red-800' : 
                                ($product->quantity <= ($product->low_stock_threshold ?? 10) ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') 
                            }}">
                                {{ 
                                    $product->quantity == 0 ? 'Out of Stock' : 
                                    ($product->quantity <= ($product->low_stock_threshold ?? 10) ? 'Low Stock' : 'In Stock') 
                                }}
                            </span>
                        </dd>
                    </div>
                </div>
            </div>

            <!-- Stock Adjustment Form -->
            <div class="bg-gray-50 p-6 rounded-lg mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Stock Adjustment</h3>
                <form action="{{ url('admin/inventory/adjust/' . $product->slug) }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Adjustment Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Adjustment Type</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <label class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 {{ old('adjustment_type') == 'add' ? 'border-blue-500 bg-blue-50' : 'border-gray-300' }}">
                                <input type="radio" name="adjustment_type" value="add" {{ old('adjustment_type') == 'add' ? 'checked' : '' }} class="sr-only">
                                <div class="flex items-center">
                                    <i class="fas fa-plus-circle text-green-600 mr-3"></i>
                                    <div>
                                        <p class="font-medium text-gray-900">Add Stock</p>
                                        <p class="text-sm text-gray-500">Increase inventory</p>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 {{ old('adjustment_type') == 'subtract' ? 'border-blue-500 bg-blue-50' : 'border-gray-300' }}">
                                <input type="radio" name="adjustment_type" value="subtract" {{ old('adjustment_type') == 'subtract' ? 'checked' : '' }} class="sr-only">
                                <div class="flex items-center">
                                    <i class="fas fa-minus-circle text-red-600 mr-3"></i>
                                    <div>
                                        <p class="font-medium text-gray-900">Remove Stock</p>
                                        <p class="text-sm text-gray-500">Decrease inventory</p>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 {{ old('adjustment_type') == 'set' ? 'border-blue-500 bg-blue-50' : 'border-gray-300' }}">
                                <input type="radio" name="adjustment_type" value="set" {{ old('adjustment_type') == 'set' ? 'checked' : '' }} class="sr-only">
                                <div class="flex items-center">
                                    <i class="fas fa-edit text-blue-600 mr-3"></i>
                                    <div>
                                        <p class="font-medium text-gray-900">Set Stock</p>
                                        <p class="text-sm text-gray-500">Set exact quantity</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                        @error('adjustment_type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Quantity -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Quantity</label>
                        <input type="number" name="quantity" required min="0" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               value="{{ old('quantity') }}">
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Reason -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Reason for Adjustment</label>
                        <select name="reason" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select a reason</option>
                            <option value="purchase" {{ old('reason') == 'purchase' ? 'selected' : '' }}>New Purchase/Stock Received</option>
                            <option value="return" {{ old('reason') == 'return' ? 'selected' : '' }}>Customer Return</option>
                            <option value="damage" {{ old('reason') == 'damage' ? 'selected' : '' }}>Damage/Loss</option>
                            <option value="correction" {{ old('reason') == 'correction' ? 'selected' : '' }}>Inventory Correction</option>
                            <option value="transfer" {{ old('reason') == 'transfer' ? 'selected' : '' }}>Stock Transfer</option>
                            <option value="other" {{ old('reason') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('reason')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                        <textarea name="notes" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes') }}</textarea>
                    </div>
                    
                    <!-- Preview -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-blue-900 mb-2">Preview</h4>
                        <div class="text-sm text-blue-800">
                            <p>Current Stock: <span class="font-bold">{{ $product->quantity }}</span></p>
                            <p>New Stock: <span class="font-bold" id="previewNewStock">{{ $product->quantity }}</span></p>
                            <p>Change: <span class="font-bold" id="previewChange">0</span></p>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.inventory.index') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md text-sm font-medium">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-save mr-2"></i>
                            Update Stock
                        </button>
                    </div>
                </form>
            </div>

            <!-- Recent Stock History -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Stock History</h3>
                @if($stockHistory && $stockHistory->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($stockHistory as $history)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $history->created_at->format('M j, Y g:i A') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                                                $history->adjustment_type == 'add' ? 'bg-green-100 text-green-800' : 
                                                ($history->adjustment_type == 'subtract' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') 
                                            }}">
                                                {{ ucfirst($history->adjustment_type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $history->quantity > 0 ? '+' : '' }}{{ $history->quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ ucfirst($history->reason) }}
                                            @if($history->notes)
                                                <p class="text-xs text-gray-500">{{ $history->notes }}</p>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $history->user->name }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-sm text-gray-500">No stock history available</p>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updatePreview() {
        const adjustmentType = document.querySelector('input[name="adjustment_type"]:checked')?.value || 'add';
        const quantity = parseInt(document.querySelector('input[name="quantity"]').value) || 0;
        const currentStock = {{ $product->quantity }};
        
        let newStock = currentStock;
        let change = 0;
        
        switch(adjustmentType) {
            case 'add':
                newStock = currentStock + quantity;
                change = quantity;
                break;
            case 'subtract':
                newStock = currentStock - quantity;
                change = -quantity;
                break;
            case 'set':
                newStock = quantity;
                change = quantity - currentStock;
                break;
        }
        
        document.getElementById('previewNewStock').textContent = newStock;
        const changeElement = document.getElementById('previewChange');
        changeElement.textContent = (change > 0 ? '+' : '') + change;
        changeElement.className = change > 0 ? 'font-bold text-green-600' : (change < 0 ? 'font-bold text-red-600' : 'font-bold');
    }
    
    // Add event listeners
    document.querySelectorAll('input[name="adjustment_type"]').forEach(radio => {
        radio.addEventListener('change', updatePreview);
    });
    
    document.querySelector('input[name="quantity"]').addEventListener('input', updatePreview);
    
    // Initialize preview
    updatePreview();
</script>
@endpush
@endsection
