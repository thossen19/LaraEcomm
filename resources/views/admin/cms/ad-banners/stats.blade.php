@extends('admin.layouts.app')

@section('title', 'Ad Banner Statistics')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-chart-bar mr-2 text-blue-600"></i>
                    Ad Banner Statistics
                </h1>
                <a href="{{ route('admin.cms.ad-banners.index') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Back to Banners
                </a>
            </div>

            <!-- Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-700 rounded-full">
                            <i class="fas fa-ad text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <div class="text-3xl font-bold">{{ $totalBanners }}</div>
                            <div class="text-blue-100">Total Banners</div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-700 rounded-full">
                            <i class="fas fa-check-circle text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <div class="text-3xl font-bold">{{ $activeBanners }}</div>
                            <div class="text-green-100">Active Banners</div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-700 rounded-full">
                            <i class="fas fa-mouse-pointer text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <div class="text-3xl font-bold">{{ number_format($totalClicks) }}</div>
                            <div class="text-purple-100">Total Clicks</div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-6 text-white">
                    <div class="flex items-center">
                        <div class="p-3 bg-orange-700 rounded-full">
                            <i class="fas fa-eye text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <div class="text-3xl font-bold">{{ number_format($totalImpressions) }}</div>
                            <div class="text-orange-100">Total Impressions</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Banners by Position -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-map-marker-alt mr-2 text-gray-600"></i>
                        Banners by Position
                    </h3>
                    
                    @if($bannersByPosition->count() > 0)
                        <div class="space-y-3">
                            @foreach($bannersByPosition as $position => $count)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">
                                        {{ \App\Models\AdBanner::POSITIONS[$position] ?? $position }}
                                    </span>
                                    <div class="flex items-center">
                                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($count / $totalBanners) * 100 }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-600">{{ $count }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-chart-pie text-4xl mb-2"></i>
                            <p>No banner data available</p>
                        </div>
                    @endif
                </div>

                <!-- Banners by Page -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-file-alt mr-2 text-gray-600"></i>
                        Banners by Page
                    </h3>
                    
                    @if($bannersByPage->count() > 0)
                        <div class="space-y-3">
                            @foreach($bannersByPage as $page => $count)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">
                                        {{ \App\Models\AdBanner::PAGES[$page] ?? $page }}
                                    </span>
                                    <div class="flex items-center">
                                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($count / $totalBanners) * 100 }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-600">{{ $count }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-chart-pie text-4xl mb-2"></i>
                            <p>No banner data available</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Top Performing Banners -->
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-trophy mr-2 text-gray-600"></i>
                    Top Performing Banners
                </h3>
                
                @if($topBanners->count() > 0)
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
                                        Impressions
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Clicks
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        CTR
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($topBanners as $banner)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($banner->image)
                                                    <img src="{{ $banner->image_url }}" alt="{{ $banner->alt_text }}" 
                                                         class="h-8 w-12 object-cover rounded mr-3">
                                                @else
                                                    <div class="h-8 w-12 bg-gray-200 rounded mr-3 flex items-center justify-center">
                                                        <i class="fas fa-image text-gray-400 text-xs"></i>
                                                    </div>
                                                @endif
                                                <div class="text-sm font-medium text-gray-900">{{ $banner->title }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $banner->formatted_position }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ number_format($banner->impressions) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ number_format($banner->clicks) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $banner->click_through_rate }}%
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <i class="fas fa-chart-line text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No banner performance data</h3>
                        <p class="text-gray-500">Banner statistics will appear here once banners start getting impressions and clicks.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
