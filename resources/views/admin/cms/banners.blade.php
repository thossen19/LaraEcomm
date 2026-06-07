@extends('admin.layouts.app')

@section('title', 'Banners Management')

@section('header', 'Homepage Banners Management')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6 lg:p-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Homepage Banners</h2>
            <a href="{{ route('admin.cms.banners.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-plus mr-2"></i>
                Add Banner
            </a>
        </div>

        <!-- Banner Positions Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-blue-50 p-6 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Hero Section</h3>
                <p class="text-sm text-gray-600 mb-4">Main homepage banners</p>
                <div class="text-2xl font-bold text-blue-600">{{ $heroBanners ?? 0 }}</div>
                <p class="text-xs text-gray-500">Active banners</p>
            </div>
            
            <div class="bg-green-50 p-6 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Category Banners</h3>
                <p class="text-sm text-gray-600 mb-4">Category section banners</p>
                <div class="text-2xl font-bold text-green-600">{{ $categoryBanners ?? 0 }}</div>
                <p class="text-xs text-gray-500">Active banners</p>
            </div>
            
            <div class="bg-purple-50 p-6 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Promotional Banners</h3>
                <p class="text-sm text-gray-600 mb-4">Promotional banners</p>
                <div class="text-2xl font-bold text-purple-600">{{ $promoBanners ?? 0 }}</div>
                <p class="text-xs text-gray-500">Active banners</p>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <form method="GET" action="{{ route('admin.cms.banners.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Search Banners</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Banner title..." 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Position</label>
                    <select name="position" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Positions</option>
                        <option value="hero" {{ request('position') == 'hero' ? 'selected' : '' }}>Hero Section</option>
                        <option value="category" {{ request('position') == 'category' ? 'selected' : '' }}>Category</option>
                        <option value="promo" {{ request('position') == 'promo' ? 'selected' : '' }}>Promotional</option>
                        <option value="sidebar" {{ request('position') == 'sidebar' ? 'selected' : '' }}>Sidebar</option>
                        <option value="footer" {{ request('position') == 'footer' ? 'selected' : '' }}>Footer</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-search mr-2"></i>
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Banners Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Banner
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Position
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Display Period
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sort Order
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($banners as $banner)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($banner->image)
                                            <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" 
                                                 class="h-16 w-24 rounded object-cover mr-4">
                                        @else
                                            <div class="h-16 w-24 bg-gray-200 rounded flex items-center justify-center mr-4">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $banner->title }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($banner->description, 50) }}</div>
                                            @if($banner->link)
                                                <div class="text-xs text-blue-600">
                                                    <a href="{{ $banner->link }}" target="_blank" class="hover:underline">{{ $banner->link }}</a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($banner->position) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($banner->starts_at && $banner->ends_at)
                                        {{ $banner->starts_at->format('M j') }} - {{ $banner->ends_at->format('M j, Y') }}
                                    @elseif($banner->starts_at)
                                        From {{ $banner->starts_at->format('M j, Y') }}
                                    @elseif($banner->ends_at)
                                        Until {{ $banner->ends_at->format('M j, Y') }}
                                    @else
                                        Always
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $banner->sort_order ?? 0 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                                        $banner->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' 
                                    }}">
                                        {{ $banner->status === 'active' ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.cms.banners.edit', $banner) }}" class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.cms.banners.toggle-status', $banner) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-yellow-600 hover:text-yellow-900" title="Toggle Status">
                                                <i class="fas fa-toggle-on"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.cms.banners.delete', $banner) }}" onsubmit="return confirm('Are you sure?')" class="inline">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    No banners found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($banners->hasPages())
            <div class="bg-white px-4 py-3 sm:px-6 lg:px-8 border-t border-gray-200">
                {{ $banners->links() }}
            </div>
        @endif
    </div>
</div>

@endsection
