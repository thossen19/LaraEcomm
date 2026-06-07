@extends('admin.layouts.app')

@section('title', 'General Settings')

@section('header', 'General Settings')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6 lg:p-8">
            <!-- Settings Navigation -->
            <div class="border-b border-gray-200 mb-8">
                <nav class="-mb-px flex space-x-8">
                    <a href="{{ route('admin.settings.general') }}" class="border-blue-500 text-blue-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        General
                    </a>
                    <a href="{{ route('admin.settings.payment') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        Payment
                    </a>
                    <a href="{{ route('admin.settings.email') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        Email
                    </a>
                    <a href="{{ route('admin.settings.shipping') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        Shipping
                    </a>
                    <a href="{{ route('admin.settings.ai') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        AI Chat
                    </a>
                    <a href="{{ route('admin.settings.sms') }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        SMS
                    </a>
                </nav>
            </div>

            <form action="{{ route('admin.settings.general.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')
                
                <!-- Store Information -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Store Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Store Name</label>
                            <input type="text" name="site_name"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('site_name', $settings['site_name'] ?? 'My E-Commerce Store') }}">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Store Email</label>
                            <input type="email" name="contact_email"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('contact_email', $settings['contact_email'] ?? 'admin@example.com') }}">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Store Phone</label>
                            <input type="tel" name="contact_phone"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('contact_phone', $settings['contact_phone']) }}">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Store Address</label>
                            <input type="text" name="contact_address"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('contact_address', $settings['contact_address']) }}">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Store Description</label>
                            <textarea name="site_description" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('site_description', $settings['site_description']) }}</textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Logo and Favicon -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Logo and Favicon</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Store Logo</label>
                            <div class="mt-1 flex items-center space-x-4">
                                @if($settings['site_logo'])
                                    <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Store Logo" class="h-16 w-16 rounded object-cover">
                                @else
                                    <div class="h-16 w-16 bg-gray-200 rounded flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                @endif
                                <div>
                                    <input type="file" name="site_logo" accept="image/*"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <p class="text-xs text-gray-500">Recommended: 200x60px</p>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Favicon</label>
                            <div class="mt-1 flex items-center space-x-4">
                                @if($settings['site_favicon'])
                                    <img src="{{ asset('storage/' . $settings['site_favicon']) }}" alt="Favicon" class="h-8 w-8 rounded">
                                @else
                                    <div class="h-8 w-8 bg-gray-200 rounded flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400 text-xs"></i>
                                    </div>
                                @endif
                                <div>
                                    <input type="file" name="site_favicon" accept="image/x-icon,image/png"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <p class="text-xs text-gray-500">32x32px ICO or PNG</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Currency and Locale -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Currency and Locale</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Default Currency</label>
                            <select name="currency"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="USD" {{ ($settings['currency'] ?? 'USD') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                <option value="EUR" {{ ($settings['currency'] ?? 'USD') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                <option value="GBP" {{ ($settings['currency'] ?? 'USD') == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                <option value="CAD" {{ ($settings['currency'] ?? 'USD') == 'CAD' ? 'selected' : '' }}>CAD - Canadian Dollar</option>
                                <option value="AUD" {{ ($settings['currency'] ?? 'USD') == 'AUD' ? 'selected' : '' }}>AUD - Australian Dollar</option>
                                <option value="BDT" {{ ($settings['currency'] ?? 'USD') == 'BDT' ? 'selected' : '' }}>BDT - Bangladeshi Taka</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Timezone</label>
                            <select name="timezone"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="UTC" {{ ($settings['timezone'] ?? 'UTC') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                <option value="America/New_York" {{ ($settings['timezone'] ?? 'UTC') == 'America/New_York' ? 'selected' : '' }}>Eastern Time</option>
                                <option value="America/Chicago" {{ ($settings['timezone'] ?? 'UTC') == 'America/Chicago' ? 'selected' : '' }}>Central Time</option>
                                <option value="America/Denver" {{ ($settings['timezone'] ?? 'UTC') == 'America/Denver' ? 'selected' : '' }}>Mountain Time</option>
                                <option value="America/Los_Angeles" {{ ($settings['timezone'] ?? 'UTC') == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date Format</label>
                            <select name="date_format"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="Y-m-d" {{ ($settings['date_format'] ?? 'Y-m-d') == 'Y-m-d' ? 'selected' : '' }}>2024-01-15</option>
                                <option value="m/d/Y" {{ ($settings['date_format'] ?? 'Y-m-d') == 'm/d/Y' ? 'selected' : '' }}>01/15/2024</option>
                                <option value="d/m/Y" {{ ($settings['date_format'] ?? 'Y-m-d') == 'd/m/Y' ? 'selected' : '' }}>15/01/2024</option>
                                <option value="M j, Y" {{ ($settings['date_format'] ?? 'Y-m-d') == 'M j, Y' ? 'selected' : '' }}>Jan 15, 2024</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Language</label>
                            <select name="language"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="en" {{ ($settings['language'] ?? 'en') == 'en' ? 'selected' : '' }}>English</option>
                                <option value="es" {{ ($settings['language'] ?? 'en') == 'es' ? 'selected' : '' }}>Spanish</option>
                                <option value="fr" {{ ($settings['language'] ?? 'en') == 'fr' ? 'selected' : '' }}>French</option>
                                <option value="de" {{ ($settings['language'] ?? 'en') == 'de' ? 'selected' : '' }}>German</option>
                            </select>
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
                                   value="{{ old('meta_title', $settings['site_description'] ?? '') }}"
                                   placeholder="Default meta title for homepage">
                            <p class="mt-1 text-sm text-gray-500">Maximum 60 characters</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Meta Description</label>
                            <textarea name="meta_description" rows="3" maxlength="160"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('meta_description', $settings['site_description'] ?? '') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Maximum 160 characters</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Meta Keywords</label>
                            <input type="text" name="site_keywords" maxlength="255"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('site_keywords', $settings['site_keywords'] ?? '') }}"
                                   placeholder="e-commerce, online store, shopping">
                            <p class="mt-1 text-sm text-gray-500">Comma-separated keywords</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Google Analytics ID</label>
                            <input type="text" name="google_analytics_id"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('google_analytics_id', $settings['google_analytics_id'] ?? '') }}"
                                   placeholder="UA-XXXXXXXXX-X">
                        </div>
                    </div>
                </div>
                
                <!-- Social Media -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Social Media Links</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Facebook</label>
                            <input type="url" name="facebook_url"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}"
                                   placeholder="https://facebook.com/yourstore">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Twitter</label>
                            <input type="url" name="twitter_url"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('twitter_url', $settings['twitter_url'] ?? '') }}"
                                   placeholder="https://twitter.com/yourstore">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Instagram</label>
                            <input type="url" name="instagram_url"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}"
                                   placeholder="https://instagram.com/yourstore">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">LinkedIn</label>
                            <input type="url" name="linkedin_url"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   value="{{ old('linkedin_url', $settings['linkedin_url'] ?? '') }}"
                                   placeholder="https://linkedin.com/company/yourstore">
                        </div>
                    </div>
                </div>
                
                <!-- Maintenance Mode -->
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Maintenance Mode</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="maintenance_mode" id="maintenance_mode" value="1"
                                   {{ ($settings['maintenance_mode'] ?? false) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="maintenance_mode" class="ml-2 block text-sm text-gray-900">
                                Enable Maintenance Mode
                            </label>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Maintenance Message</label>
                            <textarea name="maintenance_message" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('maintenance_message', $settings['maintenance_message'] ?? 'We are currently performing maintenance. Please check back soon.') }}</textarea>
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
                        <i class="fas fa-save mr-2"></i>
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
