@extends('layouts.app')

@section('title', $campaign->title . ' - Campaign Details')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-2 text-sm">
            <li>
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-home mr-1"></i>Home
                </a>
            </li>
            <li class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <a href="{{ route('campaigns.index') }}" class="text-gray-500 hover:text-gray-700">
                    Campaigns
                </a>
            </li>
            <li class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <span class="text-gray-900">{{ $campaign->title }}</span>
            </li>
        </ol>
    </nav>

    <!-- Campaign Header -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
        <div class="relative">
            <!-- Campaign Banner -->
            <div class="relative h-64 md:h-96 bg-gradient-to-br from-purple-600 to-pink-600">
                @if($campaign->image)
                    <img src="{{ asset('storage/' . $campaign->image) }}" 
                         alt="{{ $campaign->title }}" 
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="fas fa-bullhorn text-white text-6xl"></i>
                    </div>
                @endif
                
                <!-- Status Badge -->
                <div class="absolute top-6 right-6">
                    @if($campaign->is_active)
                        <span class="bg-green-500 text-white text-sm px-3 py-2 rounded-full font-semibold">
                            <i class="fas fa-circle mr-2"></i>ACTIVE NOW
                        </span>
                    @elseif($campaign->is_upcoming)
                        <span class="bg-blue-500 text-white text-sm px-3 py-2 rounded-full font-semibold">
                            <i class="fas fa-clock mr-2"></i>COMING SOON
                        </span>
                    @else
                        <span class="bg-gray-500 text-white text-sm px-3 py-2 rounded-full font-semibold">
                            <i class="fas fa-times mr-2"></i>ENDED
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Campaign Content -->
        <div class="p-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $campaign->title }}</h1>
                    
                    <div class="prose max-w-none text-gray-600 mb-8">
                        {!! $campaign->description !!}
                    </div>

                    <!-- Campaign Details -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Campaign Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center">
                                <i class="fas fa-users text-purple-500 mr-3 text-lg"></i>
                                <div>
                                    <span class="text-sm text-gray-600">Target Audience</span>
                                    <div class="font-semibold">{{ $campaign->target_audience }}</div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-tag text-purple-500 mr-3 text-lg"></i>
                                <div>
                                    <span class="text-sm text-gray-600">Campaign Type</span>
                                    <div class="font-semibold">{{ ucfirst($campaign->campaign_type) }}</div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt text-purple-500 mr-3 text-lg"></i>
                                <div>
                                    <span class="text-sm text-gray-600">Duration</span>
                                    <div class="font-semibold">
                                        {{ $campaign->start_date->format('M j, Y') }} - {{ $campaign->end_date->format('M j, Y') }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-clock text-purple-500 mr-3 text-lg"></i>
                                <div>
                                    <span class="text-sm text-gray-600">Time Remaining</span>
                                    <div class="font-semibold">
                                        @if($campaign->days_remaining > 0)
                                            {{ $campaign->days_remaining }} days
                                        @else
                                            Ended
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Discount Card -->
                    <div class="bg-gradient-to-br from-purple-600 to-pink-600 rounded-lg p-6 text-white">
                        <h3 class="text-xl font-bold mb-4">Special Offer</h3>
                        <div class="text-center mb-4">
                            <div class="text-4xl font-bold">
                                {{ $campaign->formatted_discount_value }}
                            </div>
                            <span class="text-purple-100">Discount</span>
                        </div>
                        
                        @if($campaign->formatted_min_purchase)
                            <div class="text-center mb-4">
                                <span class="text-purple-100">Min. Purchase</span>
                                <div class="text-xl font-semibold">
                                    {{ $campaign->formatted_min_purchase }}
                                </div>
                            </div>
                        @endif
                        
                        @if($campaign->formatted_max_discount)
                            <div class="text-center">
                                <span class="text-purple-100">Max. Discount</span>
                                <div class="text-lg font-semibold">
                                    {{ $campaign->formatted_max_discount }}
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Action Button -->
                    @if($campaign->is_active)
                        @if($campaign->link)
                            <a href="{{ $campaign->link }}" 
                               target="_blank"
                               class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-lg transition-colors duration-300 text-center">
                                <i class="fas fa-external-link-alt mr-2"></i>
                                Shop Now
                            </a>
                        @else
                            <a href="{{ route('products.index') }}" 
                               class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-lg transition-colors duration-300 text-center">
                                <i class="fas fa-shopping-bag mr-2"></i>
                                Shop Products
                            </a>
                        @endif
                    @elseif($campaign->is_upcoming)
                        <button disabled 
                                class="w-full bg-blue-600 text-white font-bold py-4 px-6 rounded-lg cursor-not-allowed text-center">
                            <i class="fas fa-clock mr-2"></i>
                            Starts {{ $campaign->start_date->diffForHumans() }}
                        </button>
                    @else
                        <button disabled 
                                class="w-full bg-gray-400 text-white font-bold py-4 px-6 rounded-lg cursor-not-allowed text-center">
                            <i class="fas fa-times mr-2"></i>
                            Campaign Ended
                        </button>
                    @endif

                    <!-- Share Campaign -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Share Campaign</h3>
                        <div class="flex space-x-3">
                            <button onclick="window.open('https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}', '_blank')" 
                                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-3 rounded transition-colors">
                                <i class="fab fa-facebook-f"></i>
                            </button>
                            <button onclick="window.open('https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($campaign->title) }}', '_blank')" 
                                    class="flex-1 bg-sky-500 hover:bg-sky-600 text-white py-2 px-3 rounded transition-colors">
                                <i class="fab fa-twitter"></i>
                            </button>
                            <button onclick="window.open('https://wa.me/?text={{ urlencode($campaign->title . ' ' . url()->current()) }}', '_blank')" 
                                    class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-3 rounded transition-colors">
                                <i class="fab fa-whatsapp"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Related Campaigns -->
    @if($relatedCampaigns->count() > 0)
        <section class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Campaigns</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($relatedCampaigns as $relatedCampaign)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="relative h-32 bg-gradient-to-br from-purple-500 to-pink-500">
                            @if($relatedCampaign->image)
                                <img src="{{ asset('storage/' . $relatedCampaign->image) }}" 
                                     alt="{{ $relatedCampaign->title }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-bullhorn text-white text-2xl"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-4">
                            <h3 class="font-bold text-gray-900 mb-2">{{ $relatedCampaign->title }}</h3>
                            <div class="text-purple-600 font-semibold mb-3">
                                {{ $relatedCampaign->formatted_discount_value }}
                            </div>
                            <a href="{{ route('campaigns.show', $relatedCampaign->slug) }}" 
                               class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                View Details <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
