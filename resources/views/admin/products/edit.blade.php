@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('header', 'Edit Product')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6 lg:p-8">
            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Product Name *</label>
                            <input type="text" name="name" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('name', $product->name) }}">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Description *</label>
                            <textarea name="description" rows="4" required
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">SKU *</label>
                            <input type="text" name="sku" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('sku', $product->sku) }}">
                            @error('sku')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Barcode</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <input type="text" name="barcode" id="barcode"
                                       class="block w-full rounded-none rounded-l-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('barcode', $product->barcode) }}">
                                <button type="button" onclick="generateEAN6()"
                                        class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 rounded-r-md bg-gray-50 text-gray-700 text-sm font-medium hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 whitespace-nowrap">
                                    <i class="fas fa-qrcode mr-1.5"></i>
                                    Generate EAN-6 Code
                                </button>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Category *</label>
                            <select name="category_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $selectedCategoryId) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Brand</label>
                            <select name="brand_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id', $selectedBrandId) == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Pricing -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Pricing</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Regular Price *</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" name="price" step="0.01" min="0" required
                                       class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('price', $product->price) }}">
                            </div>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Sale Price</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" name="sale_price" step="0.01" min="0"
                                       class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('sale_price', $product->sale_price) }}">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cost Price</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" name="cost_price" step="0.01" min="0"
                                       class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ old('cost_price', $product->cost_price) }}">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Inventory -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Inventory</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Quantity *</label>
                            <input type="number" name="quantity" min="0" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('quantity', $product->quantity) }}">
                            @error('quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Low Stock Threshold</label>
                            <input type="number" name="low_stock_threshold" min="0"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Weight (kg)</label>
                            <input type="number" name="weight" step="0.01" min="0"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('weight', $product->weight) }}">
                        </div>
                    </div>
                </div>
                
                <!-- Product Images -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Product Images</h3>
                    <div class="space-y-4">
                        <!-- Current Main Image -->
                        @if($product->image)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Main Image</label>
                                <div class="flex items-center space-x-4">
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                         class="h-20 w-20 rounded object-cover shadow-md"
                                         onerror="this.src='https://via.placeholder.com/150x150?text=Image+Not+Found'; this.onerror=null;">
                                    <div>
                                        <p class="text-sm text-gray-600">Current main image</p>
                                        <button type="button" onclick="removeMainImage()" 
                                                class="mt-1 text-sm text-red-600 hover:text-red-800">
                                            Remove Image
                                        </button>
                                    </div>
                                </div>
                                <input type="hidden" name="remove_main_image" id="remove_main_image" value="0">
                            </div>
                        @endif
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Change Main Image</label>
                            <input type="file" name="image" accept="image/*"
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Additional Images -->
                        @if($product->images)
                            @php
                                $additionalImages = json_decode($product->images, true) ?: [];
                            @endphp
                            @if(!empty($additionalImages))
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Additional Images</label>
                                    <div class="grid grid-cols-4 gap-4">
                                        @foreach($additionalImages as $index => $image)
                                            <div class="relative">
                                                <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }} - Image {{ $index + 1 }}" 
                                                     class="h-20 w-20 rounded object-cover shadow-md"
                                                     onerror="this.src='https://via.placeholder.com/150x150?text=Image+Not+Found'; this.onerror=null;">
                                                <button type="button" onclick="removeAdditionalImage({{ $index }})" 
                                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">
                                                    <i class="fas fa-times text-xs"></i>
                                                </button>
                                                <input type="hidden" name="remove_images[]" value="{{ $index }}" id="remove_image_{{ $index }}">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Add More Images</label>
                            <input type="file" name="images[]" accept="image/*" multiple
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="mt-1 text-sm text-gray-500">You can select multiple images</p>
                        </div>
                    </div>
                </div>
                
                <!-- SEO Settings -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Settings</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Meta Title</label>
                            <input type="text" name="meta_title" maxlength="60"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('meta_title', $product->meta_title) }}">
                            <p class="mt-1 text-sm text-gray-500">Maximum 60 characters</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Meta Description</label>
                            <textarea name="meta_description" rows="3" maxlength="160"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('meta_description', $product->meta_description) }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Maximum 160 characters</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">URL Slug</label>
                            <input type="text" name="slug"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('slug', $product->slug) }}">
                            <p class="mt-1 text-sm text-gray-500">Leave empty to auto-generate from product name</p>
                        </div>
                    </div>
                </div>
                
                <!-- Product Status -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Product Status</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" 
                                   {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                Active (Product will be visible on store)
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1"
                                   {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                Featured (Show on homepage)
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="is_best_fashion" id="is_best_fashion" value="1"
                                   {{ old('is_best_fashion', $product->is_best_fashion) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_best_fashion" class="ml-2 block text-sm text-gray-900">
                                Best of Fashion (Show on homepage)
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="track_quantity" id="track_quantity" value="1"
                                   {{ old('track_quantity', $product->track_quantity) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="track_quantity" class="ml-2 block text-sm text-gray-900">
                                Track Stock (Enable inventory tracking)
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.products.index') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md text-sm font-medium">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-save mr-2"></i>
                        Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function removeMainImage() {
        document.getElementById('remove_main_image').value = '1';
        const imageContainer = document.querySelector('img[alt="{{ $product->name }}"]').closest('div');
        imageContainer.style.display = 'none';
    }
    
    function removeAdditionalImage(imageId) {
        document.getElementById('remove_image_' + imageId).value = imageId;
        const imageContainer = document.getElementById('remove_image_' + imageId).closest('.relative');
        imageContainer.style.display = 'none';
    }

    function generateEAN6() {
        var digits = '';
        for (var i = 0; i < 5; i++) {
            digits += Math.floor(Math.random() * 10);
        }
        var sum = 0;
        for (var i = 0; i < 5; i++) {
            sum += parseInt(digits[i]) * (i % 2 === 0 ? 3 : 1);
        }
        var checksum = (10 - (sum % 10)) % 10;
        digits += checksum;
        document.getElementById('barcode').value = '20' + digits;
    }
</script>
@endpush
@endsection
