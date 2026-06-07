<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - E-Commerce Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom admin styles */
        .form-input {
            @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500;
        }
        .btn-primary {
            @apply bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors;
        }
        .btn-secondary {
            @apply bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-md transition-colors;
        }
    </style>
</head>
<body class="bg-gray-100">
    @auth
        @if(auth()->user()->hasRole(['super_admin', 'admin']))
            <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white overflow-y-auto">
            <div class="p-4">
                <h2 class="text-xl font-bold flex items-center">
                    <i class="fas fa-store mr-2"></i>
                    E-Commerce Admin
                </h2>
                <div class="mt-2 text-xs text-gray-400">
                    Welcome, {{ auth()->user()->name ?? auth()->user()->first_name }}
                    <span class="ml-2 px-2 py-1 bg-blue-600 rounded text-xs">
                        {{ auth()->user()->role }}
                    </span>
                </div>
            </div>
            <nav class="mt-4">
                <!-- Dashboard -->
                <div class="px-4 py-2">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Dashboard</h3>
                </div>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('admin.dashboard.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700 {{ request()->routeIs('admin.dashboard.*') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        Analytics Overview
                    </a>
                </div>
                
                <!-- Catalog Management -->
                <div class="px-4 py-2 mt-6">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Catalog</h3>
                </div>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('admin.products.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700 {{ request()->routeIs('admin.products.*') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-box mr-3"></i>
                        Products
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700 {{ request()->routeIs('admin.categories.*') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-tags mr-3"></i>
                        Categories
                    </a>
                    <a href="{{ route('admin.brands.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700 {{ request()->routeIs('admin.brands.*') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-trademark mr-3"></i>
                        Brands
                    </a>
                </div>
                
                <!-- Order Management -->
                <div class="px-4 py-2 mt-6">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Orders</h3>
                </div>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700 {{ request()->routeIs('admin.orders.*') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-shopping-cart mr-3"></i>
                        Orders
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700">
                        <i class="fas fa-receipt mr-3"></i>
                        Invoices
                    </a>
                </div>
                
                <!-- Customer Management -->
                <div class="px-4 py-2 mt-6">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Customers</h3>
                </div>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700 {{ request()->routeIs('admin.users.*') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-users mr-3"></i>
                        Users
                    </a>
                    <a href="{{ route('admin.reviews.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700 {{ request()->routeIs('admin.reviews.*') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-user-shield mr-3"></i>
                        Reviews
                    </a>
                </div>
                
                <!-- Inventory -->
                <div class="px-4 py-2 mt-6">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Inventory</h3>
                </div>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('admin.inventory.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700 {{ request()->routeIs('admin.inventory.*') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-warehouse mr-3"></i>
                        Stock Management
                    </a>
                    <a href="{{ route('admin.inventory.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700">
                        <i class="fas fa-exclamation-triangle mr-3"></i>
                        Low Stock Alerts
                    </a>
                </div>
                
                <!-- Marketing -->
                <div class="px-4 py-2 mt-6">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Marketing</h3>
                </div>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('admin.marketing.coupons.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700 {{ request()->routeIs('admin.marketing.coupons.*') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-ticket-alt mr-3"></i>
                        Coupons
                    </a>
                    <a href="{{ route('admin.marketing.campaigns.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700 {{ request()->routeIs('admin.marketing.campaigns.*') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-bullhorn mr-3"></i>
                        Campaigns
                    </a>
                </div>
                
                <!-- Content Management -->
                <div class="px-4 py-2 mt-6">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Content</h3>
                </div>
                <div class="mt-2 space-y-1">
                    <!-- Homepage Dropdown -->
                    <div class="relative">
                        <button onclick="toggleHomepageSubmenu()" class="w-full flex items-center justify-between px-4 py-2 text-sm font-medium rounded hover:bg-gray-700 {{ request()->routeIs('admin.cms.*') ? 'bg-gray-700' : '' }}">
                            <div class="flex items-center">
                                <i class="fas fa-home mr-3"></i>
                                Homepage
                            </div>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div id="homepage-submenu" class="ml-4 mt-1 space-y-1 hidden">
                            <a href="{{ route('admin.cms.banners.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700 {{ request()->routeIs('admin.cms.banners.*') ? 'bg-gray-700' : '' }}">
                                <i class="fas fa-image mr-3 text-xs"></i>
                                Banners
                            </a>
                            <a href="{{ route('admin.deals.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700 {{ request()->routeIs('admin.deals.*') ? 'bg-gray-700' : '' }}">
                                <i class="fas fa-tag mr-3 text-xs"></i>
                                Deals of the Day
                            </a>
                            <a href="{{ route('admin.fashion.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700 {{ request()->routeIs('admin.fashion.*') ? 'bg-gray-700' : '' }}">
                                <i class="fas fa-tshirt mr-3 text-xs"></i>
                                Best of Fashion
                            </a>
                            <a href="{{ route('admin.cms.about') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700 {{ request()->routeIs('admin.cms.about') ? 'bg-gray-700' : '' }}">
                                <i class="fas fa-info-circle mr-3 text-xs"></i>
                                Manage About
                            </a>
                            <a href="{{ route('admin.cms.contact') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700 {{ request()->routeIs('admin.cms.contact') ? 'bg-gray-700' : '' }}">
                                <i class="fas fa-envelope mr-3 text-xs"></i>
                                Manage Contact
                            </a>
                            <a href="{{ route('admin.cms.ad-banners.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700 {{ request()->routeIs('admin.cms.ad-banners.*') ? 'bg-gray-700' : '' }}">
                                <i class="fas fa-ad mr-3 text-xs"></i>
                                Ad Banner
                            </a>
                            <a href="{{ route('admin.cms.big-sale-events.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700 {{ request()->routeIs('admin.cms.big-sale-events.*') ? 'bg-gray-700' : '' }}">
                                <i class="fas fa-fire mr-3 text-xs"></i>
                                Big Sale Event
                            </a>
                            <a href="{{ route('admin.cms.product-of-day.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700 {{ request()->routeIs('admin.cms.product-of-day.*') ? 'bg-gray-700' : '' }}">
                                <i class="fas fa-star mr-3 text-xs"></i>
                                Product of the Day
                            </a>
                        </div>
                    </div>
                    <a href="{{ route('admin.cms.blog.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700 {{ request()->routeIs('admin.cms.blog.*') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-blog mr-3"></i>
                        Blog
                    </a>
                    <a href="{{ route('admin.cms.pages.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700 {{ request()->routeIs('admin.cms.pages.*') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-file-alt mr-3"></i>
                        Pages
                    </a>
                </div>
                
                <!-- Reports -->
                <div class="px-4 py-2 mt-6">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Reports</h3>
                </div>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('admin.dashboard.sales-report') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700">
                        <i class="fas fa-chart-line mr-3"></i>
                        Sales Report
                    </a>
                    <a href="{{ route('admin.dashboard.inventory-report') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700">
                        <i class="fas fa-boxes mr-3"></i>
                        Inventory Report
                    </a>
                    <a href="{{ route('admin.dashboard.user-report') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700">
                        <i class="fas fa-user-chart mr-3"></i>
                        User Report
                    </a>
                </div>
                
                <!-- Settings -->
                <div class="px-4 py-2 mt-6">
                    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Settings</h3>
                </div>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('admin.settings.general') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700 {{ request()->routeIs('admin.settings.*') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-cog mr-3"></i>
                        General Settings
                    </a>
                    <a href="{{ route('admin.settings.payment') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700 {{ request()->routeIs('admin.settings.payment') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-credit-card mr-3"></i>
                        Payment Settings
                    </a>
                    <a href="{{ route('admin.settings.email') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700 {{ request()->routeIs('admin.settings.email') ? 'bg-gray-700' : '' }}">
                        <i class="fas fa-envelope mr-3"></i>
                        Email Settings
                    </a>
                    @if(auth()->user()->isSuperAdmin())
                        <a href="#" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700">
                            <i class="fas fa-truck mr-3"></i>
                            Shipping Settings
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700">
                            <i class="fas fa-robot mr-3"></i>
                            AI Chat Settings
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm font-medium rounded hover:bg-gray-700">
                            <i class="fas fa-sms mr-3"></i>
                            SMS Settings
                        </a>
                    @endif
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Top Header -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-semibold text-gray-900">@yield('header')</h1>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-3">
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name ?? auth()->user()->first_name }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->getRoleDisplayName() }}</p>
                                </div>
                                <div class="flex-shrink-0 h-8 w-8">
                                    @if(auth()->user()->profile_image)
                                        <img class="h-8 w-8 rounded-full" src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="">
                                    @else
                                        <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                            <i class="fas fa-user text-gray-500 text-xs"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md text-sm font-medium" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </button>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </main>
    </div>
        @else
            <div class="min-h-screen flex items-center justify-center bg-gray-100">
                <div class="max-w-md w-full bg-white shadow-lg rounded-lg p-6">
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle text-6xl text-red-500 mb-4"></i>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Access Denied</h2>
                        <p class="text-gray-600 mb-6">You don't have permission to access the admin panel.</p>
                        <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md text-sm font-medium">
                            Return to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="min-h-screen flex items-center justify-center bg-gray-100">
            <div class="max-w-md w-full bg-white shadow-lg rounded-lg p-6">
                <div class="text-center">
                    <i class="fas fa-lock text-6xl text-red-500 mb-4"></i>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Authentication Required</h2>
                    <p class="text-gray-600 mb-6">Please log in to access the admin panel.</p>
                    <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md text-sm font-medium">
                        Go to Login
                    </a>
                </div>
            </div>
        </div>
    @endif

    @stack('scripts')
    
    <script>
        function toggleHomepageSubmenu() {
            const submenu = document.getElementById('homepage-submenu');
            submenu.classList.toggle('hidden');
        }
    </script>
</body>
</html>
