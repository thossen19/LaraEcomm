@extends('layouts.app')

@section('title', $deal->title . ' - Deal Details')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Deal Header -->
    <div class="bg-gradient-to-r from-red-600 to-orange-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="max-w-4xl mx-auto">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">{{ $deal->title }}</h1>
                        <p class="text-red-100 text-lg">{{ $deal->description }}</p>
                    </div>
                    <div class="text-right">
                        <div class="bg-white text-red-600 px-4 py-2 rounded-full text-2xl font-bold">
                            {{ $deal->formatted_discount_value }} OFF
                        </div>
                        @if($deal->days_remaining !== null)
                            <p class="text-red-100 mt-2">
                                {{ $deal->days_remaining > 0 ? $deal->days_remaining . ' days left' : 'Deal Ended' }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Deal Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-4xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Deal Image -->
                <div>
                    @if($deal->image)
                        <img src="{{ asset('storage/' . $deal->image) }}" 
                             alt="{{ $deal->title }}" 
                             class="w-full rounded-xl shadow-lg">
                    @else
                        <div class="w-full h-96 bg-gradient-to-br from-red-400 to-orange-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-tag text-white text-6xl"></i>
                        </div>
                    @endif
                </div>

                <!-- Deal Details -->
                <div>
                    <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Deal Details</h2>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b">
                                <span class="text-gray-600">Discount Type</span>
                                <span class="font-semibold text-gray-900">
                                    {{ ucfirst($deal->discount_type) }} Discount
                                </span>
                            </div>
                            
                            <div class="flex justify-between items-center py-3 border-b">
                                <span class="text-gray-600">Discount Value</span>
                                <span class="font-semibold text-red-600 text-xl">
                                    {{ $deal->formatted_discount_value }}
                                </span>
                            </div>

                            @if($deal->min_purchase_amount)
                                <div class="flex justify-between items-center py-3 border-b">
                                    <span class="text-gray-600">Minimum Purchase</span>
                                    <span class="font-semibold text-gray-900">
                                        {{ $deal->formatted_min_purchase }}
                                    </span>
                                </div>
                            @endif

                            @if($deal->max_discount_amount)
                                <div class="flex justify-between items-center py-3 border-b">
                                    <span class="text-gray-600">Maximum Discount</span>
                                    <span class="font-semibold text-gray-900">
                                        {{ $deal->formatted_max_discount }}
                                    </span>
                                </div>
                            @endif

                            <div class="flex justify-between items-center py-3 border-b">
                                <span class="text-gray-600">Valid From</span>
                                <span class="font-semibold text-gray-900">
                                    {{ $deal->start_date->format('M j, Y') }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center py-3">
                                <span class="text-gray-600">Valid Until</span>
                                <span class="font-semibold {{ $deal->is_expired ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $deal->end_date->format('M j, Y') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($deal->link)
                        <div class="bg-white rounded-xl shadow-lg p-8">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Ready to Shop?</h3>
                            <p class="text-gray-600 mb-6">
                                Click below to take advantage of this amazing deal before it expires!
                            </p>
                            <a href="{{ $deal->link }}" 
                               target="_blank"
                               class="w-full bg-red-600 text-white px-6 py-4 rounded-lg hover:bg-red-700 transition-colors text-center font-semibold text-lg">
                                <i class="fas fa-shopping-cart mr-2"></i>
                                Shop This Deal
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Additional Information -->
            <div class="mt-12 bg-white rounded-xl shadow-lg p-8">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Important Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Deal Status</h4>
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full mr-2 {{ $deal->status_color === 'green' ? 'bg-green-500' : ($deal->status_color === 'red' ? 'bg-red-500' : 'bg-gray-500') }}"></div>
                            <span class="text-gray-700">
                                {{ ucfirst($deal->status) }}
                                @if($deal->is_expired)
                                    (Expired)
                                @elseif($deal->is_upcoming)
                                    (Starting Soon)
                                @endif
                            </span>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-2">Time Remaining</h4>
                        <div class="text-gray-700">
                            @if($deal->days_remaining !== null)
                                @if($deal->days_remaining > 0)
                                    <span class="text-green-600 font-semibold">{{ $deal->days_remaining }} days</span>
                                @else
                                    <span class="text-red-600 font-semibold">Deal has ended</span>
                                @endif
                            @else
                                <span class="text-gray-600">No time limit</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($deal->days_remaining !== null && $deal->days_remaining <= 3)
                    <div class="mt-6 p-4 bg-orange-50 border border-orange-200 rounded-lg">
                        <p class="text-orange-800">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Hurry!</strong> This deal is ending soon. Don't miss out!
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
