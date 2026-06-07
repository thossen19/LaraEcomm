@extends('admin.layouts.app')

@section('title', 'Campaigns')

@section('content')
<div class="max-w-7xl mx-auto py-6">
    <div class="px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Campaigns</h1>
                <p class="mt-1 text-sm text-gray-600">Manage marketing campaigns and promotions</p>
            </div>
            <a href="{{ route('admin.marketing.campaigns.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Create Campaign
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Campaigns Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Audience</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($campaigns as $campaign)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($campaign->image)
                                        <img src="{{ asset('storage/' . $campaign->image) }}" alt="{{ $campaign->title }}" class="h-12 w-12 object-cover rounded">
                                    @else
                                        <div class="h-12 w-12 bg-gray-200 rounded flex items-center justify-center">
                                            <i class="fas fa-bullhorn text-gray-400"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $campaign->title }}</div>
                                    @if($campaign->description)
                                        <div class="text-sm text-gray-500 truncate max-w-xs">{{ $campaign->description }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($campaign->campaign_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ $campaign->formatted_discount_value }}</span>
                                    @if($campaign->min_purchase_amount)
                                        <div class="text-xs text-gray-500">Min: {{ $campaign->formatted_min_purchase }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">{{ $campaign->target_audience }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $campaign->start_date->format('M j') }} - {{ $campaign->end_date->format('M j') }}
                                    </div>
                                    @if($campaign->days_remaining !== null)
                                        <div class="text-xs {{ $campaign->days_remaining > 0 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $campaign->days_remaining > 0 ? $campaign->days_remaining . ' days left' : 'Expired' }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $campaign->status_color === 'green' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $campaign->status_color === 'gray' ? 'bg-gray-100 text-gray-800' : '' }}
                                        {{ $campaign->status_color === 'red' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($campaign->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.marketing.campaigns.edit', $campaign) }}" class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.marketing.campaigns.toggle-status', $campaign) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-yellow-600 hover:text-yellow-900" title="Toggle Status">
                                                <i class="fas fa-toggle-on"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.marketing.campaigns.delete', $campaign) }}" onsubmit="return confirm('Are you sure?')" class="inline">
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
                                <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-bullhorn text-4xl text-gray-300 mb-3"></i>
                                        <span class="text-lg font-medium">No campaigns found</span>
                                        <span class="text-sm">Create your first campaign to get started</span>
                                        <a href="{{ route('admin.marketing.campaigns.create') }}" class="mt-3 text-blue-600 hover:text-blue-500 font-medium">
                                            Create Campaign →
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($campaigns->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $campaigns->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
