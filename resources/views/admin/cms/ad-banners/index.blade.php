@extends('admin.layouts.app')

@section('title', 'Ad Banners Management')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-ad mr-2 text-blue-600"></i>
                    Ad Banners Management
                </h1>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.cms.ad-banners.stats') }}" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-chart-bar mr-1"></i>
                        Statistics
                    </a>
                    <a href="{{ route('admin.cms.ad-banners.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-plus mr-1"></i>
                        Add New Banner
                    </a>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <form method="GET" action="{{ route('admin.cms.ad-banners.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Position</label>
                        <select name="position" class="form-input mt-1">
                            <option value="">All Positions</option>
                            @foreach($positions as $key => $position)
                                <option value="{{ $key }}" {{ request('position') == $key ? 'selected' : '' }}>
                                    {{ $position }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Page</label>
                        <select name="page" class="form-input mt-1">
                            <option value="">All Pages</option>
                            @foreach($pages as $key => $page)
                                <option value="{{ $key }}" {{ request('page') == $key ? 'selected' : '' }}>
                                    {{ $page }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="form-input mt-1">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-filter mr-1"></i>
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Banners Table -->
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
                                Page
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Height
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Schedule
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Stats
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
                        @forelse($adBanners as $adBanner)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($adBanner->image)
                                            <img src="{{ $adBanner->image_url }}" alt="{{ $adBanner->alt_text }}" 
                                                 class="h-12 w-20 object-cover rounded mr-3">
                                        @else
                                            <div class="h-12 w-20 bg-gray-200 rounded mr-3 flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $adBanner->title }}</div>
                                            @if($adBanner->link)
                                                <div class="text-xs text-gray-500 truncate max-w-xs">{{ $adBanner->link }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $adBanner->formatted_position }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $adBanner->formatted_page }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        {{ $adBanner->height }}px
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($adBanner->start_date || $adBanner->end_date)
                                        <div>
                                            @if($adBanner->start_date)
                                                <div>From: {{ $adBanner->start_date->format('M d, Y') }}</div>
                                            @endif
                                            @if($adBanner->end_date)
                                                <div>To: {{ $adBanner->end_date->format('M d, Y') }}</div>
                                            @endif
                                        </div>
                                        @if(!$adBanner->isActive())
                                            <span class="text-xs text-red-600">Expired</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">No schedule</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="text-xs">
                                        <div><i class="fas fa-eye mr-1"></i>{{ $adBanner->impressions }} views</div>
                                        <div><i class="fas fa-mouse-pointer mr-1"></i>{{ $adBanner->clicks }} clicks</div>
                                        <div><i class="fas fa-chart-line mr-1"></i>{{ $adBanner->click_through_rate }}% CTR</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($adBanner->isActive())
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.cms.ad-banners.edit', $adBanner) }}" 
                                           class="text-blue-600 hover:text-blue-900" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.cms.ad-banners.toggle-status', $adBanner) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-yellow-600 hover:text-yellow-900" title="Toggle Status">
                                                <i class="fas fa-power-off"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.cms.ad-banners.duplicate', $adBanner) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-purple-600 hover:text-purple-900" title="Duplicate">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.cms.ad-banners.destroy', $adBanner) }}" class="inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this banner?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <i class="fas fa-ad text-6xl text-gray-300 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No ad banners found</h3>
                                    <p class="text-gray-500 mb-4">Get started by creating your first ad banner.</p>
                                    <a href="{{ route('admin.cms.ad-banners.create') }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                        <i class="fas fa-plus mr-1"></i>
                                        Create First Banner
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($adBanners->hasPages())
                <div class="mt-6">
                    {{ $adBanners->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
