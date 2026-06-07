@extends('admin.layouts.app')

@section('title', 'Manage Contact Page')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-envelope mr-2 text-blue-600"></i>
                    Manage Contact Page
                </h1>
                <a href="{{ route('admin.dashboard.index') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Back to Dashboard
                </a>
            </div>
            
            <form method="POST" action="{{ route('admin.cms.contact.update') }}" class="space-y-6">
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
                            <label for="contact_title" class="block text-sm font-medium text-gray-700">Page Title *</label>
                            <input type="text" name="contact_title" id="contact_title" value="{{ $contactTitle }}" required
                                   class="form-input mt-1">
                            @error('contact_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="contact_meta_title" class="block text-sm font-medium text-gray-700">Meta Title</label>
                            <input type="text" name="contact_meta_title" id="contact_meta_title" value="{{ $contactMetaTitle }}"
                                   class="form-input mt-1" maxlength="255">
                            <p class="mt-1 text-xs text-gray-500">SEO meta title (max 255 characters)</p>
                            @error('contact_meta_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Contact Information -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-address-card mr-2 text-gray-600"></i>
                        Contact Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700">Email Address *</label>
                            <input type="email" name="contact_email" id="contact_email" value="{{ $contactEmail }}" required
                                   class="form-input mt-1">
                            @error('contact_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700">Phone Number *</label>
                            <input type="tel" name="contact_phone" id="contact_phone" value="{{ $contactPhone }}" required
                                   class="form-input mt-1" maxlength="20">
                            @error('contact_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <label for="contact_address" class="block text-sm font-medium text-gray-700">Address *</label>
                        <textarea name="contact_address" id="contact_address" rows="3" required
                                  class="form-input mt-1" maxlength="500">{{ $contactAddress }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Full address including city, state, country (max 500 characters)</p>
                        @error('contact_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Content -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-file-alt mr-2 text-gray-600"></i>
                        Page Content
                    </h3>
                    
                    <div>
                        <label for="contact_content" class="block text-sm font-medium text-gray-700">Contact Content *</label>
                        <textarea name="contact_content" id="contact_content" rows="8" required
                                  class="form-input mt-1">{{ $contactContent }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Main content for the contact page. You can use HTML tags.</p>
                        @error('contact_content')
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
                        <label for="contact_meta_description" class="block text-sm font-medium text-gray-700">Meta Description</label>
                        <textarea name="contact_meta_description" id="contact_meta_description" rows="3"
                                  class="form-input mt-1" maxlength="500">{{ $contactMetaDescription }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">SEO meta description (max 500 characters)</p>
                        @error('contact_meta_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Preview Section -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-green-900 mb-4">
                        <i class="fas fa-eye mr-2"></i>
                        Preview
                    </h3>
                    <div class="bg-white rounded-lg p-4 border border-green-100">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $contactTitle ?: 'Contact Us' }}</h2>
                        <div class="prose max-w-none mb-6">
                            {!! $contactContent ?: '<p class="text-gray-500">No content added yet.</p>' !!}
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-envelope text-blue-600 text-xl"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Email</p>
                                    <p class="font-medium">{{ $contactEmail ?: 'contact@example.com' }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-phone text-green-600 text-xl"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Phone</p>
                                    <p class="font-medium">{{ $contactPhone ?: '+1234567890' }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-map-marker-alt text-red-600 text-xl"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Address</p>
                                    <p class="font-medium">{{ $contactAddress ?: '123 Main St, City, Country' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Changes will be reflected immediately on the public contact page.
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
