@extends('layouts.app')

@push('styles')
<style>
/* Mobile Navigation Toggle */
.mobile-nav-toggle {
    display: none;
    background: none;
    border: none;
    cursor: pointer;
    padding: 8px;
    border-radius: 8px;
    transition: all 0.3s ease;
    z-index: 1003;
    position: relative;
    pointer-events: auto !important;
}

.mobile-nav-toggle:hover {
    background: rgba(52, 152, 219, 0.1);
}

.mobile-nav-toggle span {
    display: block;
    width: 25px;
    height: 3px;
    background: #2c3e50;
    margin: 5px 0;
    transition: all 0.3s ease;
    border-radius: 2px;
}

.mobile-nav-toggle.active span:nth-child(1) {
    transform: rotate(45deg) translate(5px, 5px);
}

.mobile-nav-toggle.active span:nth-child(2) {
    opacity: 0;
}

.mobile-nav-toggle.active span:nth-child(3) {
    transform: rotate(-45deg) translate(7px, -6px);
}

/* Mobile Navigation Menu */
.mobile-nav-menu {
    display: none;
    position: fixed;
    top: 0;
    left: -100%;
    width: 80%;
    max-width: 300px;
    height: 100vh;
    background: white;
    box-shadow: 2px 0 20px rgba(0,0,0,0.1);
    z-index: 1002;
    transition: left 0.3s ease;
    overflow-y: auto;
    pointer-events: none;
}

.mobile-nav-menu.active {
    left: 0;
    display: block !important;
    pointer-events: auto;
}

/* Ensure all mobile navigation interactive elements are clickable */
.mobile-nav-menu *,
.mobile-nav-menu button,
.mobile-nav-menu a,
.mobile-nav-menu input {
    pointer-events: auto !important;
}

.mobile-nav-header {
    background: #2c3e50;
    color: white;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.mobile-nav-header h3 {
    margin: 0;
    font-size: 18px;
}

.mobile-nav-close {
    background: none;
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    padding: 5px;
    border-radius: 4px;
    transition: background 0.3s ease;
    pointer-events: auto !important;
}

.mobile-nav-close:hover {
    background: rgba(255,255,255,0.1);
}

.mobile-nav-content {
    padding: 20px;
}

.mobile-nav-section {
    margin-bottom: 30px;
}

.mobile-nav-section h4 {
    margin-bottom: 15px;
    color: #2c3e50;
    font-size: 16px;
    font-weight: 600;
}

.mobile-nav-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.mobile-nav-links li {
    margin-bottom: 10px;
}

.mobile-nav-links a {
    display: block;
    padding: 12px 15px;
    color: #2c3e50;
    text-decoration: none;
    border-radius: 6px;
    transition: all 0.3s ease;
    font-size: 15px;
    pointer-events: auto !important;
}

.mobile-nav-links a:hover {
    background: rgba(52, 152, 219, 0.1);
    color: #3498db;
    transform: translateX(5px);
}

.mobile-nav-actions {
    padding: 20px;
}

.mobile-search-bar {
    margin-bottom: 15px;
}

.mobile-search-bar input {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    background: #f8f9fa;
    pointer-events: auto !important;
}

.mobile-action-buttons {
    display: flex;
    gap: 10px;
}

.mobile-action-buttons button,
.mobile-action-buttons a {
    flex: 1;
    padding: 12px 15px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    text-align: center;
    pointer-events: auto !important;
}

.mobile-cart-btn {
    background: #3498db;
    color: white;
}

.mobile-cart-btn:hover {
    background: #2980b9;
}

.mobile-wishlist-btn {
    background: #e74c3c;
    color: white;
}

.mobile-wishlist-btn:hover {
    background: #229954;
}

/* Mobile Overlay */
.mobile-nav-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    background: rgba(0,0,0,0.5);
    z-index: 1001;
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
}

.mobile-nav-overlay.active {
    display: block;
    opacity: 1;
    pointer-events: auto;
}

/* Ensure all interactive elements are clickable */
button, a, input, select, .product-card, .container {
    pointer-events: auto !important;
}

/* Product cards enhanced for mobile */
.product-card {
    pointer-events: auto !important;
    cursor: pointer !important;
    -webkit-tap-highlight-color: transparent;
}

.product-card:hover,
.product-card:focus,
.product-card:active {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    outline: none;
}

/* Enhanced button styles for mobile */
button {
    pointer-events: auto !important;
    cursor: pointer !important;
    -webkit-tap-highlight-color: transparent;
    touch-action: manipulation;
}

button:hover,
button:focus,
button:active {
    outline: none;
    transform: scale(1.02);
}

/* Main content area */
.container {
    pointer-events: auto !important;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .mobile-nav-toggle {
        display: block !important;
    }
    
    .mobile-nav-menu {
        display: none !important;
    }
    
    .mobile-nav-menu.active {
        display: block !important;
    }
    
    .mobile-nav-overlay {
        display: none !important;
    }
    
    .mobile-nav-overlay.active {
        display: block !important;
    }
    
    .main-nav {
        display: none !important;
    }
}

@media (max-width: 768px) {
    .main-nav {
        display: none !important;
    }
    
    .mobile-nav-toggle {
        display: block !important;
    }
    
    .mobile-nav-menu {
        display: none !important;
    }
    
    .mobile-nav-menu.active {
        display: block !important;
    }
    
    .mobile-nav-overlay {
        display: none !important;
    }
    
    .mobile-nav-overlay.active {
        display: block !important;
    }
    
    .header-content {
        flex-wrap: wrap;
        gap: 15px;
        justify-content: space-between;
        padding: 15px 0;
    }
    
    .header-actions {
        order: 3;
        width: 100%;
        justify-content: center;
        gap: 15px;
    }
    
    .search-bar {
        display: none;
    }
    
    .cart-button, .wishlist-button {
        padding: 8px 12px;
        font-size: 13px;
    }
    
    .container > div:first-child {
        padding: 20px !important;
    }
    
    .container > div:first-child > div {
        flex-direction: column !important;
        text-align: center;
        gap: 20px !important;
    }
    
    .container > div:nth-child(2) {
        grid-template-columns: 1fr !important;
        gap: 20px !important;
    }
}

@media (max-width: 480px) {
    .mobile-nav-toggle {
        display: block !important;
    }
    
    .mobile-nav-menu {
        display: none !important;
        width: 85%;
        max-width: 280px;
    }
    
    .mobile-nav-menu.active {
        display: block !important;
    }
    
    .mobile-nav-overlay {
        display: none !important;
    }
    
    .mobile-nav-overlay.active {
        display: block !important;
    }
    
    .header-content {
        padding: 10px 0;
    }
    
    .cart-button, .wishlist-button {
        padding: 6px 10px;
        font-size: 13px;
    }
    
    .container {
        padding: 10px !important;
    }
    
    .container > div:first-child {
        padding: 15px !important;
    }
    
    .container > div:nth-child(2) {
        grid-template-columns: 1fr !important;
        gap: 15px !important;
    }
}
</style>
@endpush

@section('content')
<!-- Mobile Navigation Menu -->
<div class="mobile-nav-menu" id="mobileNavMenu">
    <div class="mobile-nav-header">
        <h3>Menu</h3>
        <button class="mobile-nav-close" id="mobileNavClose" aria-label="Close navigation">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <div class="mobile-nav-content">
        <div class="mobile-nav-section">
            <h4>Main Navigation</h4>
            <ul class="mobile-nav-links">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('flash-sale.index') }}">Flash Sale</a></li>
                <li><a href="{{ route('products.index') }}">Products</a></li>
                <li><a href="{{ route('categories.index') }}">Categories</a></li>
                <li><a href="{{ route('sellers.index') }}">Sellers</a></li>
            </ul>
        </div>
        
        <div class="mobile-nav-actions">
            <div class="mobile-search-bar">
                <input type="text" placeholder="Search products...">
            </div>
            <div class="mobile-action-buttons">
                <a href="{{ route('cart.show') }}" class="mobile-cart-btn">
                    <i class="fas fa-shopping-cart"></i> Cart
                </a>
                <a href="{{ route('wishlist.index') }}" class="mobile-wishlist-btn">
                    <i class="fas fa-heart"></i> Wishlist
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Navigation Overlay -->
<div class="mobile-nav-overlay" id="mobileNavOverlay"></div>

<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <div style="background: white; border-radius: 12px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); margin-bottom: 30px;">
        <div style="display: flex; align-items: center; gap: 25px;">
            <div style="width: 120px; height: 120px; border-radius: 50%; background: #f8f9fa; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                @if($seller->image)
                    <img src="{{ asset('storage/' . $seller->image) }}" alt="{{ $seller->name }}" 
                         style="width: 100%; height: 100%; object-fit: cover;"
                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($seller->name) }}&background=3498db&color=fff';">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($seller->name) }}&background=3498db&color=fff" alt="{{ $seller->name }}" 
                         style="width: 100%; height: 100%; object-fit: cover;">
                @endif
            </div>
            
            <div style="flex: 1;">
                <h1 style="font-size: 2rem; font-weight: 700; color: #2c3e50; margin-bottom: 8px;">{{ $seller->name }}</h1>
                <p style="color: #7f8c8d; margin-bottom: 15px;">{{ $seller->email }}</p>
                
                @if($seller->shop)
                    <div style="margin-bottom: 15px;">
                        <span style="background: #e1f0fa; color: #3498db; padding: 8px 15px; border-radius: 20px; font-weight: 500; display: inline-block;">
                            <i class="fas fa-store"></i> {{ $seller->shop->name ?? 'Shop' }}
                        </span>
                    </div>
                @endif
                
                <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 8px; background: #f8f9fa; padding: 8px 15px; border-radius: 6px;">
                        <i class="fas fa-box" style="color: #3498db;"></i>
                        <span style="font-weight: 600; color: #2c3e50;">{{ $seller->products_count ?? 0 }} Products</span>
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 8px; background: #f8f9fa; padding: 8px 15px; border-radius: 6px;">
                        <i class="fas fa-shopping-bag" style="color: #3498db;"></i>
                        <span style="font-weight: 600; color: #2c3e50;">{{ $seller->orders_count ?? 0 }} Orders</span>
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 8px; background: #f8f9fa; padding: 8px 15px; border-radius: 6px;">
                        <i class="fas fa-star" style="color: #f39c12;"></i>
                        <span style="font-weight: 600; color: #2c3e50;">{{ number_format($seller->rating ?? 0, 1) }} Rating</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 25px;">
        @if($products && $products->count() > 0)
            @foreach($products as $product)
                <div class="product-card" style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); transition: all 0.3s ease; text-decoration: none; color: inherit; display: block; border: 1px solid #f1f2f6;">
                    <div class="product-image" style="position: relative; height: 200px; overflow: hidden; background: #f8f9fa;">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                 style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;"
                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/250x200/f8f9fa/7f8c8d?text=No+Image';">
                        @else
                            <img src="https://via.placeholder.com/250x200/f8f9fa/7f8c8d?text=No+Image" alt="{{ $product->name }}" 
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        @endif
                        @if($product->compare_price && $product->compare_price > $product->price)
                            <span style="position: absolute; top: 10px; right: 10px; background: #e74c3c; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                -{{ round((1 - $product->price / $product->compare_price) * 100) }}%
                            </span>
                        @endif
                    </div>
                    <div style="padding: 20px;">
                        <h3 style="font-weight: 600; margin-bottom: 10px; color: #2c3e50;">{{ $product->name }}</h3>
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                            <span style="font-size: 1.2rem; font-weight: 700; color: #3498db;">${{ number_format($product->price, 2) }}</span>
                            @if($product->compare_price && $product->compare_price > $product->price)
                                <span style="color: #95a5a6; text-decoration: line-through;">${{ number_format($product->compare_price, 2) }}</span>
                            @endif
                        </div>
                        <div style="display: flex; gap: 8px; margin-bottom: 15px;">
                            @if($product->categories && $product->categories->count() > 0)
                                @foreach($product->categories->take(2) as $category)
                                    <span style="background: #f8f9fa; color: #7f8c8d; padding: 2px 6px; border-radius: 3px; font-size: 11px;">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            @endif
                        </div>
                        
                        <div style="display: flex; gap: 10px;">
                            <a href="{{ route('products.show', $product) }}" style="flex: 1; text-align: center; padding: 10px 15px; background: #3498db; color: white; text-decoration: none; border-radius: 6px; font-weight: 500; transition: background 0.3s ease;"
                               onmouseover="this.style.background='#2980b9'" 
                               onmouseout="this.style.background='#3498db'">
                                View Details
                            </a>
                            
                            <button onclick="addToCart({{ $product->id }})" style="padding: 10px; background: #27ae60; color: white; border: none; border-radius: 6px; cursor: pointer; transition: background 0.3s ease; flex-shrink: 0;"
                                onmouseover="this.style.background='#219653'" 
                                onmouseout="this.style.background='#27ae60'">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: white; border-radius: 8px; border: 1px solid #f1f2f6;">
                <i class="fas fa-box-open" style="font-size: 4rem; color: #bdc3c7; margin-bottom: 20px;"></i>
                <h3 style="color: #7f8c8d; margin-bottom: 10px;">No products found</h3>
                <p style="color: #95a5a6;">This seller hasn't listed any products yet.</p>
            </div>
        @endif
    </div>
</div>

<script>
    // Load cart count on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateCartCount();
        
        // Add hamburger menu to header
        addMobileNavigationToggle();
        
        // Initialize mobile navigation
        initializeMobileNavigation();
        
        // Add touch event support for product cards
        addTouchEventSupport();
    });

    function addMobileNavigationToggle() {
        const headerContent = document.querySelector('.header-content');
        if (headerContent) {
            const logo = headerContent.querySelector('.logo');
            if (logo && !document.getElementById('mobileNavToggle')) {
                const mobileNavToggle = document.createElement('button');
                mobileNavToggle.className = 'mobile-nav-toggle';
                mobileNavToggle.id = 'mobileNavToggle';
                mobileNavToggle.setAttribute('aria-label', 'Toggle navigation');
                mobileNavToggle.innerHTML = '<span></span><span></span><span></span>';
                headerContent.insertBefore(mobileNavToggle, logo);
            }
        }
    }

    function initializeMobileNavigation() {
        const mobileNavToggle = document.getElementById('mobileNavToggle');
        const mobileNavMenu = document.getElementById('mobileNavMenu');
        const mobileNavClose = document.getElementById('mobileNavClose');
        const mobileNavOverlay = document.getElementById('mobileNavOverlay');
        
        console.log('Seller details page - Mobile nav elements found:', {
            toggle: !!mobileNavToggle,
            menu: !!mobileNavMenu,
            close: !!mobileNavClose,
            overlay: !!mobileNavOverlay
        });
        
        if (!mobileNavToggle || !mobileNavMenu || !mobileNavClose || !mobileNavOverlay) {
            console.error('Seller details page - Mobile navigation elements not found!');
            return;
        }
        
        function openMobileNav() {
            console.log('Seller details page - Opening mobile nav');
            mobileNavMenu.className = mobileNavMenu.className.replace(/\bactive\b/g, '').trim() + ' active';
            mobileNavMenu.style.display = 'block';
            mobileNavOverlay.className = mobileNavOverlay.className.replace(/\bactive\b/g, '').trim() + ' active';
            mobileNavToggle.className = mobileNavToggle.className.replace(/\bactive\b/g, '').trim() + ' active';
            document.body.style.overflow = 'hidden';
        }
        
        function closeMobileNav() {
            console.log('Seller details page - Closing mobile nav');
            mobileNavMenu.className = mobileNavMenu.className.replace(/\bactive\b/g, '').trim();
            mobileNavMenu.style.display = 'none';
            mobileNavOverlay.className = mobileNavOverlay.className.replace(/\bactive\b/g, '').trim();
            mobileNavToggle.className = mobileNavToggle.className.replace(/\bactive\b/g, '').trim();
            document.body.style.overflow = '';
        }
        
        // Remove existing listeners to prevent duplicates
        if (mobileNavToggle) {
            mobileNavToggle.replaceWith(mobileNavToggle.cloneNode(true));
            const newToggle = document.getElementById('mobileNavToggle');
            
            newToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Seller details page - Hamburger clicked - event fired');
                openMobileNav();
            });
            
            newToggle.addEventListener('touchstart', function(e) {
                e.preventDefault();
                console.log('Seller details page - Hamburger touched');
                openMobileNav();
            });
        }
        
        if (mobileNavClose) {
            mobileNavClose.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                closeMobileNav();
            });
            
            mobileNavClose.addEventListener('touchstart', function(e) {
                e.preventDefault();
                closeMobileNav();
            });
        }
        
        if (mobileNavOverlay) {
            mobileNavOverlay.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                closeMobileNav();
            });
            
            mobileNavOverlay.addEventListener('touchstart', function(e) {
                e.preventDefault();
                closeMobileNav();
            });
        }
        
        // Close mobile navigation when clicking on links
        const mobileNavLinks = document.querySelectorAll('.mobile-nav-links a');
        mobileNavLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                setTimeout(closeMobileNav, 100);
            });
            link.addEventListener('touchstart', function(e) {
                setTimeout(closeMobileNav, 100);
            });
        });
        
        // Handle ESC key to close mobile navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileNavMenu.classList.contains('active')) {
                closeMobileNav();
            }
        });
        
        console.log('Seller details page - Mobile navigation event listeners attached successfully');
    }

    function addTouchEventSupport() {
        // Add touch event support for product cards
        const productCards = document.querySelectorAll('.product-card');
        productCards.forEach(card => {
            card.addEventListener('touchstart', function(e) {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 8px 30px rgba(0,0,0,0.12)';
            });
            
            card.addEventListener('touchend', function(e) {
                setTimeout(() => {
                    this.style.transform = '';
                    this.style.boxShadow = '';
                }, 200);
            });
        });
        
        // Add touch event support for add to cart buttons
        const addToCartButtons = document.querySelectorAll('button[onclick*="addToCart"]');
        addToCartButtons.forEach(button => {
            button.addEventListener('touchstart', function(e) {
                this.style.transform = 'scale(1.02)';
            });
            
            button.addEventListener('touchend', function(e) {
                setTimeout(() => {
                    this.style.transform = '';
                }, 200);
            });
        });
        
        console.log('Seller details page - Touch event handlers attached successfully');
    }

    function updateCartCount() {
        fetch('/cart/count')
            .then(response => {
                // Check if the response is a redirect (like to login page)
                if (response.redirected || response.url.includes('/login')) {
                    // If redirected to login, just return without updating
                    return { count: 0 };
                }
                return response.json();
            })
            .then(data => {
                const cartCountElement = document.querySelector('.cart-button .cart-count');
                if (cartCountElement) {
                    cartCountElement.textContent = data.count;
                }
                
                // Update wishlist count as well
                updateWishlistCount();
            })
            .catch(error => {
                console.error('Error loading cart count:', error);
            });
    }

    // Add to Cart function
    function addToCart(productId) {
        fetch(`/cart/add/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            // Check if the response is a redirect (like to login page)
            if (response.redirected || response.url.includes('/login')) {
                // Redirect the user to the login page
                window.location.href = response.url;
                return;
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showNotification('Product added to cart successfully!', 'success');
                // Update cart count if function exists
                if (window.updateCartCount) {
                    window.updateCartCount();
                }
            } else {
                showNotification(data.message || 'Error adding product to cart', 'error');
            }
        })
        .catch(error => {
            console.error('Error adding to cart:', error);
            showNotification('Error adding product to cart', 'error');
        });
    }

    // Global function for updating cart count after add to cart
    window.updateCartCount = updateCartCount;

    // Update wishlist count
    function updateWishlistCount() {
        fetch('/wishlist/count')
            .then(response => {
                // Check if the response is a redirect (like to login page)
                if (response.redirected || response.url.includes('/login')) {
                    // If redirected to login, just return without updating
                    return { count: 0 };
                }
                return response.json();
            })
            .then(data => {
                const wishlistCountElement = document.getElementById('header-wishlist-count');
                if (wishlistCountElement) {
                    wishlistCountElement.textContent = data.count;
                }
            })
            .catch(error => {
                console.error('Error loading wishlist count:', error);
            });
    }
    
    window.updateWishlistCount = updateWishlistCount;

    // Show notification function
    function showNotification(message, type) {
        // Create notification element
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            z-index: 10000;
            animation: slideIn 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        
        if (type === 'success') {
            notification.style.background = '#27ae60';
            notification.innerHTML = `<i class="fas fa-check-circle" style="margin-right: 8px;"></i>${message}`;
        } else {
            notification.style.background = '#e74c3c';
            notification.innerHTML = `<i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i>${message}`;
        }
        
        // Add animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
        `;
        document.head.appendChild(style);
        
        document.body.appendChild(notification);
        
        // Remove notification after 3 seconds
        setTimeout(() => {
            notification.style.transition = 'opacity 0.3s ease';
            notification.style.opacity = '0';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
</script>
@endsection