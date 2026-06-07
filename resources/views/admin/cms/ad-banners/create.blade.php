@extends('admin.layouts.app')

@section('title', 'Create Ad Banner')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-ad mr-2 text-blue-600"></i>
                    Create Ad Banner
                </h1>
                <a href="{{ route('admin.cms.ad-banners.index') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Back to Banners
                </a>
            </div>

            <form method="POST" action="{{ route('admin.cms.ad-banners.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Basic Information -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-info-circle mr-2 text-gray-600"></i>
                        Basic Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Banner Title *</label>
                            <input type="text" name="title" id="title" required
                                   class="form-input mt-1">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="link" class="block text-sm font-medium text-gray-700">Destination URL</label>
                            <input type="url" name="link" id="link" placeholder="https://example.com"
                                   class="form-input mt-1">
                            @error('link')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3"
                                  class="form-input mt-1" maxlength="500"></textarea>
                        <p class="mt-1 text-xs text-gray-500">Brief description of the banner (max 500 characters)</p>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Banner Image -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-image mr-2 text-gray-600"></i>
                        Banner Image
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700">Banner Image *</label>
                            <input type="file" name="image" id="image" accept="image/*" required
                                   class="form-input mt-1">
                            <p class="mt-1 text-xs text-gray-500">Allowed formats: JPEG, PNG, JPG, GIF, WebP (max 2MB)</p>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="alt_text" class="block text-sm font-medium text-gray-700">Alt Text</label>
                            <input type="text" name="alt_text" id="alt_text"
                                   class="form-input mt-1" maxlength="255">
                            <p class="mt-1 text-xs text-gray-500">Alternative text for accessibility</p>
                            @error('alt_text')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Placement Settings -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-map-marker-alt mr-2 text-gray-600"></i>
                        Placement Settings
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700">Position *</label>
                            <select name="position" id="position" required class="form-input mt-1">
                                <option value="">Select Position</option>
                                @foreach($positions as $key => $position)
                                    <option value="{{ $key }}">{{ $position }}</option>
                                @endforeach
                            </select>
                            @error('position')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="page" class="block text-sm font-medium text-gray-700">Page *</label>
                            <select name="page" id="page" required class="form-input mt-1">
                                <option value="">Select Page</option>
                                @foreach($pages as $key => $page)
                                    <option value="{{ $key }}">{{ $page }}</option>
                                @endforeach
                            </select>
                            @error('page')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="sort_order" class="block text-sm font-medium text-gray-700">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" min="0" value="0"
                               class="form-input mt-1">
                        <p class="mt-1 text-xs text-gray-500">Lower numbers appear first (0 = highest priority)</p>
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-6">
                        <label for="height" class="block text-sm font-medium text-gray-700">Banner Height (px)</label>
                        <input type="number" name="height" id="height" min="50" max="500" value="100"
                               class="form-input mt-1">
                        <p class="mt-1 text-xs text-gray-500">Banner height in pixels (default: 100px)</p>
                        @error('height')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Schedule Settings -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-calendar-alt mr-2 text-gray-600"></i>
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
                        <i class="fas fa-cog mr-2 text-gray-600"></i>
                        Additional Options
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="status" id="status" value="1" checked
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="status" class="ml-2 block text-sm text-gray-700">
                                Active (display this banner)
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="target_blank" id="target_blank" value="1"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="target_blank" class="ml-2 block text-sm text-gray-700">
                                Open link in new tab
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Banner will be displayed according to the selected position and page settings.
                    </div>
                    <div class="space-x-3">
                        <a href="{{ route('admin.cms.ad-banners.index') }}" class="btn-secondary">
                            <i class="fas fa-times mr-1"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save mr-1"></i>
                            Create Banner
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Set end date minimum to start date
document.getElementById('start_date').addEventListener('change', function() {
    document.getElementById('end_date').min = this.value;
});
</script>
@endsection
