@extends('layouts.app')

@section('title', 'Campaigns - Special Offers & Promotions')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-br from-purple-700 via-purple-600 to-pink-600 text-white">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-pink-300/20 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 left-1/4 w-64 h-64 bg-purple-300/10 rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
            <div class="text-center">
                <div class="inline-flex items-center px-4 py-2 bg-white/15 backdrop-blur-sm rounded-full text-sm font-medium text-white/90 mb-6">
                    <i class="fas fa-bolt text-yellow-400 mr-2"></i>
                    Limited Time Offers
                </div>
                <h1 class="text-5xl md:text-6xl font-bold mb-4 tracking-tight">
                    Campaigns & 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-pink-300">Special Offers</span>
                </h1>
                <p class="text-xl text-purple-100 max-w-3xl mx-auto mb-8">
                    Discover amazing deals, exclusive promotions, and limited-time offers tailored just for you!
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <div class="px-6 py-3 bg-white/10 backdrop-blur-sm rounded-xl">
                        <div class="text-2xl font-bold">{{ $activeCampaigns->count() + $upcomingCampaigns->count() }}</div>
                        <div class="text-sm text-purple-200">Total Campaigns</div>
                    </div>
                    <div class="px-6 py-3 bg-white/10 backdrop-blur-sm rounded-xl">
                        <div class="text-2xl font-bold text-green-300">{{ $activeCampaigns->count() }}</div>
                        <div class="text-sm text-purple-200">Active Now</div>
                    </div>
                    <div class="px-6 py-3 bg-white/10 backdrop-blur-sm rounded-xl">
                        <div class="text-2xl font-bold text-yellow-300">{{ $upcomingCampaigns->count() }}</div>
                        <div class="text-sm text-purple-200">Coming Soon</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-gray-50 to-transparent"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-10">
        <!-- Active Campaigns -->
        @if($activeCampaigns->count() > 0)
            <section class="mb-12" data-aos="fade-up">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center">
                        <div class="p-3 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl shadow-lg shadow-orange-200 mr-4">
                            <i class="fas fa-fire text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Active Campaigns</h2>
                            <p class="text-sm text-gray-500">Grab these deals before they're gone</p>
                        </div>
                    </div>
                    <span class="px-4 py-2 bg-orange-100 text-orange-700 text-sm font-semibold rounded-full animate-pulse">
                        {{ $activeCampaigns->count() }} Live Now
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($activeCampaigns as $campaign)
                        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden transform hover:-translate-y-2">
                            <div class="relative h-52 overflow-hidden">
                                @if($campaign->image)
                                    <img src="{{ asset('storage/' . $campaign->image) }}" 
                                         alt="{{ $campaign->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-purple-600 via-purple-500 to-pink-500 flex items-center justify-center">
                                        <div class="text-center">
                                            <i class="fas fa-bullhorn text-white text-5xl mb-2 opacity-50"></i>
                                            <p class="text-white/60 text-sm">{{ $campaign->title }}</p>
                                        </div>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                                <div class="absolute top-4 right-4 flex flex-col gap-2">
                                    <span class="px-3 py-1.5 bg-green-500 text-white text-xs font-bold rounded-full shadow-lg flex items-center gap-1">
                                        <span class="w-2 h-2 bg-white rounded-full animate-ping"></span>
                                        ACTIVE
                                    </span>
                                </div>
                                @if($campaign->days_remaining <= 3)
                                    <div class="absolute top-4 left-4">
                                        <span class="px-3 py-1.5 bg-red-500 text-white text-xs font-bold rounded-full shadow-lg animate-pulse">
                                            🔥 Ending Soon
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors">{{ $campaign->title }}</h3>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed">{{ $campaign->description }}</p>

                                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-5 mb-4 border border-purple-100">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</span>
                                            <div class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600">
                                                {{ $campaign->formatted_discount_value }}
                                            </div>
                                        </div>
                                        @if($campaign->formatted_min_purchase)
                                            <div class="text-right">
                                                <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Min. Purchase</span>
                                                <div class="text-lg font-semibold text-gray-900">
                                                    {{ $campaign->formatted_min_purchase }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-3 mb-5">
                                    <div class="text-center p-2 bg-gray-50 rounded-lg">
                                        <i class="fas fa-users text-purple-500 text-sm"></i>
                                        <p class="text-xs text-gray-600 mt-1 truncate">{{ $campaign->target_audience }}</p>
                                    </div>
                                    <div class="text-center p-2 bg-gray-50 rounded-lg">
                                        <i class="fas fa-tag text-pink-500 text-sm"></i>
                                        <p class="text-xs text-gray-600 mt-1 truncate">{{ ucfirst($campaign->campaign_type) }}</p>
                                    </div>
                                    <div class="text-center p-2 bg-gray-50 rounded-lg">
                                        <i class="fas fa-clock text-orange-500 text-sm"></i>
                                        <p class="text-xs text-gray-600 mt-1">{{ $campaign->days_remaining }}d left</p>
                                    </div>
                                </div>

                                <a href="{{ route('campaigns.show', $campaign->slug) }}" 
                                   class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-300 inline-flex items-center justify-center gap-2 group/btn shadow-lg shadow-purple-200">
                                    <span>Shop Now</span>
                                    <i class="fas fa-arrow-right group-hover/btn:translate-x-1 transition-transform"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Upcoming Campaigns -->
        @if($upcomingCampaigns->count() > 0)
            <section class="mb-12" data-aos="fade-up">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center">
                        <div class="p-3 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl shadow-lg shadow-blue-200 mr-4">
                            <i class="fas fa-calendar-alt text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Upcoming Campaigns</h2>
                            <p class="text-sm text-gray-500">Save the date for these upcoming deals</p>
                        </div>
                    </div>
                    <span class="px-4 py-2 bg-blue-100 text-blue-700 text-sm font-semibold rounded-full">
                        {{ $upcomingCampaigns->count() }} Coming Soon
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($upcomingCampaigns as $campaign)
                        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden transform hover:-translate-y-2 border-2 border-dashed border-blue-200 hover:border-blue-400">
                            <div class="relative h-52 overflow-hidden">
                                @if($campaign->image)
                                    <img src="{{ asset('storage/' . $campaign->image) }}" 
                                         alt="{{ $campaign->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 opacity-75">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-600 to-cyan-500 flex items-center justify-center">
                                        <div class="text-center">
                                            <i class="fas fa-calendar text-white text-5xl mb-2 opacity-30"></i>
                                            <p class="text-white/40 text-sm">{{ $campaign->title }}</p>
                                        </div>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                                <div class="absolute top-4 right-4">
                                    <span class="px-3 py-1.5 bg-blue-500 text-white text-xs font-bold rounded-full shadow-lg">
                                        COMING SOON
                                    </span>
                                </div>
                            </div>

                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $campaign->title }}</h3>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed">{{ $campaign->description }}</p>

                                <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl p-5 mb-5 border border-blue-100">
                                    <div class="text-center">
                                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Starts</span>
                                        <div class="flex items-center justify-center gap-3 mt-2">
                                            <div class="text-center">
                                                <div class="text-2xl font-bold text-blue-600">{{ $campaign->start_date->format('d') }}</div>
                                                <div class="text-xs text-gray-500">{{ $campaign->start_date->format('M') }}</div>
                                            </div>
                                            <div class="text-2xl text-blue-300 font-bold">:</div>
                                            <div class="text-center">
                                                <div class="text-2xl font-bold text-blue-600">{{ $campaign->start_date->format('H') }}</div>
                                                <div class="text-xs text-gray-500">Hour</div>
                                            </div>
                                            <div class="text-2xl text-blue-300 font-bold">:</div>
                                            <div class="text-center">
                                                <div class="text-2xl font-bold text-blue-600">{{ $campaign->start_date->format('i') }}</div>
                                                <div class="text-xs text-gray-500">Min</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button disabled 
                                        class="w-full bg-gray-200 text-gray-500 font-semibold py-3 px-4 rounded-xl cursor-not-allowed inline-flex items-center justify-center gap-2">
                                    <i class="fas fa-clock"></i>
                                    Starts {{ $campaign->start_date->diffForHumans() }}
                                </button>

                                <div class="mt-3 text-center">
                                    <span class="text-xs text-gray-400">
                                        <i class="fas fa-bell mr-1"></i>
                                        Notify me when this starts
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Expired Campaigns -->
        @if($expiredCampaigns->count() > 0)
            <section class="mb-12" data-aos="fade-up">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center">
                        <div class="p-3 bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl shadow-lg shadow-gray-200 mr-4">
                            <i class="fas fa-history text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Recent Expired Campaigns</h2>
                            <p class="text-sm text-gray-500">Don't worry, more deals are coming</p>
                        </div>
                    </div>
                    <span class="px-4 py-2 bg-gray-100 text-gray-600 text-sm font-semibold rounded-full">
                        {{ $expiredCampaigns->count() }} Ended
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($expiredCampaigns as $campaign)
                        <div class="group bg-white/60 backdrop-blur-sm rounded-2xl shadow-md overflow-hidden opacity-70 grayscale hover:grayscale-0 transition-all duration-500">
                            <div class="relative h-48 overflow-hidden">
                                @if($campaign->image)
                                    <img src="{{ asset('storage/' . $campaign->image) }}" 
                                         alt="{{ $campaign->title }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center">
                                        <i class="fas fa-bullhorn text-white text-4xl opacity-30"></i>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                                <div class="absolute top-4 right-4">
                                    <span class="px-3 py-1.5 bg-gray-600 text-white text-xs font-bold rounded-full shadow-lg">
                                        EXPIRED
                                    </span>
                                </div>
                            </div>

                            <div class="p-6">
                                <h3 class="text-lg font-bold text-gray-700 mb-2">{{ $campaign->title }}</h3>
                                <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ $campaign->description }}</p>

                                <div class="bg-gray-100 rounded-xl p-4 mb-4">
                                    <div class="text-center">
                                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Ended</span>
                                        <div class="text-lg font-semibold text-gray-600 mt-1">
                                            {{ $campaign->end_date->format('M j, Y') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @php
            $currentCurrency = \App\Models\Setting::get('currency', 'USD');
            $coupons = \App\Models\Coupon::active()
                ->orderBy('expires_at', 'asc')
                ->take(4)
                ->get();
        @endphp

        <!-- Smart Coupons & Offers -->
        @if($activeCampaigns->count() > 0 || $upcomingCampaigns->count() > 0 || $expiredCampaigns->count() > 0)
            <section class="mb-12" data-aos="fade-up">
                <div class="relative overflow-hidden bg-gradient-to-br from-purple-600 via-purple-700 to-pink-700 rounded-3xl shadow-2xl shadow-purple-200">
                    <div class="absolute inset-0 overflow-hidden">
                        <div class="absolute -top-20 -right-20 w-60 h-60 bg-white/10 rounded-full blur-3xl"></div>
                        <div class="absolute -bottom-20 -left-20 w-60 h-60 bg-pink-300/20 rounded-full blur-3xl"></div>
                    </div>
                    <div class="relative px-8 py-10">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <div class="inline-flex items-center px-4 py-2 bg-white/15 backdrop-blur-sm rounded-full text-sm font-medium text-white/90 mb-3">
                                    <i class="fas fa-ticket-alt text-yellow-400 mr-2"></i>
                                    Exclusive Offers
                                </div>
                                <h2 class="text-3xl font-bold text-white">Smart Coupons & Offers</h2>
                                <p class="text-purple-200 mt-1">Save more with these exclusive coupon codes</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            @forelse($coupons as $coupon)
                                <div class="group bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                                    <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-5 text-white relative overflow-hidden">
                                        <div class="absolute -top-6 -right-6 w-16 h-16 bg-white/10 rounded-full"></div>
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="inline-flex items-center px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-bold">
                                                {{ $coupon->formatted_value }} OFF
                                            </span>
                                            <div class="text-xs opacity-80 flex items-center gap-1">
                                                <i class="fas fa-clock"></i>
                                                {{ $coupon->expires_at->diffForHumans() }}
                                            </div>
                                        </div>
                                        <h3 class="font-bold text-lg mb-1">{{ $coupon->name }}</h3>
                                        <div class="bg-white/10 backdrop-blur-sm rounded-lg px-3 py-2 text-center">
                                            <span class="text-xs opacity-70">Code</span>
                                            <div class="font-mono font-bold tracking-wider text-lg">{{ $coupon->code }}</div>
                                        </div>
                                    </div>

                                    <div class="p-5">
                                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $coupon->description }}</p>

                                        <div class="space-y-2 mb-4">
                                            @if($coupon->minimum_amount)
                                                <div class="flex items-center text-xs text-gray-500">
                                                    <i class="fas fa-shopping-cart mr-2 text-purple-500"></i>
                                                    <span>Min. purchase: {{ \App\Helpers\CurrencyHelper::format($coupon->minimum_amount, $currentCurrency) }}</span>
                                                </div>
                                            @endif
                                            @if($coupon->usage_limit)
                                                <div class="flex items-center text-xs text-gray-500">
                                                    <i class="fas fa-users mr-2 text-purple-500"></i>
                                                    <span>{{ $coupon->usage_limit - $coupon->used_count }} uses left</span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex gap-2">
                                            <button onclick="copyCouponCode('{{ $coupon->code }}', this)" 
                                                    class="flex-1 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold py-2.5 px-3 rounded-xl transition-all duration-200 inline-flex items-center justify-center gap-2">
                                                <i class="fas fa-copy"></i>
                                                Copy Code
                                            </button>
                                            <a href="{{ route('products.index') }}" 
                                               class="flex-1 bg-pink-600 hover:bg-pink-700 text-white text-sm font-semibold py-2.5 px-3 rounded-xl transition-all duration-200 inline-flex items-center justify-center gap-2">
                                                <i class="fas fa-shopping-bag"></i>
                                                Shop
                                            </a>
                                        </div>
                                    </div>

                                    <div class="bg-gray-50 px-5 py-3 border-t border-gray-100">
                                        <div class="flex items-center justify-between text-xs text-gray-500">
                                            <span class="flex items-center gap-1"><i class="fas fa-fire text-orange-500"></i> Popular</span>
                                            <span class="flex items-center gap-1"><i class="fas fa-shield-alt text-green-500"></i> Verified</span>
                                            <span class="flex items-center gap-1"><i class="fas fa-star text-yellow-500"></i> Best Deal</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-center col-span-full">
                                    <i class="fas fa-ticket-alt text-white/40 text-4xl mb-3"></i>
                                    <p class="text-white/70">No coupons available right now. Check back later!</p>
                                </div>
                            @endforelse
                        </div>

                        @if($coupons->count() > 0)
                            <div class="mt-8 text-center">
                                <a href="{{ route('products.index') }}" 
                                   class="inline-flex items-center px-6 py-3 bg-white/15 hover:bg-white/25 backdrop-blur-sm text-white font-semibold rounded-xl transition-all duration-300 gap-2">
                                    <i class="fas fa-gift"></i>
                                    View All Coupons & Offers
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endif

        <!-- Empty State -->
        @if($activeCampaigns->count() === 0 && $upcomingCampaigns->count() === 0 && $expiredCampaigns->count() === 0)
            <div class="text-center py-20" data-aos="fade-up">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-purple-100 to-pink-100 rounded-full mb-6">
                    <i class="fas fa-bullhorn text-purple-400 text-4xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-3">No Campaigns Available</h3>
                <p class="text-gray-600 max-w-md mx-auto mb-8">
                    There are currently no active campaigns. Check back soon for exciting offers and promotions!
                </p>
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold rounded-xl transition-all duration-300 gap-2 shadow-lg shadow-purple-200">
                    <i class="fas fa-shopping-bag"></i>
                    Browse Products
                </a>
            </div>
        @endif
    </div>
</div>

<style>
@import url('https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css');
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
AOS.init({
    duration: 800,
    once: true,
    offset: 50
});

function copyCouponCode(code, button) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(code).then(function() {
            var originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check"></i> Copied!';
            button.classList.remove('bg-purple-600', 'hover:bg-purple-700');
            button.classList.add('bg-green-600', 'hover:bg-green-700');
            setTimeout(function() {
                button.innerHTML = originalText;
                button.classList.remove('bg-green-600', 'hover:bg-green-700');
                button.classList.add('bg-purple-600', 'hover:bg-purple-700');
            }, 2000);
        }).catch(function() {
            fallbackCopyCode(code, button);
        });
    } else {
        fallbackCopyCode(code, button);
    }
}

function fallbackCopyCode(code, button) {
    var textarea = document.createElement('textarea');
    textarea.value = code;
    textarea.style.position = 'fixed';
    textarea.style.opacity = '0';
    document.body.appendChild(textarea);
    textarea.select();
    try {
        document.execCommand('copy');
        var originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i> Copied!';
        button.classList.remove('bg-purple-600', 'hover:bg-purple-700');
        button.classList.add('bg-green-600', 'hover:bg-green-700');
        setTimeout(function() {
            button.innerHTML = originalText;
            button.classList.remove('bg-green-600', 'hover:bg-green-700');
            button.classList.add('bg-purple-600', 'hover:bg-purple-700');
        }, 2000);
    } catch (e) {
        alert('Please manually copy the code: ' + code);
    }
    document.body.removeChild(textarea);
}
</script>
@endsection
