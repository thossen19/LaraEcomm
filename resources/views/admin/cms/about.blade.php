@extends('admin.layouts.app')

@section('title', 'Manage About Page')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                    Manage About Page
                </h1>
                <a href="{{ route('admin.dashboard.index') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Back to Dashboard
                </a>
            </div>
            
            <form method="POST" action="{{ route('admin.cms.about.update') }}" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-edit mr-2 text-gray-600"></i>
                        Basic Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="about_title" class="block text-sm font-medium text-gray-700">Page Title *</label>
                            <input type="text" name="about_title" id="about_title" value="{{ $aboutTitle }}" required
                                   class="form-input mt-1">
                            @error('about_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="about_meta_title" class="block text-sm font-medium text-gray-700">Meta Title</label>
                            <input type="text" name="about_meta_title" id="about_meta_title" value="{{ $aboutMetaTitle }}"
                                   class="form-input mt-1" maxlength="255">
                            <p class="mt-1 text-xs text-gray-500">SEO meta title (max 255 characters)</p>
                            @error('about_meta_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-file-alt mr-2 text-gray-600"></i>
                        Page Content
                    </h3>
                    
                    <div>
                        <label for="about_content" class="block text-sm font-medium text-gray-700">About Content *</label>
                        <textarea name="about_content" id="about_content" rows="15" required
                                  class="form-input mt-1">{{ $aboutContent }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Main content for the about page. You can use HTML tags.</p>
                        @error('about_content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- SEO Settings -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-search mr-2 text-gray-600"></i>
                        SEO Settings
                    </h3>
                    
                    <div>
                        <label for="about_meta_description" class="block text-sm font-medium text-gray-700">Meta Description</label>
                        <textarea name="about_meta_description" id="about_meta_description" rows="3"
                                  class="form-input mt-1" maxlength="500">{{ $aboutMetaDescription }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">SEO meta description (max 500 characters)</p>
                        @error('about_meta_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Preview Section -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-blue-900 mb-4">
                        <i class="fas fa-eye mr-2"></i>
                        Preview
                    </h3>
                    <div class="bg-white rounded-lg p-4 border border-blue-100">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $aboutTitle ?: 'About Us' }}</h2>
                        <div class="prose max-w-none">
                            {!! $aboutContent ?: '<p class="text-gray-500">No content added yet.</p>' !!}
                        </div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Changes will be reflected immediately on the public about page.
                    </div>
                    <div class="space-x-3">
                        <a href="{{ route('admin.dashboard.index') }}" class="btn-secondary">
                            <i class="fas fa-times mr-1"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save mr-1"></i>
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
