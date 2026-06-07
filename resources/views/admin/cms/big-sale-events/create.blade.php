@extends('admin.layouts.app')

@section('title', 'Create Big Sale Event')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-fire mr-2 text-red-600"></i>
                    Create Big Sale Event
                </h1>
                <a href="{{ route('admin.cms.big-sale-events.index') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Back to Events
                </a>
            </div>

            <form method="POST" action="{{ route('admin.cms.big-sale-events.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Basic Information -->
                <div class="bg-red-50 rounded-lg p-6 border border-red-200">
                    <h3 class="text-lg font-medium text-red-900 mb-4">
                        <i class="fas fa-info-circle mr-2"></i>
                        Event Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Event Title *</label>
                            <input type="text" name="title" id="title" required
                                   class="form-input mt-1" placeholder="Mega Sale Festival">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="subtitle" class="block text-sm font-medium text-gray-700">Subtitle</label>
                            <input type="text" name="subtitle" id="subtitle"
                                   class="form-input mt-1" placeholder="Limited Time Offer">
                            @error('subtitle')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description *</label>
                        <textarea name="description" id="description" rows="4" required
                                  class="form-input mt-1" placeholder="Describe your amazing sale event..."></textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                    </div>
                </div>

                <!-- Visual Design -->
                <div class="bg-purple-50 rounded-lg p-6 border border-purple-200">
                    <h3 class="text-lg font-medium text-purple-900 mb-4">
                        <i class="fas fa-palette mr-2"></i>
                        Visual Design
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="banner_image" class="block text-sm font-medium text-gray-700">Banner Image *</label>
                            <input type="file" name="banner_image" id="banner_image" accept="image/*" required
                                   class="form-input mt-1">
                            <p class="mt-1 text-xs text-gray-500">Recommended: 1200x400px, Max 2MB</p>
                            @error('banner_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="background_color" class="block text-sm font-medium text-gray-700">Background Color</label>
                                <div class="flex items-center space-x-2">
                                    <input type="color" name="background_color" id="background_color" value="#FF6B6B"
                                           class="h-10 w-20 border border-gray-300 rounded">
                                    <input type="text" name="background_color_text" value="#FF6B6B" readonly
                                           class="form-input flex-1 text-sm">
                                </div>
                            </div>
                            
                            <div>
                                <label for="text_color" class="block text-sm font-medium text-gray-700">Text Color</label>
                                <div class="flex items-center space-x-2">
                                    <input type="color" name="text_color" id="text_color" value="#FFFFFF"
                                           class="h-10 w-20 border border-gray-300 rounded">
                                    <input type="text" name="text_color_text" value="#FFFFFF" readonly
                                           class="form-input flex-1 text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pricing & Discount -->
                <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                    <h3 class="text-lg font-medium text-green-900 mb-4">
                        <i class="fas fa-tags mr-2"></i>
                        Pricing & Discount
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="discount_percentage" class="block text-sm font-medium text-gray-700">Discount %</label>
                            <input type="number" name="discount_percentage" id="discount_percentage" 
                                   min="0" max="100" step="0.01" class="form-input mt-1" placeholder="50">
                            @error('discount_percentage')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="original_price" class="block text-sm font-medium text-gray-700">Original Price</label>
                            <input type="number" name="original_price" id="original_price" 
                                   min="0" step="0.01" class="form-input mt-1" placeholder="99.99">
                            @error('original_price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="sale_price" class="block text-sm font-medium text-gray-700">Sale Price</label>
                            <input type="number" name="sale_price" id="sale_price" 
                                   min="0" step="0.01" class="form-input mt-1" placeholder="49.99">
                            @error('sale_price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                    <h3 class="text-lg font-medium text-blue-900 mb-4">
                        <i class="fas fa-mouse-pointer mr-2"></i>
                        Call to Action
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="button_text" class="block text-sm font-medium text-gray-700">Button Text</label>
                            <input type="text" name="button_text" id="button_text" value="Shop Now"
                                   class="form-input mt-1">
                            @error('button_text')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="button_link" class="block text-sm font-medium text-gray-700">Button Link</label>
                            <input type="url" name="button_link" id="button_link" 
                                   class="form-input mt-1" placeholder="/deals">
                            @error('button_link')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Schedule Settings -->
                <div class="bg-yellow-50 rounded-lg p-6 border border-yellow-200">
                    <h3 class="text-lg font-medium text-yellow-900 mb-4">
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
                    
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="status" id="status" value="1" checked
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="status" class="ml-2 block text-sm text-gray-700">
                                Active (display this event)
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="featured" id="featured" value="1"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="featured" class="ml-2 block text-sm text-gray-700">
                                Featured (highlight this event)
                            </label>
                        </div>
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="bg-indigo-50 rounded-lg p-6 border border-indigo-200">
                    <h3 class="text-lg font-medium text-indigo-900 mb-4">
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
                        This event will be displayed on the homepage according to your settings.
                    </div>
                    <div class="space-x-3">
                        <a href="{{ route('admin.cms.big-sale-events.index') }}" class="btn-secondary">
                            <i class="fas fa-times mr-1"></i>
                            Cancel
                        </a>
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-fire mr-1"></i>
                            Create Event
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Sync color inputs
document.getElementById('background_color').addEventListener('input', function(e) {
    document.getElementById('background_color_text').value = e.target.value;
});

document.getElementById('text_color').addEventListener('input', function(e) {
    document.getElementById('text_color_text').value = e.target.value;
});

// Set end date minimum to start date
document.getElementById('start_date').addEventListener('change', function() {
    document.getElementById('end_date').min = this.value;
});
</script>
@endsection
