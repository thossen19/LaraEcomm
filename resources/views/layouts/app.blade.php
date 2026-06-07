<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $siteSettings['site_name']) - {{ $siteSettings['site_name'] }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Dynamic Favicon -->
    @if($siteSettings['site_favicon'])
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/settings/' . $siteSettings['site_favicon']) }}">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}">
    @endif
    
    <!-- Meta Description -->
    @if($siteSettings['site_description'])
        <meta name="description" content="{{ $siteSettings['site_description'] }}">
    @endif
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        @if($siteSettings['site_logo'])
                            <img src="{{ asset('storage/settings/' . $siteSettings['site_logo']) }}" 
                                 alt="{{ $siteSettings['site_name'] }}" 
                                 class="h-10 w-auto object-contain">
                        @else
                            <span class="text-2xl font-bold text-blue-600">
                                {{ $siteSettings['site_name'] }}
                            </span>
                        @endif
                    </a>
                </div>

                <!-- Navigation Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                        Products
                    </a>
                    <a href="{{ route('categories.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                        Categories
                    </a>
                    <a href="{{ route('about') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                        About
                    </a>
                    <a href="{{ route('contact') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                        Contact
                    </a>
                    <a href="{{ route('campaigns.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium flex items-center">
                        <i class="fas fa-bullhorn text-purple-500 mr-2"></i>
                        Campaigns
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="flex-1 max-w-md mx-8">
                    <div class="relative">
                        <input type="text" 
                               placeholder="Search products..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Right side buttons -->
                <div class="flex items-center space-x-4">
                    @guest
                        <!-- Login Button -->
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                            
                        </a>
                    @else
                        <!-- Cart -->
                        <a href="{{ route('cart.index') }}" class="relative text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-shopping-cart"></i>
                            <span id="cart-count" class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                0
                            </span>
                        </a>
                        
                        <!-- Wishlist -->
                        <a href="{{ route('customer.wishlist.index') }}" class="relative text-gray-700 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium wishlist-icon">
                            <i class="fas fa-heart"></i>
                            <span id="wishlist-count" class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" style="display: none;">
                                0
                            </span>
                        </a>
                        
                        <!-- User Menu -->
                        <div class="relative">
                            <button onclick="document.getElementById('user-menu').classList.toggle('hidden')" 
                                    class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-user"></i> {{ Auth::user()->first_name }}
                            </button>
                            
                            <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50">
                                <div class="py-1">
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                                    </a>
                                    <a href="{{ route('customer.orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-shopping-bag mr-2"></i> My Orders
                                    </a>
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user-edit mr-2"></i> Profile
                                    </a>
                                    <a href="{{ route('customer.addresses.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-map-marker-alt mr-2"></i> Addresses
                                    </a>
                                    <a href="{{ route('customer.wishlist.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-heart mr-2"></i> My Wishlist
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3">
                <div class="max-w-7xl mx-auto">
                    {{ session('success') }}
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3">
                <div class="max-w-7xl mx-auto">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">{{ $siteSettings['site_name'] }}</h3>
                    <p class="text-gray-300">Your trusted online shopping destination for quality products.</p>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('products.index') }}" class="text-gray-300 hover:text-white">Products</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Categories</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">About Us</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Customer Service</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white">Help Center</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Shipping Info</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Returns</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">FAQs</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-lg font-semibold mb-4">Connect</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-facebook text-xl"></i></a>
                        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-twitter text-xl"></i></a>
                        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-instagram text-xl"></i></a>
                        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-linkedin text-xl"></i></a>
                    </div>
                    <div class="mt-4">
                        <p class="text-gray-300">Subscribe to our newsletter</p>
                        <form class="mt-2 flex">
                            <input type="email" placeholder="Your email" 
                                   class="flex-1 px-3 py-2 rounded-l-md text-gray-900">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-r-md">
                                Subscribe
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} E-Commerce. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
    
    <!-- Cart count update script -->
    @auth
    <script>
        // Update cart count
        function updateCartCount() {
            fetch('{{ route("cart.count") }}')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const cartCountElement = document.getElementById('cart-count');
                    if (cartCountElement) {
                        cartCountElement.textContent = data.count || 0;
                    }
                })
                .catch(error => {
                    console.error('Error updating cart count:', error);
                    const cartCountElement = document.getElementById('cart-count');
                    if (cartCountElement) {
                        cartCountElement.textContent = '0';
                    }
                });
        }
        
        // Update cart count on page load
        document.addEventListener('DOMContentLoaded', updateCartCount);
        
        // Close user menu when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('user-menu');
            const userButton = event.target.closest('button[onclick*="user-menu"]');
            
            if (!userButton && !userMenu.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });
    </script>
    @endauth
    
    <!-- Wishlist functionality -->
    <script>
        // Wishlist functionality
        let wishlistItems = [];
        
        // Set authentication status from server
        const isLoggedIn = @json(Auth::check());
        console.log('=== AUTHENTICATION STATUS ===');
        console.log('User is logged in:', isLoggedIn);
        console.log('User ID:', @json(Auth::id()));
        console.log('==============================');

        function initializeWishlist() {
            console.log('Initializing wishlist...');
            
            // Don't initialize on wishlist page itself (it already has the data)
            if (window.location.pathname.includes('/customer/wishlist')) {
                console.log('On wishlist page, skipping AJAX initialization');
                return;
            }
            
            // Load wishlist from server first
            fetch('{{ route("customer.wishlist.index") }}', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                console.log('Content-Type:', response.headers.get('Content-Type'));
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const contentType = response.headers.get('Content-Type');
                if (contentType && contentType.includes('text/html')) {
                    console.log('Got HTML instead of JSON, this is a normal page load');
                    return null; // Exit gracefully for normal page loads
                }
                
                return response.json();
            })
            .then(data => {
                if (data && data.success && data.wishlist) {
                    console.log('Loaded wishlist from server:', data.wishlist);
                    wishlistItems = data.wishlist;
                    // Save to localStorage as backup
                    localStorage.setItem('wishlist', JSON.stringify(wishlistItems));
                } else if (data && data.wishlist) {
                    console.log('Server returned empty wishlist');
                    wishlistItems = data.wishlist;
                    localStorage.setItem('wishlist', JSON.stringify(wishlistItems));
                } else {
                    console.log('Server returned invalid response or error, using localStorage');
                    // Fallback to localStorage
                    const storedWishlist = localStorage.getItem('wishlist');
                    if (storedWishlist) {
                        wishlistItems = JSON.parse(storedWishlist);
                    }
                }
                
                // Update wishlist UI
                updateWishlistUI();
                updateWishlistCount();
            })
            .catch(error => {
                console.error('Error loading wishlist from server:', error);
                // Fallback to localStorage
                const storedWishlist = localStorage.getItem('wishlist');
                if (storedWishlist) {
                    wishlistItems = JSON.parse(storedWishlist);
                    updateWishlistUI();
                    updateWishlistCount();
                }
            });
        }

        function updateWishlistUI() {
            console.log('Updating wishlist UI...');
            console.log('Current wishlist items:', wishlistItems);
            
            // Update all wishlist buttons on the page
            const wishlistButtons = document.querySelectorAll('.wishlist-btn');
            console.log('Found wishlist buttons:', wishlistButtons.length);
            
            wishlistButtons.forEach(button => {
                const productId = button.dataset.productId;
                console.log('Checking button for product ID:', productId);
                
                if (productId && productId !== 'null') {
                    const isInWishlist = wishlistItems.some(item => item.id == productId);
                    console.log('Product', productId, 'is in wishlist:', isInWishlist);
                    
                    const heartIcon = button.querySelector('i');
                    if (isInWishlist) {
                        console.log('Updating to filled heart for product:', productId);
                        heartIcon.className = 'fas fa-heart text-sm';
                        button.classList.add('text-red-600');
                        button.classList.remove('text-red-500');
                    } else {
                        console.log('Updating to empty heart for product:', productId);
                        heartIcon.className = 'far fa-heart text-sm';
                        button.classList.remove('text-red-600');
                        button.classList.add('text-red-500');
                    }
                }
            });
            
            updateWishlistCount();
        }

        function toggleWishlist(button, productId) {
            console.log('toggleWishlist called with:', { productId, button });
            console.log('Checking authentication - isLoggedIn:', isLoggedIn);
            
            // Check if user is authenticated
            if (!isLoggedIn) {
                console.log('❌ User not authenticated, redirecting to login');
                showNotification('Please login to add products to wishlist', 'info');
                // Redirect to login page
                window.location.href = '{{ route("login") }}';
                return;
            }
            
            console.log('✅ User is authenticated, proceeding with wishlist operation');
            
            if (!productId || productId === 'null') {
                console.log('Invalid product ID:', productId);
                showNotification('Product information not available', 'error');
                return;
            }
            
            const productName = button.dataset.productName;
            const productImage = button.dataset.productImage;
            const productPrice = button.dataset.productPrice;
            
            console.log('Product data:', { productName, productImage, productPrice });
            
            const index = wishlistItems.findIndex(item => item.id == productId);
            
            if (index > -1) {
                // Remove from wishlist
                console.log('Removing from wishlist:', productId);
                wishlistItems.splice(index, 1);
                button.querySelector('i').className = 'far fa-heart text-sm';
                button.classList.remove('text-red-600');
                button.classList.add('text-red-500');
                showNotification(`${productName} removed from wishlist`, 'success');
                
                // Remove from server
                removeFromServerWishlist(productId);
            } else {
                // Add to wishlist
                console.log('Adding to wishlist:', productId);
                wishlistItems.push({
                    id: productId,
                    name: productName,
                    image: productImage,
                    price: productPrice,
                    added_at: new Date().toISOString()
                });
                button.querySelector('i').className = 'fas fa-heart text-sm';
                button.classList.add('text-red-600');
                button.classList.remove('text-red-500');
                showNotification(`${productName} added to wishlist`, 'success');
                
                // Add to server
                addToServerWishlist(productId, productName, productImage, productPrice);
            }
            
            // Save to localStorage
            localStorage.setItem('wishlist', JSON.stringify(wishlistItems));
            
            // Update UI
            updateWishlistUI();
            updateWishlistCount();
        }

        function addToServerWishlist(productId, productName, productImage, productPrice) {
            // Check if user is authenticated
            console.log('addToServerWishlist called - isLoggedIn:', isLoggedIn);
            
            if (!isLoggedIn) {
                console.log('❌ User not authenticated, cannot add to server wishlist');
                showNotification('Please login to add products to wishlist', 'info');
                return;
            }
            
            console.log('✅ User is authenticated, proceeding with server request');
            console.log('Adding to server wishlist:', { productId, productName, productImage, productPrice });
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            console.log('CSRF Token:', csrfToken ? 'Found' : 'Not found');
            
            const requestData = {
                product_id: productId,
                quantity: 1,
                notes: ''
            };
            console.log('Request data:', requestData);
            
            // Test the route first
            const testUrl = '{{ route("customer.wishlist.add") }}';
            console.log('Request URL:', testUrl);
            
            fetch(testUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(requestData)
            })
            .then(response => {
                console.log('=== RESPONSE RECEIVED ===');
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                console.log('Response type:', response.type);
                
                if (response.status === 409) {
                    // Handle 409 Conflict (item already exists)
                    return response.json().then(data => {
                        console.log('Item already in wishlist:', data.message);
                        showNotification('Item is already in your wishlist', 'info');
                        return { success: false, handled: true };
                    });
                }
                
                if (!response.ok) {
                    console.error('HTTP error! status:', response.status);
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                return response.json();
            })
            .then(data => {
                console.log('=== RESPONSE DATA ===');
                console.log('Response data:', data);
                
                // Skip if already handled (409 case)
                if (data && data.handled) {
                    console.log('Request already handled (409 case)');
                    return;
                }
                
                if (data && data.success) {
                    console.log('✅ SUCCESS: Added to server wishlist:', data.message);
                    console.log('✅ New wishlist count:', data.wishlist_count);
                    console.log('✅ User ID:', data.user_id);
                    console.log('✅ DB ID:', data.db_id);

                    // Update local wishlistItems with the database ID for future removals
                    if (data.db_id) {
                        const localIndex = wishlistItems.findIndex(item => item.id == productId);
                        if (localIndex > -1) {
                            wishlistItems[localIndex].db_id = data.db_id;
                            localStorage.setItem('wishlist', JSON.stringify(wishlistItems));
                        }
                    }
                } else if (data && data.message && data.message.includes('already in your wishlist')) {
                    console.log('ℹ️ INFO: Item already in wishlist');
                    showNotification('Item is already in your wishlist', 'info');
                } else {
                    console.error('❌ ERROR: Failed to add to server wishlist:', data.message);
                    if (data.errors) {
                        console.error('❌ Validation errors:', data.errors);
                    }
                    showNotification(data.message || 'Failed to add to wishlist', 'error');
                }
                console.log('=== ADD TO SERVER WISHLIST END ===');
            })
            .catch(error => {
                console.error('=== CATCH ERROR ===');
                console.error('❌ Error adding to server wishlist:', error);
                console.error('❌ Error details:', error.message);
                console.log('=== ADD TO SERVER WISHLIST END ===');
            });
        }

        function removeFromServerWishlist(productId) {
            // Check if user is authenticated
            console.log('removeFromServerWishlist called - isLoggedIn:', isLoggedIn);
            
            if (!isLoggedIn) {
                console.log('❌ User not authenticated, cannot remove from server wishlist');
                showNotification('Please login to manage your wishlist', 'info');
                return;
            }
            
            console.log('✅ User is authenticated, proceeding with removal');
            
            // Find the wishlist item to get its database ID
            const wishlistItem = wishlistItems.find(item => item.id == productId);
            if (!wishlistItem) return;
            
            // Check if we have the database ID for this item
            const itemId = wishlistItem.db_id || wishlistItem.id;
            if (!itemId) {
                console.error('No database ID found for wishlist item');
                return;
            }
            
            // Build URL dynamically without route helper
            const removeUrl = `{{ url('/customer/wishlist') }}/${itemId}`;
            
            fetch(removeUrl, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Removed from server wishlist:', data.message);
                } else {
                    console.error('Failed to remove from server wishlist:', data.message);
                }
            })
            .catch(error => {
                console.error('Error removing from server wishlist:', error);
            });
        }

        
        function updateWishlistCount() {
            const count = wishlistItems.length;
            
            // Update wishlist count displays
            const countElements = document.querySelectorAll('[data-wishlist-count]');
            countElements.forEach(element => {
                element.textContent = count;
            });
            
            // Update header wishlist icon
            const headerWishlistIcon = document.querySelector('.wishlist-icon');
            if (headerWishlistIcon) {
                const badge = headerWishlistIcon.querySelector('#wishlist-count');
                if (badge) {
                    badge.textContent = count;
                    badge.style.display = count > 0 ? 'flex' : 'none';
                }
            }
            
            // Also try to get the server count for accuracy
            fetch('{{ route("customer.wishlist.count") }}', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.count !== undefined) {
                    console.log('Server wishlist count:', data.count);
                    // Update with server count if different
                    if (data.count !== count) {
                        const serverBadge = headerWishlistIcon.querySelector('#wishlist-count');
                        if (serverBadge) {
                            serverBadge.textContent = data.count;
                            serverBadge.style.display = data.count > 0 ? 'flex' : 'none';
                        }
                    }
                }
            })
            .catch(error => {
                console.log('Could not fetch server wishlist count:', error);
            });
        }

        function getWishlistItems() {
            return wishlistItems;
        }

        function clearWishlist() {
            if (confirm('Are you sure you want to clear your entire wishlist?')) {
                wishlistItems = [];
                localStorage.setItem('wishlist', JSON.stringify(wishlistItems));
                updateWishlistUI();
                showNotification('Wishlist cleared', 'success');
            }
        }

        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full`;
            
            // Set color based on type
            if (type === 'success') {
                notification.classList.add('bg-green-500', 'text-white');
            } else if (type === 'error') {
                notification.classList.add('bg-red-500', 'text-white');
            } else {
                notification.classList.add('bg-blue-500', 'text-white');
            }
            
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'} mr-2"></i>
                    <span>${message}</span>
                </div>
            `;
            
            // Add to page
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
                notification.classList.add('translate-x-0');
            }, 100);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.classList.remove('translate-x-0');
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Load real wishlist data from server
        function loadRealWishlistData() {
            console.log('Loading real wishlist data...');
            
            // Only load if user is authenticated and not on wishlist page
            @auth
                if (!window.location.pathname.includes('/customer/wishlist')) {
                    fetch('{{ route("customer.wishlist.index") }}', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        const contentType = response.headers.get('Content-Type');
                        
                        if (contentType && contentType.includes('text/html')) {
                            console.log('Got HTML response, this is normal for direct page loads');
                            return null;
                        }
                        
                        return response.json();
                    })
                    .then(data => {
                        if (data && data.success && data.wishlist) {
                            console.log('Loaded real wishlist data:', data.wishlist);
                            wishlistItems = data.wishlist;
                            localStorage.setItem('wishlist', JSON.stringify(wishlistItems));
                            updateWishlistUI();
                            updateWishlistCount();
                        } else if (data && data.wishlist) {
                            console.log('Empty wishlist loaded');
                            wishlistItems = data.wishlist;
                            localStorage.setItem('wishlist', JSON.stringify(wishlistItems));
                            updateWishlistUI();
                            updateWishlistCount();
                        }
                    })
                    .catch(error => {
                        console.error('Error loading real wishlist data:', error);
                        // Fallback to localStorage
                        const storedWishlist = localStorage.getItem('wishlist');
                        if (storedWishlist) {
                            wishlistItems = JSON.parse(storedWishlist);
                            updateWishlistUI();
                            updateWishlistCount();
                        }
                    });
                }
            @endauth
        }

        // Initialize wishlist on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Page loaded, initializing wishlist...');
            
            // Load real wishlist data from server
            @auth
                if (!window.location.pathname.includes('/customer/wishlist')) {
                    fetch('{{ route("customer.wishlist.index") }}', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        const contentType = response.headers.get('Content-Type');
                        
                        if (contentType && contentType.includes('text/html')) {
                            console.log('Got HTML response, this is normal for direct page loads');
                            return null;
                        }
                        
                        return response.json();
                    })
                    .then(data => {
                        if (data && data.success && data.wishlist) {
                            console.log('Loaded real wishlist data:', data.wishlist);
                            wishlistItems = data.wishlist;
                            localStorage.setItem('wishlist', JSON.stringify(wishlistItems));
                            updateWishlistUI();
                            updateWishlistCount();
                        } else if (data && data.wishlist) {
                            console.log('Empty wishlist loaded');
                            wishlistItems = data.wishlist;
                            localStorage.setItem('wishlist', JSON.stringify(wishlistItems));
                            updateWishlistUI();
                            updateWishlistCount();
                        }
                    })
                    .catch(error => {
                        console.error('Error loading real wishlist data:', error);
                        // Fallback to localStorage
                        const storedWishlist = localStorage.getItem('wishlist');
                        if (storedWishlist) {
                            wishlistItems = JSON.parse(storedWishlist);
                            updateWishlistUI();
                            updateWishlistCount();
                        }
                    });
                } else {
                    console.log('On wishlist page, skipping AJAX initialization');
                }
            @endauth
        });
    </script>
    
    <!-- AI Chat Widget - Only on homepage -->
    @if(request()->route()->getName() === 'home')
    <style>
    /* AI Chat Widget Styles */
    .ai-chat-widget {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
    }

    .ai-chat-button {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        border: none;
        color: white;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .ai-chat-button:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 25px rgba(59, 130, 246, 0.6);
    }

    .ai-chat-button.pulse {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
        100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
    }

    .ai-chat-container {
        position: absolute;
        bottom: 80px;
        right: 0;
        width: 380px;
        height: 600px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        display: none;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }

    .ai-chat-header {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
    }

    .ai-chat-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
    }

    .ai-chat-status {
        display: flex;
        align-items: center;
        font-size: 12px;
        margin-top: 4px;
        opacity: 0.9;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
        margin-right: 6px;
        animation: blink 2s infinite;
    }

    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }

    .ai-chat-close {
        background: none;
        border: none;
        color: white;
        font-size: 20px;
        cursor: pointer;
        padding: 8px;
        border-radius: 50%;
        transition: background 0.3s ease;
    }

    .ai-chat-close:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .ai-chat-messages {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background: #f9fafb;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .ai-message {
        display: flex;
        animation: fadeInUp 0.3s ease;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .ai-message.user {
        justify-content: flex-end;
    }

    .ai-message.bot {
        justify-content: flex-start;
    }

    .message-content {
        max-width: 75%;
        padding: 12px 16px;
        border-radius: 18px;
        font-size: 14px;
        line-height: 1.5;
        word-wrap: break-word;
    }

    .ai-message.user .message-content {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        border-bottom-right-radius: 6px;
    }

    .ai-message.bot .message-content {
        background: white;
        color: #374151;
        border-bottom-left-radius: 6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .message-time {
        font-size: 11px;
        color: #9ca3af;
        margin-top: 4px;
    }

    .ai-message.user .message-time {
        text-align: right;
    }

    .typing-indicator {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        background: white;
        border-radius: 18px;
        border-bottom-left-radius: 6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        max-width: 75%;
    }

    .typing-dots {
        display: flex;
        gap: 4px;
    }

    .typing-dot {
        width: 8px;
        height: 8px;
        background: #9ca3af;
        border-radius: 50%;
        animation: typing 1.4s infinite;
    }

    .typing-dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-dot:nth-child(3) { animation-delay: 0.4s; }

    @keyframes typing {
        0%, 60%, 100% { transform: translateY(0); }
        30% { transform: translateY(-10px); }
    }

    .ai-chat-input {
        padding: 20px;
        background: white;
        border-top: 1px solid #e5e7eb;
        flex-shrink: 0;
    }

    .input-container {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .ai-input-field {
        flex: 1;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 25px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.3s ease;
    }

    .ai-input-field:focus {
        border-color: #3b82f6;
    }

    .ai-send-button {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        border: none;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .ai-send-button:hover:not(:disabled) {
        transform: scale(1.05);
    }

    .ai-send-button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .quick-actions {
        padding: 16px 20px;
        background: #f8fafc;
        border-top: 1px solid #e5e7eb;
        flex-shrink: 0;
    }

    .quick-actions-title {
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .quick-action-buttons {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    .quick-action-btn {
        padding: 8px 12px;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 12px;
        color: #374151;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }

    .quick-action-btn:hover {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
        transform: translateY(-1px);
    }

    @media (max-width: 640px) {
        .ai-chat-container {
            width: calc(100vw - 32px);
            right: -16px;
            height: 500px;
        }
        
        .ai-chat-widget {
            bottom: 16px;
            right: 16px;
        }

        .ai-chat-button {
            width: 50px;
            height: 50px;
            font-size: 20px;
        }

        .quick-action-buttons {
            grid-template-columns: 1fr;
        }
    }
    </style>
    
    <div class="ai-chat-widget">
        <button class="ai-chat-button pulse" id="aiChatButton" onclick="toggleAIChat()">
            <i class="fas fa-robot"></i>
        </button>
        
        <div class="ai-chat-container" id="aiChatContainer">
            <div class="ai-chat-header">
                <div>
                    <h3>AI Shopping Assistant</h3>
                    <div class="ai-chat-status">
                        <span class="status-dot"></span>
                        <span>Always here to help</span>
                    </div>
                </div>
                <button class="ai-chat-close" onclick="toggleAIChat()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="ai-chat-messages" id="aiChatMessages">
                <div class="ai-message bot">
                    <div class="message-content">
                        👋 Hello! I'm your AI shopping assistant. I can help you find products, track orders, answer questions about returns, and provide personalized recommendations. How can I assist you today?
                    </div>
                    <div class="message-time">Just now</div>
                </div>
            </div>
            
            <div class="quick-actions">
                <div class="quick-actions-title">Quick Actions</div>
                <div class="quick-action-buttons">
                    <button class="quick-action-btn" onclick="sendQuickMessage('Show me today\'s deals')">🎉 Today's Deals</button>
                    <button class="quick-action-btn" onclick="sendQuickMessage('Track my order')">📦 Track Order</button>
                    <button class="quick-action-btn" onclick="sendQuickMessage('Product recommendations')">🛍️ Recommendations</button>
                    <button class="quick-action-btn" onclick="sendQuickMessage('Return policy')">🔄 Return Policy</button>
                </div>
            </div>
            
            <div class="ai-chat-input">
                <div class="input-container">
                    <input type="text" class="ai-input-field" id="aiChatInput" placeholder="Type your message..." onkeypress="handleKeyPress(event)">
                    <button class="ai-send-button" id="aiSendButton" onclick="sendMessage()">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    // AI Chatbot Functionality
    let isChatOpen = false;
    let isTyping = false;
    let messageHistory = [];

    const aiResponses = {
        greetings: [
            "Hello! 👋 How can I help you with your shopping today?",
            "Hi there! 😊 I'm here to assist with your shopping needs.",
            "Welcome! 🛍️ What can I help you find today?"
        ],
        deals: [
            "🎉 Today's hot deals:\n• Electronics: Up to 70% off\n• Fashion: Starting at ₹299\n• Home & Living: Minimum 40% off\n• Mobiles: No-cost EMI available\n\nWould you like me to show you specific products?",
            "🔥 Flash deals active now:\n• Wireless Earbuds: 65% off (₹899)\n• Smart Watch: 45% off (₹2,799)\n• Laptop Stand: 60% off (₹399)\n\nThese deals end in 2 hours!",
            "💰 Special offers just for you:\n• First-time buyer: 10% extra off\n• Free shipping on orders above ₹999\n• Buy 2 get 1 free on selected items\n\nWant to explore these deals?"
        ],
        order: [
            "📦 To track your order, I'll need your order ID. You can find it in:\n• Email confirmation\n• SMS notification\n• Your account dashboard\n\nPlease share your order ID and I'll help you track it.",
            "🚚 Order tracking help:\n1. Check your email for order confirmation\n2. Find the 6-digit order ID\n3. Share it with me here\n4. I'll provide real-time status\n\nNeed help finding your order ID?",
            "📋 Order status updates:\n• Processing: 1-2 business days\n• Shipped: 3-5 business days\n• Delivered: You'll get notification\n\nWhat's your order ID?"
        ],
        recommendations: [
            "🛍️ Personalized recommendations for you:\n\nBased on trending items:\n• Tech: Wireless chargers, Phone cases\n• Fashion: Casual shirts, Jeans\n• Home: Kitchen organizers, Decor\n\nWhat category interests you most?",
            "🎯 Top picks this week:\n\nElectronics:\n• Bluetooth speakers (₹899)\n• Power banks (₹699)\n\nFashion:\n• Summer collection (₹499)\n• Sports shoes (₹1,299)\n\nWant details on any of these?",
            "⭐ Customer favorites:\n\nMost loved products:\n• Smart watches (4.8⭐)\n• Yoga mats (4.9⭐)\n• Water bottles (4.7⭐)\n\nI can help you find similar items. What's your budget?"
        ],
        returns: [
            "🔄 Easy return policy:\n\n• 30-day return window\n• Items must be unused with tags\n• Free returns for most items\n• Refund in 5-7 business days\n\nNeed help with a specific return?",
            "📦 Return process:\n1. Go to 'My Orders'\n2. Click 'Return Item'\n3. Select return reason\n4. Schedule pickup\n5. Get refund\n\nI can guide you through this step by step!",
            "💡 Return tips:\n• Keep original packaging\n• Don't remove tags\n• Click photos before returning\n• Use our pickup service (free)\n• Track return status online\n\nWhat item do you want to return?"
        ],
        shipping: [
            "🚚 Shipping options:\n\nStandard: 5-7 days (FREE above ₹999)\nExpress: 2-3 days (₹99)\nSame Day: Select cities (₹149)\n\nWant to check delivery time for your location?",
            "📍 Delivery information:\n\n• Pan India delivery\n• Real-time tracking\n• SMS updates\n• Safe packaging\n• Contactless delivery available\n\nWhat's your pincode? I'll check exact delivery time.",
            "⏰ Quick delivery:\n\nMetro cities: 1-2 days\nTier 2 cities: 2-4 days\nOther locations: 4-7 days\n\nInternational: 10-15 days\n\nWhere do you want delivery?"
        ],
        payment: [
            "💳 Secure payment options:\n\n• Credit/Debit Cards\n• UPI (GPay, PhonePe, Paytm)\n• Net Banking\n• Cash on Delivery\n• EMI (3, 6, 9, 12 months)\n• Wallets (Paytm, Amazon Pay)\n\nNeed help with payment?",
            "🔒 Safe payments:\n\n• 256-bit encryption\n• PCI DSS compliant\n• 2-factor authentication\n• Fraud protection\n• Easy refunds\n\nYour payment security is our priority!",
            "💰 Payment help:\n\n• Failed payment? Try again\n• EMI starting at ₹99/month\n• No-cost EMI on select cards\n• Wallet cashback available\n\nWhat payment issue are you facing?"
        ],
        contact: [
            "📞 How to reach us:\n\n• Phone: 1800-123-4567 (9 AM - 9 PM)\n• Email: support@ecommerce.com\n• WhatsApp: +91 98765-43210\n• Live Chat: Available 24/7\n\nWhat's the best way to help you?",
            "🆘 Need urgent help?\n\n• WhatsApp: Instant replies\n• Live Chat: 24/7 available\n• Emergency line: 1800-999-0000\n• Social media: @ecommerce_help\n\nI'm here to solve your problem now!"
        ],
        help: [
            "🤝 How I can help:\n\n✅ Find products\n✅ Compare prices\n✅ Track orders\n✅ Process returns\n✅ Payment assistance\n✅ Size recommendations\n✅ Gift suggestions\n\nWhat do you need help with?",
            "🔍 Search assistance:\n\n• Describe the product\n• Mention your budget\n• Share preferences (color, size)\n• I'll find best matches\n\nTry: 'I need a blue shirt under ₹1000'"
        ],
        default: [
            "I'm here to help! 😊 Could you tell me more about what you're looking for? You can ask about products, orders, returns, or general shopping help.",
            "Let me assist you better! 🎯 Try asking about:\n• Product recommendations\n• Order tracking\n• Return policy\n• Today's deals\n• Payment options\n\nWhat would you like to know?",
            "I understand your question! 💭 Could you please provide more details or try one of the quick action buttons below? I'm here to make your shopping experience better!"
        ]
    };

    function toggleAIChat() {
        const chatContainer = document.getElementById('aiChatContainer');
        const chatButton = document.getElementById('aiChatButton');
        
        if (!chatContainer || !chatButton) return;
        
        isChatOpen = !isChatOpen;
        
        if (isChatOpen) {
            chatContainer.style.display = 'flex';
            chatButton.classList.remove('pulse');
            setTimeout(() => {
                const inputField = document.getElementById('aiChatInput');
                if (inputField) inputField.focus();
            }, 300);
        } else {
            chatContainer.style.display = 'none';
        }
    }

    function sendMessage() {
        const input = document.getElementById('aiChatInput');
        const message = input.value.trim();
        
        if (message === '' || isTyping) return;
        
        // Add user message
        addMessage(message, 'user');
        input.value = '';
        
        // Show typing indicator
        showTypingIndicator();
        
        // Generate AI response
        setTimeout(() => {
            hideTypingIndicator();
            const response = generateAIResponse(message);
            addMessage(response, 'bot');
        }, 800 + Math.random() * 1200);
    }

    function sendQuickMessage(message) {
        const input = document.getElementById('aiChatInput');
        input.value = message;
        sendMessage();
    }

    function addMessage(message, sender) {
        const messagesContainer = document.getElementById('aiChatMessages');
        const messageDiv = document.createElement('div');
        messageDiv.className = `ai-message ${sender}`;
        
        const timestamp = new Date().toLocaleTimeString('en-US', { 
            hour: 'numeric', 
            minute: '2-digit',
            hour12: true 
        });
        
        messageDiv.innerHTML = `
            <div class="message-content">${escapeHtml(message)}</div>
            <div class="message-time">${timestamp}</div>
        `;
        
        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
        
        // Store in history
        messageHistory.push({ message, sender, timestamp });
    }

    function showTypingIndicator() {
        isTyping = true;
        const messagesContainer = document.getElementById('aiChatMessages');
        
        const typingDiv = document.createElement('div');
        typingDiv.className = 'ai-message bot';
        typingDiv.id = 'typingIndicator';
        typingDiv.innerHTML = `
            <div class="typing-indicator">
                <div class="typing-dots">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            </div>
        `;
        
        messagesContainer.appendChild(typingDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function hideTypingIndicator() {
        isTyping = false;
        const typingIndicator = document.getElementById('typingIndicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }

    function generateAIResponse(userMessage) {
        const message = userMessage.toLowerCase();
        
        // Check for specific keywords
        if (message.includes('hello') || message.includes('hi') || message.includes('hey')) {
            return getRandomResponse('greetings');
        } else if (message.includes('deal') || message.includes('offer') || message.includes('discount') || message.includes('sale')) {
            return getRandomResponse('deals');
        } else if (message.includes('order') || message.includes('track') || message.includes('delivery') || message.includes('shipment')) {
            return getRandomResponse('order');
        } else if (message.includes('recommend') || message.includes('suggest') || message.includes('what should') || message.includes('show me')) {
            return getRandomResponse('recommendations');
        } else if (message.includes('return') || message.includes('refund') || message.includes('exchange') || message.includes('money back')) {
            return getRandomResponse('returns');
        } else if (message.includes('shipping') || message.includes('delivery') || message.includes('how long') || message.includes('when will')) {
            return getRandomResponse('shipping');
        } else if (message.includes('payment') || message.includes('pay') || message.includes('card') || message.includes('emi')) {
            return getRandomResponse('payment');
        } else if (message.includes('contact') || message.includes('help') || message.includes('support') || message.includes('call')) {
            return getRandomResponse('contact');
        } else if (message.includes('help') || message.includes('how to') || message.includes('can you')) {
            return getRandomResponse('help');
        } else {
            return getRandomResponse('default');
        }
    }

    function getRandomResponse(category) {
        const responses = aiResponses[category];
        return responses[Math.floor(Math.random() * responses.length)];
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function handleKeyPress(event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            sendMessage();
        }
    }

    // Initialize chat
    document.addEventListener('DOMContentLoaded', () => {
        // Show pulse animation after 3 seconds
        setTimeout(() => {
            const chatButton = document.getElementById('aiChatButton');
            if (chatButton && !isChatOpen) {
                chatButton.classList.add('pulse');
            }
        }, 3000);
        
        // Add keyboard shortcut (Ctrl/Cmd + K)
        document.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                toggleAIChat();
            }
        });
    });
    </script>
    @endif
    
    <!-- 24-Hour Timer for Deals of the Day -->
    <script>
    function startDealTimer() {
        const timerElement = document.getElementById('deal-timer');
        if (!timerElement) return;
        
        function updateTimer() {
            const now = new Date();
            const tomorrow = new Date(now);
            tomorrow.setHours(24, 0, 0, 0); // Set to midnight
            
            const timeDiff = tomorrow - now;
            
            if (timeDiff <= 0) {
                // Reset to next day
                tomorrow.setDate(tomorrow.getDate() + 1);
            }
            
            const hours = Math.floor(timeDiff / (1000 * 60 * 60));
            const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);
            
            const formattedTime = 
                String(hours).padStart(2, '0') + ':' +
                String(minutes).padStart(2, '0') + ':' +
                String(seconds).padStart(2, '0');
            
            timerElement.textContent = formattedTime;
        }
        
        // Update immediately
        updateTimer();
        
        // Update every second
        setInterval(updateTimer, 1000);
    }
    
    // Start timer when page loads
    document.addEventListener('DOMContentLoaded', startDealTimer);
    </script>
</body>
</html>
