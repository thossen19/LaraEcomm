@extends('admin.layouts.app')

@section('title', 'Add Product of the Day')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-star mr-2 text-yellow-500"></i>
                    Add Product of the Day
                </h1>
                <a href="{{ route('admin.cms.product-of-day.index') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Back to Products
                </a>
            </div>

            <form method="POST" action="{{ route('admin.cms.product-of-day.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Product Selection -->
                <div class="bg-yellow-50 rounded-lg p-6 border border-yellow-200">
                    <h3 class="text-lg font-medium text-yellow-900 mb-4">
                        <i class="fas fa-box mr-2"></i>
                        Product Selection
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="product_id" class="block text-sm font-medium text-gray-700">Link to Existing Product (Optional)</label>
                            <select name="product_id" id="product_id" class="form-input mt-1">
                                <option value="">Select a product (optional)</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Leave empty to create a standalone product feature</p>
                            @error('product_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Display Title *</label>
                            <input type="text" name="title" id="title" required
                                   class="form-input mt-1" placeholder="Amazing Product of the Day">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description *</label>
                        <textarea name="description" id="description" rows="4" required
                                  class="form-input mt-1" placeholder="Describe why this product is special..."></textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                    </div>
                </div>

                <!-- Badge Design -->
                <div class="bg-purple-50 rounded-lg p-6 border border-purple-200">
                    <h3 class="text-lg font-medium text-purple-900 mb-4">
                        <i class="fas fa-tag mr-2"></i>
                        Badge Design
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="badge_text" class="block text-sm font-medium text-gray-700">Badge Text</label>
                            <input type="text" name="badge_text" id="badge_text" value="Product of the Day"
                                   class="form-input mt-1">
                            @error('badge_text')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="badge_color" class="block text-sm font-medium text-gray-700">Badge Color</label>
                            <div class="flex items-center space-x-2">
                                <input type="color" name="badge_color" id="badge_color" value="#FF6B6B"
                                       class="h-10 w-20 border border-gray-300 rounded">
                                <input type="text" name="badge_color_text" value="#FF6B6B" readonly
                                       class="form-input flex-1 text-sm">
                            </div>
                            @error('badge_color')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Images -->
                <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                    <h3 class="text-lg font-medium text-blue-900 mb-4">
                        <i class="fas fa-images mr-2"></i>
                        Product Images
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="featured_image" class="block text-sm font-medium text-gray-700">Featured Image</label>
                            <input type="file" name="featured_image" id="featured_image" accept="image/*"
                                   class="form-input mt-1">
                            <p class="mt-1 text-xs text-gray-500">Product showcase image (recommended: 400x400px)</p>
                            @error('featured_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="banner_image" class="block text-sm font-medium text-gray-700">Banner Image</label>
                            <input type="file" name="banner_image" id="banner_image" accept="image/*"
                                   class="form-input mt-1">
                            <p class="mt-1 text-xs text-gray-500">Homepage banner image (recommended: 1200x300px)</p>
                            @error('banner_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                    <h3 class="text-lg font-medium text-green-900 mb-4">
                        <i class="fas fa-dollar-sign mr-2"></i>
                        Pricing Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="discount_percentage" class="block text-sm font-medium text-gray-700">Discount %</label>
                            <input type="number" name="discount_percentage" id="discount_percentage" 
                                   min="0" max="100" step="0.01" class="form-input mt-1" placeholder="25">
                            @error('discount_percentage')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="original_price" class="block text-sm font-medium text-gray-700">Original Price</label>
                            <input type="number" name="original_price" id="original_price" 
                                   min="0" step="0.01" class="form-input mt-1" placeholder="199.99">
                            @error('original_price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="sale_price" class="block text-sm font-medium text-gray-700">Sale Price</label>
                            <input type="number" name="sale_price" id="sale_price" 
                                   min="0" step="0.01" class="form-input mt-1" placeholder="149.99">
                            @error('sale_price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="bg-indigo-50 rounded-lg p-6 border border-indigo-200">
                    <h3 class="text-lg font-medium text-indigo-900 mb-4">
                        <i class="fas fa-mouse-pointer mr-2"></i>
                        Call to Action
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="button_text" class="block text-sm font-medium text-gray-700">Button Text</label>
                            <input type="text" name="button_text" id="button_text" value="View Product"
                                   class="form-input mt-1">
                            @error('button_text')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="button_link" class="block text-sm font-medium text-gray-700">Button Link</label>
                            <input type="url" name="button_link" id="button_link" 
                                   class="form-input mt-1" placeholder="/products/amazing-product">
                            @error('button_link')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Schedule Settings -->
                <div class="bg-orange-50 rounded-lg p-6 border border-orange-200">
                    <h3 class="text-lg font-medium text-orange-900 mb-4">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Schedule Settings
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                            <input type="date" name="start_date" id="start_date"
                                   class="form-input mt-1" min="{{ now()->format('Y-m-d') }}">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                            <input type="date" name="end_date" id="end_date"
                                   class="form-input mt-1">
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Leave empty for no date restrictions</p>
                </div>

                <!-- Additional Options -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-cog mr-2"></i>
                        Additional Options
                    </h3>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="status" id="status" value="1" checked
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="status" class="ml-2 block text-sm text-gray-700">
                            Active (display this product of the day)
                        </label>
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="bg-pink-50 rounded-lg p-6 border border-pink-200">
                    <h3 class="text-lg font-medium text-pink-900 mb-4">
                        <i class="fas fa-search mr-2"></i>
                        SEO Settings
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700">Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title" maxlength="255"
                                   class="form-input mt-1">
                            @error('meta_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" rows="2" maxlength="500"
                                      class="form-input mt-1"></textarea>
                            @error('meta_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        This product will be featured on the homepage according to your settings.
                    </div>
                    <div class="space-x-3">
                        <a href="{{ route('admin.cms.product-of-day.index') }}" class="btn-secondary">
                            <i class="fas fa-times mr-1"></i>
                            Cancel
                        </a>
                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-star mr-1"></i>
                            Add Product
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Sync color input
document.getElementById('badge_color').addEventListener('input', function(e) {
    document.getElementById('badge_color_text').value = e.target.value;
});

// Set end date minimum to start date
document.getElementById('start_date').addEventListener('change', function() {
    document.getElementById('end_date').min = this.value;
});
</script>
@endsection
