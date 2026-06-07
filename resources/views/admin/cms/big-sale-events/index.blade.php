@extends('admin.layouts.app')

@section('title', 'Big Sale Events Management')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-fire mr-2 text-red-600"></i>
                    Big Sale Events Management
                </h1>
                <a href="{{ route('admin.cms.big-sale-events.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-plus mr-1"></i>
                    Create Big Sale Event
                </a>
            </div>

            <!-- Filters -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <form method="GET" action="{{ route('admin.cms.big-sale-events.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="form-input mt-1">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Featured</label>
                        <select name="featured" class="form-input mt-1">
                            <option value="">All</option>
                            <option value="yes" {{ request('featured') == 'yes' ? 'selected' : '' }}>Featured</option>
                            <option value="no" {{ request('featured') == 'no' ? 'selected' : '' }}>Not Featured</option>
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

            <!-- Events Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Event
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Discount
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Schedule
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
                        @forelse($bigSaleEvents as $event)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($event->banner_image)
                                            <img src="{{ $event->banner_image_url }}" alt="{{ $event->title }}" 
                                                 class="h-12 w-20 object-cover rounded mr-3">
                                        @else
                                            <div class="h-12 w-20 bg-gradient-to-r from-red-400 to-pink-400 rounded mr-3 flex items-center justify-center">
                                                <i class="fas fa-fire text-white text-lg"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $event->title }}</div>
                                            @if($event->subtitle)
                                                <div class="text-xs text-gray-500">{{ $event->subtitle }}</div>
                                            @endif
                                            @if($event->featured)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                                                    <i class="fas fa-star mr-1"></i>Featured
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($event->discount_percentage)
                                        <div class="text-sm">
                                            <span class="text-lg font-bold text-red-600">{{ $event->formatted_discount }}</span>
                                            @if($event->original_price && $event->sale_price)
                                                <div class="text-xs text-gray-500">
                                                    {{ $event->formatted_original_price }} → {{ $event->formatted_sale_price }}
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">No discount</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($event->start_date || $event->end_date)
                                        <div>
                                            @if($event->start_date)
                                                <div>From: {{ $event->start_date->format('M d, Y') }}</div>
                                            @endif
                                            @if($event->end_date)
                                                <div>To: {{ $event->end_date->format('M d, Y') }}</div>
                                            @endif
                                        </div>
                                        <div class="text-xs mt-1">
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium 
                                                @if($event->urgency_level == 'critical') bg-red-100 text-red-800
                                                @elseif($event->urgency_level == 'high') bg-orange-100 text-orange-800
                                                @elseif($event->urgency_level == 'medium') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ $event->days_left }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-gray-400">No schedule</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($event->isActive())
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
                                        <a href="{{ route('admin.cms.big-sale-events.edit', $event) }}" 
                                           class="text-blue-600 hover:text-blue-900" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.cms.big-sale-events.toggle-status', $event) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-yellow-600 hover:text-yellow-900" title="Toggle Status">
                                                <i class="fas fa-power-off"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.cms.big-sale-events.toggle-featured', $event) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-purple-600 hover:text-purple-900" title="Toggle Featured">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.cms.big-sale-events.duplicate', $event) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900" title="Duplicate">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.cms.big-sale-events.destroy', $event) }}" class="inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this event?')">
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
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <i class="fas fa-fire text-6xl text-gray-300 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Big Sale Events found</h3>
                                    <p class="text-gray-500 mb-4">Create your first big sale event to drive sales and engagement.</p>
                                    <a href="{{ route('admin.cms.big-sale-events.create') }}" 
                                       class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                        <i class="fas fa-plus mr-1"></i>
                                        Create First Event
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($bigSaleEvents->hasPages())
                <div class="mt-6">
                    {{ $bigSaleEvents->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
