<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Flash Sale - </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f8f9fa;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .main-header {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
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
            font-weight: 600;
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
            color: #2c3e50;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
            color: #2c3e50;
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            padding: 10px 15px;
            display: block;
            border-radius: 6px;
            transition: all 0.3s ease;
            pointer-events: auto !important;
        }
        
        .mobile-nav-links a:hover {
            background: rgba(52, 152, 219, 0.1);
            color: #3498db;
            transform: translateX(5px);
        }
        
        .mobile-nav-actions {
            padding: 20px;
            border-top: 1px solid #e9ecef;
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
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
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
            background: #27ae60;
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
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: #3498db;
            text-decoration: none;
        }
        
        .main-nav {
            display: flex;
            list-style: none;
            gap: 30px;
        }
        
        .main-nav a {
            text-decoration: none;
            color: #2c3e50;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .main-nav a:hover {
            color: #3498db;
        }
        
        .main-nav a.active {
            color: #e74c3c;
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .search-bar {
            display: flex;
            align-items: center;
        }
        
        .search-bar input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 200px;
        }
        
        .search-bar button {
            background: none;
            border: none;
            color: #7f8c8d;
            cursor: pointer;
            padding: 8px;
        }
        
        .cart-button {
            background: #3498db !important;
            color: white !important;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(52, 152, 219, 0.3);
        }
        
        .cart-button:hover {
            background: #2980b9 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.4);
        }
        
        .wishlist-button {
            background: #27ae60 !important;
            color: white !important;
            padding: 10px 16px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(39, 174, 96, 0.3);
        }
        
        .wishlist-button:hover {
            background: #229954 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(39, 174, 96, 0.4);
        }
        
        /* Flash Sale Header */
        .flash-sale-header {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            padding: 60px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .flash-sale-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="lightning" width="50" height="50" patternUnits="userSpaceOnUse"><path d="M25 0 L25 20 L35 20 L20 50 L30 50 L15 80 L25 50 L15 50 L30 20 L20 20 L20 0 Z" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23lightning)"/></svg>');
            pointer-events: none;
        }
        
        .flash-sale-content {
            position: relative;
            z-index: 1;
        }
        
        .flash-sale-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 16px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .flash-sale-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 30px;
        }
        
        .flash-sale-timer {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        
        .timer-unit {
            background: rgba(255,255,255,0.2);
            padding: 15px 20px;
            border-radius: 8px;
            text-align: center;
            backdrop-filter: blur(10px);
        }
        
        .timer-value {
            font-size: 2rem;
            font-weight: 700;
            display: block;
        }
        
        .timer-label {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        /* Filters Section */
        .filters-section {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin: -30px auto 40px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            position: relative;
            z-index: 10;
        }
        
        .filters-form {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            align-items: flex-end;
        }
        
        .filter-group {
            flex: 1;
            min-width: 200px;
        }
        
        .filter-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
        }
        
        .filter-select {
            width: 100%;
            padding: 10px 12px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            background: white;
            color: #2c3e50;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }
        
        .filter-select:focus {
            border-color: #e74c3c;
            outline: none;
        }
        
        .search-input-group {
            position: relative;
            flex: 2;
            min-width: 250px;
        }
        
        .search-input {
            width: 100%;
            padding: 10px 12px 10px 40px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            background: white;
            color: #2c3e50;
            transition: border-color 0.3s ease;
        }
        
        .search-input:focus {
            border-color: #e74c3c;
            outline: none;
        }
        
        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #7f8c8d;
        }
        
        .clear-filters-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            white-space: nowrap;
        }
        
        .clear-filters-btn:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }
        
        /* Results Summary */
        .results-summary {
            background: white;
            border-radius: 8px;
            padding: 16px 20px;
            margin-bottom: 30px;
            border-left: 4px solid #e74c3c;
        }
        
        .results-text {
            margin: 0;
            color: #2c3e50;
            font-weight: 500;
        }
        
        /* Products Grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            text-decoration: none;
            color: #2c3e50;
            display: block;
        }
        
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }
        
        .product-image {
            position: relative;
            height: 200px;
            overflow: hidden;
            background: #f8f9fa;
        }
        
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .product-card:hover .product-image img {
            transform: scale(1.08);
        }
        
        .discount-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #e74c3c;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(231, 76, 60, 0.3);
            z-index: 10;
        }
        
        .flash-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(243, 156, 18, 0.3);
            z-index: 10;
        }
        
        .product-info {
            padding: 20px;
        }
        
        .product-name {
            font-weight: 700;
            margin-bottom: 12px;
            color: #2c3e50;
            font-size: 16px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .product-price {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }
        
        .current-price {
            font-size: 20px;
            font-weight: 800;
            color: #e74c3c;
        }
        
        .original-price {
            font-size: 16px;
            color: #95a5a6;
            text-decoration: line-through;
            font-weight: 400;
        }
        
        .product-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }
        
        .category-tag {
            background: #ecf0f1;
            color: #7f8c8d;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .rating {
            display: flex;
            align-items: center;
            gap: 4px;
            color: #f39c12;
            font-size: 14px;
        }
        
        .add-to-cart {
            background: #3498db;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            border: none;
        }
        
        .add-to-cart:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 8px;
            grid-column: 1 / -1;
        }
        
        .empty-icon {
            font-size: 4rem;
            color: #bdc3c7;
            margin-bottom: 20px;
        }
        
        .empty-title {
            color: #7f8c8d;
            margin-bottom: 10px;
        }
        
        .empty-text {
            color: #95a5a6;
            margin-bottom: 20px;
        }
        
        .view-all-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .view-all-btn:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 40px;
        }
        
        .pagination a {
            padding: 10px 15px;
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            text-decoration: none;
            color: #2c3e50;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .pagination a:hover {
            border-color: #e74c3c;
            color: #e74c3c;
        }
        
        .pagination .active {
            background: #e74c3c;
            color: white;
            border-color: #e74c3c;
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
            .flash-sale-title {
                font-size: 2rem;
            }
            
            .flash-sale-timer {
                flex-wrap: wrap;
                gap: 10px;
            }
            
            .timer-unit {
                padding: 10px 15px;
            }
            
            .timer-value {
                font-size: 1.5rem;
            }
            
            .filters-form {
                flex-direction: column;
            }
            
            .filter-group,
            .search-input-group {
                width: 100%;
            }
            
            .products-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 20px;
            }
            
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
        }
    </style>
</head>
<body>
    <!-- Main Header -->
    <header class="main-header">
        <div class="container">
            <div class="header-content">
                <a href="{{ route('home') }}" class="logo"><img src="{{ asset('storage/' . $headerLogo) }}" alt="Logo" style="height: 40px; width: auto;"></a>
                
                <!-- Mobile Navigation Toggle -->
                <button class="mobile-nav-toggle" id="mobileNavToggle" aria-label="Toggle navigation">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                
                <nav>
                    <ul class="main-nav">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('flash-sale.index') }}" class="active">Flash Sale</a></li>
                        <li><a href="{{ route('products.index') }}">Products</a></li>
                        <li><a href="{{ route('categories.index') }}">Categories</a></li>
                        <li><a href="{{ route('sellers.index') }}">Sellers</a></li>

                        
                    </ul>
                </nav>
                
                <div class="header-actions">
                    <div class="search-bar">
                        <input type="text" placeholder="Search flash deals...">
                        <button type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    
                    <a href="{{ route('wishlist.index') }}" class="wishlist-button">
                        <i class="fas fa-heart"></i>
                        Wishlist
                        @if(auth()->check())
                        <span class="cart-count" id="header-wishlist-count">0</span>
                        @endif
                    </a>
                    
                    <a href="{{ route('cart.show') }}" class="cart-button">
                        <i class="fas fa-shopping-cart"></i>
                        Cart
                        <span class="cart-count">0</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

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

    <!-- Flash Sale Header -->
    <div class="flash-sale-header">
        <div class="container">
            <div class="flash-sale-content">
                <h1 class="flash-sale-title">
                    <i class="fas fa-bolt"></i> FLASH SALE
                </h1>
                <p class="flash-sale-subtitle">Massive discounts on selected items - Limited time only!</p>
                
                <div class="flash-sale-timer">
                    <div class="timer-unit">
                        <span class="timer-value" id="hours">23</span>
                        <span class="timer-label">Hours</span>
                    </div>
                    <div class="timer-unit">
                        <span class="timer-value" id="minutes">59</span>
                        <span class="timer-label">Minutes</span>
                    </div>
                    <div class="timer-unit">
                        <span class="timer-value" id="seconds">59</span>
                        <span class="timer-label">Seconds</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- Filters Section -->
        <div class="filters-section">
            <form method="GET" action="{{ route('flash-sale.index') }}" class="filters-form">
                
                <!-- Sort Filter -->
                <div class="filter-group">
                    <label class="filter-label">Sort By</label>
                    <select name="sort" onchange="this.form.submit()" class="filter-select">
                        <option value="discount_desc" {{ request('sort') == 'discount_desc' ? 'selected' : '' }}>Biggest Discount</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price (Low to High)</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price (High to Low)</option>
                        <option value="created_desc" {{ request('sort') == 'created_desc' ? 'selected' : '' }}>Newest First</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                    </select>
                </div>

                <!-- Discount Filter -->
                <div class="filter-group">
                    <label class="filter-label">Discount Level</label>
                    <select name="discount_filter" onchange="this.form.submit()" class="filter-select">
                        <option value="" {{ request('discount_filter') == '' ? 'selected' : '' }}>All Discounts</option>
                        <option value="10_plus" {{ request('discount_filter') == '10_plus' ? 'selected' : '' }}>10% Off or More</option>
                        <option value="25_plus" {{ request('discount_filter') == '25_plus' ? 'selected' : '' }}>25% Off or More</option>
                        <option value="50_plus" {{ request('discount_filter') == '50_plus' ? 'selected' : '' }}>50% Off or More</option>
                        <option value="70_plus" {{ request('discount_filter') == '70_plus' ? 'selected' : '' }}>70% Off or More</option>
                    </select>
                </div>

                <!-- Search -->
                <div class="search-input-group">
                    <label class="filter-label">Search Deals</label>
                    <div style="position: relative;">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search flash sale products..." class="search-input">
                    </div>
                </div>

                <!-- Clear Filters Button -->
                @if(request()->hasAny(['sort', 'discount_filter', 'search']))
                    <div>
                        <a href="{{ route('flash-sale.index') }}" class="clear-filters-btn">
                            <i class="fas fa-times"></i>
                            Clear Filters
                        </a>
                    </div>
                @endif
            </form>
        </div>

        <!-- Results Summary -->
        <div class="results-summary">
            <p class="results-text">
                Showing <strong>{{ $products->count() }}</strong> 
                @if(request()->hasAny(['sort', 'discount_filter', 'search']))
                    filtered 
                @endif
                flash deal{{ $products->count() == 1 ? '' : 's' }}
                @if(request('search'))
                    for "<strong>{{ request('search') }}</strong>"
                @endif
            </p>
        </div>

        <!-- Products Grid -->
        <div class="products-grid">
            @if($products->count() > 0)
                @foreach($products as $product)
                    <a href="{{ route('products.show', $product) }}" class="product-card">
                        <div class="product-image">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                            @else
                                <img src="https://via.placeholder.com/300x200/3498db/ffffff?text=Product" alt="{{ $product->name }}">
                            @endif
                            
                            <div class="flash-badge">
                                <i class="fas fa-bolt"></i> FLASH
                            </div>
                            
                            @if($product->discount_percentage > 0)
                                <div class="discount-badge">
                                    {{ $product->discount_percentage }}% OFF
                                </div>
                            @endif
                        </div>
                        
                        <div class="product-info">
                            <h3 class="product-name">{{ $product->name }}</h3>
                            
                            <div class="product-price">
                                <span class="current-price">
                                    ${{ $product->sale_price ?? number_format($product->price * (1 - $product->discount_percentage/100), 2) }}
                                </span>
                                @if($product->sale_price || $product->discount_percentage > 0)
                                    <span class="original-price">
                                        ${{ $product->price }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="product-meta">
                                @if($product->categories && $product->categories->count() > 0)
                                    <span class="category-tag">{{ $product->categories->first()->name }}</span>
                                @endif
                                <div class="rating">
                                    <i class="fas fa-star"></i>
                                    <span>4.5</span>
                                </div>
                            </div>
                            
                            <button class="add-to-cart" onclick="addToCart({{ $product->id }})">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                        </div>
                    </a>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="fas fa-bolt empty-icon"></i>
                    <h3 class="empty-title">No Flash Deals Found</h3>
                    <p class="empty-text">
                        @if(request()->hasAny(['sort', 'discount_filter', 'search']))
                            Try adjusting your filters or search terms.
                        @else
                            Check back later for new flash sale deals!
                        @endif
                    </p>
                    @if(request()->hasAny(['sort', 'discount_filter', 'search']))
                        <div>
                            <a href="{{ route('flash-sale.index') }}" class="view-all-btn">
                                <i class="fas fa-redo"></i>
                                View All Deals
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            {{ $products->links() }}
        @endif
    </div>

    <script>
        // Flash Sale Timer function
        function startTimer() {
            let hours = 23;
            let minutes = 59;
            let seconds = 59;
            
            const timerInterval = setInterval(() => {
                seconds--;
                
                if (seconds < 0) {
                    seconds = 59;
                    minutes--;
                    
                    if (minutes < 0) {
                        minutes = 59;
                        hours--;
                        
                        if (hours < 0) {
                            clearInterval(timerInterval);
                            document.getElementById('hours').textContent = '00';
                            document.getElementById('minutes').textContent = '00';
                            document.getElementById('seconds').textContent = '00';
                            return;
                        }
                    }
                }
                
                document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
                document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
                document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
            }, 1000);
        }
        
        // Start the timer when page loads
        document.addEventListener('DOMContentLoaded', startTimer);
    </script>
    
    <script>
    // Load cart count on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateCartCount();
    });

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
        .then(response => response.json())
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

    // Wishlist functionality
    function toggleWishlist(productId) {
        console.log('Toggling wishlist for product:', productId);
        
        if (!authCheck()) {
            showNotification('Please login to add items to wishlist', 'error');
            return;
        }

        fetch(`/wishlist/toggle/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Wishlist response data:', data);
            if (data.success) {
                showNotification(data.message, 'success');
                updateWishlistCount();
                updateWishlistButton(productId, data.in_wishlist);
            } else {
                showNotification(data.message || 'Error updating wishlist', 'error');
            }
        })
        .catch(error => {
            console.error('Error toggling wishlist:', error);
            showNotification('Error updating wishlist. Please try again.', 'error');
        });
    }

    function updateWishlistButton(productId, inWishlist) {
        const button = document.querySelector(`[data-wishlist-product="${productId}"]`);
        if (button) {
            if (inWishlist) {
                button.classList.add('in-wishlist');
                button.innerHTML = '<i class="fas fa-heart"></i> In Wishlist';
                button.style.background = '#e74c3c';
            } else {
                button.classList.remove('in-wishlist');
                button.innerHTML = '<i class="far fa-heart"></i> Add to Wishlist';
                button.style.background = '#27ae60';
            }
        }
    }

    function authCheck() {
        return @json(auth()->check());
    }

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
            notification.style.animation = 'slideIn 0.3s ease reverse';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    // Initialize wishlist functionality
    document.addEventListener('DOMContentLoaded', function() {
        updateWishlistCount();
        
        @if(auth()->check())
            // Get user's wishlist items and initialize button states
            fetch('/wishlist', {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const wishlistItems = data.wishlistItems || [];
                const wishlistProductIds = wishlistItems.map(item => item.product_id);
                
                // Update wishlist buttons state
                document.querySelectorAll('.wishlist-toggle-btn').forEach(button => {
                    const productId = button.getAttribute('data-wishlist-product');
                    if (productId && wishlistProductIds.includes(parseInt(productId))) {
                        button.classList.add('in-wishlist');
                        button.innerHTML = '<i class="fas fa-heart"></i>';
                        button.style.background = '#e74c3c';
                    }
                });
            })
            .catch(error => {
                console.error('Error initializing wishlist buttons:', error);
            });
        @endif
    });
    </script>
    
    <!-- Mobile Navigation JavaScript -->
    <script>
        // Mobile Navigation functionality - Ensure DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Wait a bit to ensure all elements are loaded
            setTimeout(function() {
                const mobileNavToggle = document.getElementById('mobileNavToggle');
                const mobileNavMenu = document.getElementById('mobileNavMenu');
                const mobileNavClose = document.getElementById('mobileNavClose');
                const mobileNavOverlay = document.getElementById('mobileNavOverlay');
                
                console.log('Flash Sale - Mobile nav elements found:', {
                    toggle: !!mobileNavToggle,
                    menu: !!mobileNavMenu,
                    close: !!mobileNavClose,
                    overlay: !!mobileNavOverlay
                });
                
                if (!mobileNavToggle || !mobileNavMenu || !mobileNavClose || !mobileNavOverlay) {
                    console.error('Flash Sale - Mobile navigation elements not found!');
                    return;
                }
                
                function openMobileNav() {
                    console.log('Flash Sale - Opening mobile nav');
                    mobileNavMenu.className = mobileNavMenu.className.replace(/\bactive\b/g, '').trim() + ' active';
                    mobileNavMenu.style.display = 'block';
                    mobileNavOverlay.className = mobileNavOverlay.className.replace(/\bactive\b/g, '').trim() + ' active';
                    mobileNavToggle.className = mobileNavToggle.className.replace(/\bactive\b/g, '').trim() + ' active';
                    document.body.style.overflow = 'hidden';
                }
                
                function closeMobileNav() {
                    console.log('Flash Sale - Closing mobile nav');
                    mobileNavMenu.className = mobileNavMenu.className.replace(/\bactive\b/g, '').trim();
                    mobileNavMenu.style.display = 'none';
                    mobileNavOverlay.className = mobileNavOverlay.className.replace(/\bactive\b/g, '').trim();
                    mobileNavToggle.className = mobileNavToggle.className.replace(/\bactive\b/g, '').trim();
                    document.body.style.overflow = '';
                }
                
                // Remove existing listeners to prevent duplicates
                mobileNavToggle.replaceWith(mobileNavToggle.cloneNode(true));
                const newToggle = document.getElementById('mobileNavToggle');
                
                newToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Flash Sale - Hamburger clicked');
                    openMobileNav();
                });
                
                mobileNavClose.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    closeMobileNav();
                });
                
                mobileNavOverlay.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    closeMobileNav();
                });
                
                // Close mobile navigation when clicking on links
                const mobileNavLinks = document.querySelectorAll('.mobile-nav-links a');
                mobileNavLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        setTimeout(closeMobileNav, 100);
                    });
                });
                
                // Handle ESC key to close mobile navigation
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && mobileNavMenu.classList.contains('active')) {
                        closeMobileNav();
                    }
                });
                
                console.log('Flash Sale - Mobile navigation event listeners attached successfully');
            }, 100);
        });
    </script>
</body>
</html>
