@extends('admin.layouts.app')

@section('title', 'Shipping Settings')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Shipping Settings</h1>
        <p class="mt-1 text-sm text-gray-600">Configure shipping rates and delivery options</p>
    </div>

    <form method="POST" action="{{ route('admin.settings.shipping.update') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="_method" value="PUT">
                
        <!-- General Shipping Settings -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">General Shipping Settings</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Default Shipping Method</label>
                        <select name="default_shipping_method"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="flat_rate" {{ ($settings['default_shipping_method'] ?? 'flat_rate') == 'flat_rate' ? 'selected' : '' }}>Flat Rate</option>
                            <option value="weight_based" {{ ($settings['default_shipping_method'] ?? 'flat_rate') == 'weight_based' ? 'selected' : '' }}>Weight Based</option>
                            <option value="price_based" {{ ($settings['default_shipping_method'] ?? 'flat_rate') == 'price_based' ? 'selected' : '' }}>Price Based</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Free Shipping Threshold</label>
                        <input type="number" name="free_shipping_threshold" step="0.01" min="0"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               value="{{ old('free_shipping_threshold', $settings['free_shipping_threshold'] ?? 0) }}"
                               placeholder="0.00">
                        <p class="mt-1 text-sm text-gray-500">Orders above this amount get free shipping</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Flat Shipping Rate</label>
                        <input type="number" name="flat_shipping_rate" step="0.01" min="0"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               value="{{ old('flat_shipping_rate', $settings['flat_shipping_rate'] ?? 0) }}"
                               placeholder="0.00">
                        <p class="mt-1 text-sm text-gray-500">Default flat rate for all orders</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Processing Time (days)</label>
                        <input type="number" name="processing_time" min="1" max="30"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               value="{{ old('processing_time', $settings['processing_time'] ?? 1) }}"
                               placeholder="1">
                        <p class="mt-1 text-sm text-gray-500">Days needed to process orders before shipping</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Zones -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Shipping Zones</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">Domestic</h4>
                            <p class="text-sm text-gray-500">Within country</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <input type="number" name="domestic_rate" step="0.01" min="0"
                                   class="w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('domestic_rate', $settings['domestic_rate'] ?? 10) }}"
                                   placeholder="10.00">
                            <span class="text-sm text-gray-500">$</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">International</h4>
                            <p class="text-sm text-gray-500">Outside country</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <input type="number" name="international_rate" step="0.01" min="0"
                                   class="w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('international_rate', $settings['international_rate'] ?? 25) }}"
                                   placeholder="25.00">
                            <span class="text-sm text-gray-500">$</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">Express</h4>
                            <p class="text-sm text-gray-500">Express delivery (2-3 days)</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <input type="number" name="express_rate" step="0.01" min="0"
                                   class="w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('express_rate', $settings['express_rate'] ?? 35) }}"
                                   placeholder="35.00">
                            <span class="text-sm text-gray-500">$</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Weight-Based Shipping -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Weight-Based Shipping</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">0-1 kg</h4>
                            <p class="text-sm text-gray-500">Light items</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <input type="number" name="weight_0_1_rate" step="0.01" min="0"
                                   class="w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('weight_0_1_rate', $settings['weight_0_1_rate'] ?? 5) }}"
                                   placeholder="5.00">
                            <span class="text-sm text-gray-500">$</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">1-5 kg</h4>
                            <p class="text-sm text-gray-500">Medium items</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <input type="number" name="weight_1_5_rate" step="0.01" min="0"
                                   class="w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('weight_1_5_rate', $settings['weight_1_5_rate'] ?? 10) }}"
                                   placeholder="10.00">
                            <span class="text-sm text-gray-500">$</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">5-10 kg</h4>
                            <p class="text-sm text-gray-500">Heavy items</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <input type="number" name="weight_5_10_rate" step="0.01" min="0"
                                   class="w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('weight_5_10_rate', $settings['weight_5_10_rate'] ?? 20) }}"
                                   placeholder="20.00">
                            <span class="text-sm text-gray-500">$</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">10+ kg</h4>
                            <p class="text-sm text-gray-500">Very heavy items</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <input type="number" name="weight_10_plus_rate" step="0.01" min="0"
                                   class="w-24 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('weight_10_plus_rate', $settings['weight_10_plus_rate'] ?? 30) }}"
                                   placeholder="30.00">
                            <span class="text-sm text-gray-500">$</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delivery Options -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Delivery Options</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="enable_standard_shipping" id="enable_standard_shipping" value="1"
                               {{ ($settings['enable_standard_shipping'] ?? true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="enable_standard_shipping" class="ml-2 block text-sm text-gray-900">
                            Enable Standard Shipping (5-7 days)
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="enable_express_shipping" id="enable_express_shipping" value="1"
                               {{ ($settings['enable_express_shipping'] ?? true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="enable_express_shipping" class="ml-2 block text-sm text-gray-900">
                            Enable Express Shipping (2-3 days)
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="enable_pickup" id="enable_pickup" value="1"
                               {{ ($settings['enable_pickup'] ?? false) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="enable_pickup" class="ml-2 block text-sm text-gray-900">
                            Enable Store Pickup
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="enable_local_delivery" id="enable_local_delivery" value="1"
                               {{ ($settings['enable_local_delivery'] ?? false) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="enable_local_delivery" class="ml-2 block text-sm text-gray-900">
                            Enable Local Delivery (same day)
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-3">
            <button type="button" onclick="location.reload()" 
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md text-sm font-medium">
                Cancel
            </button>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                Save Shipping Settings
            </button>
        </div>
    </form>
</div>
@endsection
