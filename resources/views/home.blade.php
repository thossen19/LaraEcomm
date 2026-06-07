<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Lara-Com') }} - AN ONLINE SHOPPING PLATFORM WITH GREAT DEALS</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
            /* Reset and Base Styles */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            html {
                scroll-behavior: smooth;
            }
            
            body {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
                background: #f8f9fa !important;
                color: #2c3e50 !important;
                line-height: 1.6;
                font-size: 16px;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }
            
            img {
                max-width: 100%;
                height: auto;
                display: block;
            }
            
            a {
                text-decoration: none;
                transition: all 0.3s ease;
            }
            
            button {
                border: none;
                outline: none;
                cursor: pointer;
                font-family: inherit;
                transition: all 0.3s ease;
            }
            
            input, select {
                font-family: inherit;
                outline: none;
                transition: all 0.3s ease;
            }
            
            .container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0 20px;
                width: 100%;
            }
            
            /* Top Bar Styles */
            .top-bar {
                background: #2c3e50 !important;
                color: white !important;
                padding: 10px 0;
                font-size: 13px;
                border-bottom: 1px solid #34495e;
                position: relative;
                z-index: 1001;
            }
            
            .top-bar .container {
                display: flex !important;
                justify-content: space-between;
                align-items: center;
                min-height: 40px;
            }
            
            .top-bar-left, .top-bar-right {
                display: flex !important;
                align-items: center;
                gap: 20px;
                flex-wrap: wrap;
            }
            
            .top-bar a {
                color: #ecf0f1 !important;
                text-decoration: none;
                transition: color 0.3s ease;
                font-weight: 400;
            }
            
            .top-bar a:hover {
                color: #3498db !important;
            }
            
            .currency-dropdown {
                background: transparent;
                color: white !important;
                border: 1px solid #7f8c8d;
                padding: 4px 8px;
                border-radius: 4px;
                font-size: 12px;
                cursor: pointer;
            }
            
            .currency-dropdown:focus {
                border-color: #3498db;
            }
            
            .currency-dropdown option {
                background: #2c3e50;
                color: white;
            }
            
            /* Header Styles */
            .main-header {
                background: white !important;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                position: sticky;
                top: 0;
                z-index: 999;
                border-bottom: 1px solid #e9ecef;
            }
            
            .header-content {
                display: flex !important;
                justify-content: space-between;
                align-items: center;
                padding: 20px 0;
                flex-wrap: nowrap;
                gap: 30px;
                min-height: 80px;
            }
            
            .logo {
                font-size: 28px !important;
                font-weight: 800 !important;
                color: #2c3e50 !important;
                text-decoration: none;
                display: flex;
                align-items: center;
                white-space: nowrap;
                transition: color 0.3s ease;
            }
            
            .logo:hover {
                color: #3498db !important;
            }
            
            .main-nav {
                display: flex !important;
                list-style: none;
                gap: 30px;
                align-items: center;
                flex-wrap: wrap;
                margin: 0;
                padding: 0;
            }
            
            .main-nav a {
                color: #2c3e50 !important;
                text-decoration: none;
                font-weight: 600;
                font-size: 15px;
                transition: color 0.3s ease;
                position: relative;
                padding: 5px 0;
            }
            
            .main-nav a:hover {
                color: #3498db !important;
            }
            
            .main-nav a::after {
                content: '';
                position: absolute;
                bottom: -2px;
                left: 0;
                width: 0;
                height: 2px;
                background: #3498db;
                transition: width 0.3s ease;
            }
            
            .main-nav a:hover::after {
                width: 100%;
            }
            
            .header-actions {
                display: flex !important;
                align-items: center;
                gap: 20px;
                flex-shrink: 0;
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
                pointer-events: auto;
            }
            
            .mobile-nav-menu.active {
                left: 0;
                display: block !important;
            }
            
            /* Ensure all mobile navigation interactive elements are clickable */
            .mobile-nav-menu *,
            .mobile-nav-menu button,
            .mobile-nav-menu a,
            .mobile-nav-menu input {
                pointer-events: auto !important;
            }
            
            .mobile-nav-overlay.active {
                display: block;
                opacity: 1;
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
            
            .search-bar {
                position: relative;
                display: flex;
                align-items: center;
                height:45px;
            }
            
            .search-bar input {
                padding: 12px 50px 12px 20px;
                border: 1px solid #e0e0e0;
                border-radius: 50px;
                width: 250px;
                Height:43px;
                font-size: 14px;
                transition: all 0.3s ease;
                background: #f8f9fa;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            }
            
            .search-bar input:focus {
                border-color: #3498db;
                background: white;
                box-shadow: 0 4px 15px rgba(52, 152, 219, 0.2);
                outline: none;
            }
            
            .search-bar button {
                position: absolute;
                right: 8px;
                background: #3498db;
                border: none;
                color: white;
                width: 36px;
                height: 36px;
                border-radius: 50%;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
            }
            
            .search-bar button:hover {
                background: #2980b9;
                transform: scale(1.05);
            }
            
            .cart-button {
                background: #3498db !important;
                color: white !important;
                padding: 10px 18px;
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
                padding: 10px 18px;
                border-radius: 8px;
                cursor: pointer;
                display: flex;
                align-items: center;
                gap: 4px;
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
            
            .cart-count {
                background: #e74c3c !important;
                color: white !important;
                border-radius: 50%;
                width: 20px;
                height: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 11px;
                font-weight: 700;
                position: relative;
                top: -8px;
                right: -4px;
            }
            
            /* Hero Section */
            .hero-section {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
                color: white !important;
                padding: 80px 0;
                margin-bottom: 0;
                position: relative;
                overflow: hidden;
            }
            
            .hero-section::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="0.5" fill="white" opacity="0.03"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
                pointer-events: none;
            }
            
            .hero-content {
                display: grid !important;
                grid-template-columns: 1fr 1fr;
                gap: 60px;
                align-items: center;
                justify-items: start;
                position: relative;
                z-index: 1;
            }
            
            .hero-text h1 {
                font-size: 48px !important;
                font-weight: 800 !important;
                margin-bottom: 24px;
                line-height: 1.1;
                text-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            
            .hero-text p {
                font-size: 20px !important;
                margin-bottom: 32px;
                opacity: 0.95;
                line-height: 1.6;
                font-weight: 400;
            }
            
            .hero-buttons {
                display: flex !important;
                gap: 16px;
                margin-bottom: 48px;
                flex-wrap: wrap;
            }
            
            .btn {
                padding: 14px 28px;
                border-radius: 8px;
                text-decoration: none;
                font-weight: 600;
                font-size: 16px;
                transition: all 0.3s ease;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                border: none;
                min-width: 140px;
                text-align: center;
            }
            
            .btn-primary {
                background: #e74c3c !important;
                color: white !important;
                box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
            }
            
            .btn-primary:hover {
                background: #c0392b !important;
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
            }
            
            .btn-secondary {
                background: white !important;
                color: #667eea !important;
                box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
            }
            
            .btn-secondary:hover {
                background: #f8f9fa !important;
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(255, 255, 255, 0.4);
            }
            
            .hero-image {
                position: relative;
            }
            
            .hero-image img {
                width: 100%;
                border-radius: 16px;
                box-shadow: 0 20px 40px rgba(0,0,0,0.2);
                transition: transform 0.3s ease;
            }
            
            .hero-image:hover img {
                transform: scale(1.02);
            }
            
            /* Section Styles */
            .section {
                padding: 80px 0;
                position: relative;
            }
            
            .section-title {
                text-align: center;
                margin-top: 20px;
                margin-bottom:20px;
                font-size: 24px !important;
                margin-bottom: 20px;
                color: #000 !important;
                font-weight: 700;
            }
            
            .section-title h2 {
                font-size: 36px !important;
                font-weight: 800 !important;
                color: #2c3e50 !important;
                margin-bottom: 12px;
                line-height: 1.2;
                position: relative;
            }
            
            .section-title h3 {
                font-size: 36px !important;
                font-weight: 800 !important;
                color: #000 !important;
                margin-bottom: 12px;
                line-height: 1.2;
                position: relative;
            }

          
            
            
            .section-title h3::after {
                content: '';
                position: absolute;
                bottom: -4px;
                left: 50%;
                transform: translateX(-50%);
                width: 40px;
                height: 3px;
                background: #3498db;
                border-radius: 2px;
            }
            
            .section-title p {
                color: #7f8c8d !important;
                font-size: 18px;
                font-weight: 400;
                line-height: 1.6;
            }
            
            /* Category Grid */
            .category-grid {
                display: grid !important;
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                gap: 24px;
                justify-items: stretch;
                margin-bottom: 40px;
            }
            
            /* Category Slider */
            .category-slider {
                display: flex !important;
                gap: 20px;
                overflow-x: auto !important;
                overflow-y: hidden !important;
                scroll-behavior: smooth;
                padding: 20px 70px 20px 70px;
                scrollbar-width: thin;
                scrollbar-color: #ddd #f8f9fa;
                -ms-overflow-style: thin;
                white-space: nowrap;
                width: 100%;
            }
            
            .category-slider::-webkit-scrollbar {
                height: 6px;
                display: block;
            }
            
            .category-slider::-webkit-scrollbar-track {
                background: #f8f9fa;
                border-radius: 3px;
            }
            
            .category-slider::-webkit-scrollbar-thumb {
                background: #ddd;
                border-radius: 3px;
            }
            
            .category-slider::-webkit-scrollbar-thumb:hover {
                background: #bbb;
            }
            
            .category-slider-container {
                position: relative;
                overflow: hidden;
                width: 100%;
                max-width: 800px; /* Limit max width to force scrolling */
                margin: 0 auto;
                padding: 0;
            }
            
            .category-scroll-item {
                min-width: 210px !important;
                max-width: 210px !important;
                flex-shrink: 0 !important;
                display: inline-block !important;
            }
            
            .category-nav-btn {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                z-index: 10;
                background: rgba(255,255,255,0.95);
                border: none;
                width: 45px;
                height: 45px;
                border-radius: 50%;
                cursor: pointer;
                display: none !important;
                align-items: center;
                justify-content: center;
                box-shadow: 0 4px 15px rgba(0,0,0,0.3);
                transition: all 0.3s ease;
            }
            
            .category-nav-left {
                left: 15px;
            }
            
            .category-nav-right {
                right: 15px;
            }
            
            .category-nav-btn:hover {
                background: rgba(255,255,255,1);
                transform: translateY(-50%) scale(1.1);
            }
            
            .category-card {
                background: #fff !important;
                border-radius: 12px;
                padding: 30px 20px;
                text-align: center;
                box-shadow: 0 4px 20px rgba(0,0,0,0.08);
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                cursor: pointer;
                text-decoration: none;
                color: #2c3e50 !important;
                border: 1px solid #e9ecef;
                position: relative;
                overflow: hidden;
                width: 400px;
                height: 180px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }
            
            .category-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(135deg, #3498db, #2980b9);
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            
            .category-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 12px 40px rgba(0,0,0,0.15);
                border-color: #3498db;
            }
            
            .category-card:hover::before {
                opacity: 0.05;
            }
            
            .category-icon {
                font-size: 48px !important;
                margin-bottom: 16px;
                color: #3498db !important;
                transition: transform 0.3s ease;
                position: relative;
                z-index: 1;
            }
            
            .category-card:hover .category-icon {
                transform: scale(1.1);
            }
            
            .category-name {
                font-weight: 700 !important;
                margin-bottom: 8px;
                font-size: 24px;
                position: relative;
                z-index: 1;
                color: #2c3e50 !important;
                line-height: 1.1;
            }
            
            .category-count {
                color: #7f8c8d !important;
                font-size: 10px;
                font-weight: 400;
                position: relative;
                z-index: 1;
            }
            
            /* Product Grid */
            .product-grid {
                display: grid !important;
                grid-template-columns: repeat(4, 1fr);
                gap: 30px;
                justify-items: stretch;
                margin-bottom: 40px;
            }
            
            @media (max-width: 1200px) {
                .product-grid {
                    grid-template-columns: repeat(3, 1fr);
                }
            }
            
            @media (max-width: 768px) {
                .product-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }
            
            @media (max-width: 480px) {
                .product-grid {
                    grid-template-columns: 1fr;
                }
                
                .mobile-nav-toggle {
                    display: block !important;
                }
                
                .mobile-nav-menu {
                    display: block !important;
                    width: 85%;
                    max-width: 280px;
                }
                
                .mobile-nav-overlay {
                    display: block !important;
                }
                
                .header-content {
                    padding: 10px 0;
                }
                
                .logo img {
                    height: 40px !important;
                }
                
                .cart-button, .wishlist-button {
                    padding: 6px 10px;
                    font-size: 12px;
                }
                
                .cart-count {
                    width: 18px;
                    height: 18px;
                    font-size: 10px;
                }
            }
            
            .product-card {
                background: white !important;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 4px 20px rgba(0,0,0,0.08);
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                cursor: pointer;
                position: relative;
                border: 0px solid #e9ecef;
            }
            
            .product-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 12px 40px rgba(0,0,0,0.15);
                border-color: #3498db;
            }
            
            .product-image {
                position: relative;
                height: 250px;
                overflow: hidden;
                background: #f8f9fa;
            }
            
            .product-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            .product-card:hover .product-image img {
                transform: scale(1.08);
            }
            
            .product-badge {
                position: absolute;
                top: 12px;
                right: 12px;
                background: #e74c3c !important;
                color: white !important;
                padding: 6px 12px;
                border-radius: 20px;
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                box-shadow: 0 2px 8px rgba(231, 76, 60, 0.3);
                z-index: 10;
            }
            
            .product-info {
                padding: 20px;
            }
            
            .product-name {
                font-weight: 700 !important;
                margin-bottom: 12px;
                color: #2c3e50 !important;
                font-size: 16px;
                line-height: 1.4;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            
            .product-price {
                display: flex !important;
                align-items: center;
                gap: 12px;
                margin-bottom: 16px;
            }
            
            .current-price {
                font-size: 20px !important;
                font-weight: 800 !important;
                color: #e74c3c !important;
            }
            
            .original-price {
                font-size: 16px;
                color: #95a5a6;
                text-decoration: line-through;
                font-weight: 400;
            }
            
            .product-rating {
                display: flex !important;
                align-items: center;
                gap: 8px;
                margin-bottom: 20px;
            }
            
            .stars {
                color: #f39c12 !important;
                font-size: 14px;
            }
            
            .rating-count {
                color: #7f8c8d !important;
                font-size: 14px;
                font-weight: 400;
            }
            
            .add-to-cart {
                background: #3498db !important;
                color: white !important;
                padding: 12px 20px;
                border-radius: 8px;
                cursor: pointer;
                width: 100%;
                font-weight: 600;
                font-size: 14px;
                transition: all 0.3s ease;
                box-shadow: 0 2px 8px rgba(52, 152, 219, 0.3);
            }
            
            .add-to-cart:hover {
                background: #2980b9 !important;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(52, 152, 219, 0.4);
            }
            
            .wishlist-toggle-btn {
                background: #27ae60 !important;
                color: white !important;
                padding: 12px 15px;
                border-radius: 8px;
                cursor: pointer;
                font-weight: 600;
                font-size: 14px;
                transition: all 0.3s ease;
                box-shadow: 0 2px 8px rgba(39, 174, 96, 0.3);
                border: none;
                min-width: 50px;
            }
            
            .wishlist-toggle-btn:hover {
                background: #229954 !important;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(39, 174, 96, 0.4);
            }
            
            .wishlist-toggle-btn.in-wishlist {
                background: #e74c3c !important;
            }
            
            .wishlist-toggle-btn.in-wishlist:hover {
                background: #c0392b !important;
            }
            
            /* Newsletter */
            .newsletter {
                background: linear-gradient(135deg, #3498db, #2980b9) !important;
                color: white !important;
                padding: 40px 0;
                position: relative;
                overflow: hidden;
                max-height: 270px;
            }
            
            .newsletter .container {
                max-height: 270px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .newsletter::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
                pointer-events: none;
            }
            
            .newsletter-content {
                text-align: center;
                max-width: 600px;
                margin: 0 auto;
                position: relative;
                z-index: 1;
                display: flex;
                flex-direction: column;
                gap: 16px;
                max-height: 270px;
            }
            
            .newsletter h2 {
                font-size: 36px !important;
                margin-bottom: 0;
                font-weight: 800;
                text-shadow: 0 2px 4px rgba(0,0,0,0.1);
                line-height: 1.2;
            }
            
            .newsletter p {
                margin-bottom: 0;
                opacity: 0.95;
                font-size: 18px;
                line-height: 1.6;
            }
            
            .newsletter-form {
                display: flex !important;
                gap: 10px;
                max-width: 450px;
                margin: 0 auto;
                background: rgba(255, 255, 255, 0.1);
                padding: 4px;
                border-radius: 12px;
                backdrop-filter: blur(10px);
            }
            
            .newsletter-form input {
                flex: 1;
                padding: 10px 14px;
                border: none;
                border-radius: 8px;
                outline: none;
                font-size: 14px;
                background: white;
                color: #2c3e50;
            }
            
            .newsletter-form input:focus {
                box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
            }
            
            .newsletter-form input::placeholder {
                color: #7f8c8d;
            }
            
            .newsletter-form button {
                background: #e74c3c !important;
                color: white !important;
                border: none;
                padding: 10px 20px;
                border-radius: 8px;
                cursor: pointer;
                font-weight: 700;
                font-size: 14px;
                transition: all 0.3s ease;
                box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
            }
            
            .newsletter-form button:hover {
                background: #c0392b !important;
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
            }
            
            .newsletter-badge {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 8px 16px;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.15);
                color: white;
                font-weight: 600;
                text-transform: uppercase;
                font-size: 12px;
                letter-spacing: 1.2px;
                margin-bottom: 20px;
            }
            
            .newsletter-badge i {
                color: #f1c40f;
            }
            
            .newsletter-footnote {
                margin-top: 18px;
                color: rgba(255, 255, 255, 0.85);
                font-size: 14px;
                display: inline-flex;
                align-items: center;
                gap: 10px;
                padding: 10px 18px;
                border-radius: 999px;
                background: rgba(0, 0, 0, 0.15);
                box-shadow: 0 12px 28px rgba(0,0,0,0.15);
            }
            
            .newsletter-footnote i {
                color: #2ecc71;
                font-size: 16px;
            }
            
            /* Footer */
            .footer {
                background: #2c3e50 !important;
                color: white !important;
                padding: 60px 0 30px;
                position: relative;
            }
            
            .footer::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 1px;
                background: linear-gradient(90deg, transparent, #34495e, transparent);
            }
            
            .footer-content {
                display: grid !important;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 20px;
                margin-bottom: 40px;
                justify-items: start;
            }
            
            .footer-section h3 {
                font-size: 20px !important;
                margin-bottom: 24px;
                color: #3498db !important;
                font-weight: 700;
                position: relative;
            }
            
            .footer-section h3::after {
                content: '';
                position: absolute;
                bottom: -8px;
                left: 0;
                width: 40px;
                height: 3px;
                background: #3498db;
                border-radius: 2px;
            }
            
            .footer-section p {
                line-height: 1.8;
                color: #bdc3c7!important;
                font-size: 15px;
            }
            
            .footer-section ul {
                list-style: none;
                padding: 0;
                margin: 0;
            }
            
            .footer-section ul li {
                margin-bottom: 12px;
            }
            
            .footer-section a {
                color: #bdc3c7 !important;
                text-decoration: none;
                transition: all 0.3s ease;
                font-weight: 400;
                display: inline-block;
            }
            
            .footer-section a:hover {
                color: #3498db !important;
                transform: translateX(4px);
            }
            
            .footer-section .social-links {
                display: flex !important;
                gap: 16px;
                margin-bottom: 20px;
            }
            
            .footer-section .social-links a {
                width: 40px;
                height: 40px;
                background: rgba(52, 152, 219, 0.1);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 18px;
                transition: all 0.3s ease;
            }
            
            .footer-section .social-links a:hover {
                background: #3498db;
                color: white;
                transform: translateY(-4px);
            }
            
            .footer-section .contact-info {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }
            
            .footer-section .contact-info p {
                display: flex;
                align-items: center;
                gap: 12px;
                margin: 0;
            }
            
            .footer-section .contact-info i {
                color: #3498db !important;
                font-size: 16px;
                width: 20px;
                text-align: center;
            }
            
            .footer-bottom {
                border-top: 1px solid #34495e;
                padding-top: 30px;
                text-align: center;
                color: #95a5a6;
            }
            
            .footer-bottom p {
                margin: 0;
                font-size: 14px;
                line-height: 1.6;
            }
            
            /* Responsive Design */
            @media (max-width: 1024px) {
                .container {
                    padding: 0 20px;
                }
                
                .hero-content {
                    gap: 40px;
                }
                
                .hero-text h1 {
                    font-size: 42px;
                }
                
                .product-grid {
                    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                    gap: 24px;
                }
                
                .category-grid {
                    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                    gap: 20px;
                }
                
                .category-slider-container {
                    max-width: 700px;
                }
                
                /* Show mobile navigation on tablets and smaller */
                .mobile-nav-toggle {
                    display: block !important;
                }
                
                .mobile-nav-menu {
                    display: block !important;
                }
                
                .mobile-nav-overlay {
                    display: block !important;
                }
                
                .main-nav {
                    display: none !important;
                }
            }
            
            @media (max-width: 768px) {
                .container {
                    padding: 0 15px;
                }
                
                .hero-section {
                    padding: 60px 0;
                }
                
                .hero-content {
                    grid-template-columns: 1fr;
                    text-align: center;
                    gap: 30px;
                }
                
                .hero-text h1 {
                    font-size: 32px;
                    margin-bottom: 20px;
                }
                
                .hero-text p {
                    font-size: 18px;
                    margin-bottom: 24px;
                }
                
                .hero-buttons {
                    justify-content: center;
                    flex-direction: column;
                    align-items: center;
                    gap: 12px;
                }
                
                .btn {
                    width: 100%;
                    max-width: 280px;
                }
                
                .section {
                    padding: 60px 0;
                }
                
                .section-title h2 {
                    font-size: 30px;
                }
                
                .section-title p {
                    font-size: 16px;
                }
                
                .main-nav {
                    display: none !important;
                }
                
                .mobile-nav-toggle {
                    display: block;
                }
                
                .mobile-nav-menu {
                    display: block;
                }
                
                .mobile-nav-overlay {
                    display: block;
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
                
                .search-box input {
                    width: 250px;
                }
                
                .top-bar {
                    padding: 8px 0;
                }
                
                .top-bar .container {
                    flex-direction: column;
                    gap: 8px;
                    text-align: center;
                }
                
                .top-bar-left, .top-bar-right {
                    flex-wrap: wrap;
                    justify-content: center;
                    gap: 12px;
                    font-size: 12px;
                }
                
                .top-bar a {
                    font-size: 12px;
                }
                
                .currency-dropdown {
                    font-size: 11px;
                    padding: 3px 6px;
                }
                
                .product-grid {
                    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                    gap: 20px;
                }
                
                .category-grid {
                    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
                    gap: 16px;
                }
                
                .category-slider-container {
                    max-width: 500px; /* Adjust for mobile */
                }
                
                .category-card {
                    padding: 10px 8px;
                }
                
                .category-icon {
                    font-size: 20px;
                }
                
                .category-name {
                    font-size: 10px;
                }
                
                .category-count {
                    font-size: 8px;
                }
                
                .footer-content {
                    grid-template-columns: 1fr;
                    text-align: center;
                    gap: 40px;
                }
                
                .footer-section h3::after {
                    left: 50%;
                    transform: translateX(-50%);
                }
                
                .footer-section .social-links {
                    justify-content: center;
                }
                
                .newsletter {
                    padding: 60px 0;
                }
                
                .newsletter h2 {
                    font-size: 28px;
                }
                
                .newsletter p {
                    font-size: 16px;
                }
                
                .newsletter-form {
                    flex-direction: column;
                    max-width: 300px;
                    padding: 4px;
                }
                
                .newsletter-form input {
                    padding: 14px 16px;
                }
                
                .newsletter-form button {
                    padding: 14px 20px;
                }
                
                .header-actions {
                    flex-direction: column;
                    width: 100%;
                    gap: 12px;
                }
            }
            
            @media (max-width: 480px) {
                .container {
                    padding: 0 12px;
                }
                
                .hero-section {
                    padding: 40px 0;
                }
                
                .hero-text h1 {
                    font-size: 26px;
                }
                
                .hero-text p {
                    font-size: 16px;
                }
                
                .section {
                    padding: 40px 0;
                }
                
                .section-title h2 {
                    font-size: 24px;
                }
                
                .product-grid {
                    grid-template-columns: 1fr;
                    gap: 16px;
                }
                
                .category-grid {
                    grid-template-columns: repeat(2, 1fr);
                    gap: 12px;
                }
                
                .category-card {
                    padding: 8px 6px;
                }
                
                .category-icon {
                    font-size: 18px;
                }
                
                .category-name {
                    font-size: 9px;
                }
                
                .category-count {
                    font-size: 7px;
                }
                
                .product-image {
                    height: 200px;
                }
                
                .product-info {
                    padding: 16px;
                }
                
                .product-name {
                    font-size: 15px;
                }
                
                .current-price {
                    font-size: 18px;
                }
                
                .original-price {
                    font-size: 14px;
                }
                
                .newsletter-form {
                    max-width: 100%;
                }
                
                .footer-content {
                    gap: 30px;
                }
                
                .footer-section h3 {
                    font-size: 18px;
                }
                
                .footer-section p {
                    font-size: 14px;
                }
                
                .search-box input {
                    width: 200px;
                    padding: 10px 40px 10px 12px;
                }
                
                .cart-button {
                    padding: 10px 16px;
                    font-size: 13px;
                }
                
                .wishlist-button {
                    padding: 10px 16px;
                    font-size: 13px;
                }
                
                .logo {
                    font-size: 24px;
                }
            }
        .main-footer {
            background: #2c3e50;
            color: white;
            padding: 60px 0 20px;
            margin-top: 80px;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .footer-section h3 {
            margin-bottom: 20px;
            color: #3498db;
        }
        
        .footer-section ul {
            list-style: none;
        }
        
        .footer-section ul li {
            margin-bottom: 10px;
        }
        
        .footer-section ul li a {
            color: #bdc3c7;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-section ul li a:hover {
            color: #3498db;
        }
        
        .footer-bottom {
            text-align: center;
            padding: 20px 0;
            border-top: 1px solid #34495e;
            color: #bdc3c7;
        }
        
        @media (max-width: 768px) {
            .main-nav {
                display: none !important;
            }
            
            .mobile-nav-toggle {
                display: block !important;
            }
            
            .mobile-nav-menu {
                display: block !important;
            }
            
            .mobile-nav-overlay {
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
            
            .search-box input {
                width: 200px;
                padding: 10px 40px 10px 12px;
            }
            
            .cart-button {
                padding: 10px 16px;
                font-size: 13px;
            }
            
            .wishlist-button {
                padding: 8px 13px;
                font-size: 11px;
            }
        }
        </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-left">
                <select class="currency-dropdown">
                    <option>U.S. Dollar ($)</option>
                    <option>Euro (€)</option>
                    <option>British Pound (£)</option>
                    <option>Japanese Yen (¥)</option>
                </select>
                <a href="#">Become A Seller</a>
                <a href="#">Seller Login</a>
                <a href="#">Be an affiliate partner</a>
            </div>
            <div class="top-bar-right">
                <a href="#">Helpline: +01 234 567 890</a>
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Registration</a>
            </div>
        </div>
    </div>

    <!-- Main Header -->
     
    <header class="main-header">
        <div class="container">
            <div class="header-content">
                <button class="mobile-nav-toggle" id="mobileNavToggle" aria-label="Toggle navigation">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                
                <a href="#" class="logo"><img src="{{ asset('storage/' . $headerLogo) }}" alt="Logo" style="height: 50px; width: auto;"></a>
                
                <nav>
                    <ul class="main-nav">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('flash-sale.index') }}">Flash Sale</a></li>
                        <li><a href="{{ route('products.index') }}">Products</a></li>
                        <li><a href="{{ route('categories.index') }}">Categories</a></li>
                        <li><a href="{{ route('sellers.index') }}">Sellers</a></li>
                        
                        
                    </ul>
                </nav>
                
                <div class="header-actions">
                    <div class="search-bar">
                        <input type="text" placeholder="Search products...">
                        <button type="submit">
                            <i class="fas fa-search" style="font-weight: 550; font-size: 16px;"></i>
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
                        <span class="cart-count">3</span>
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
            
            <div class="mobile-nav-section">
                <h4>Account</h4>
                <ul class="mobile-nav-links">
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Registration</a></li>
                    <li><a href="#">Become A Seller</a></li>
                    <li><a href="#">Seller Login</a></li>
                    <li><a href="#">Be an affiliate partner</a></li>
                </ul>
            </div>
            
            <div class="mobile-nav-section">
                <h4>Support</h4>
                <ul class="mobile-nav-links">
                    <li><a href="#">Helpline: +01 234 567 890</a></li>
                </ul>
            </div>
        </div>
        
        <div class="mobile-nav-actions">
            <div class="mobile-search-bar">
                <input type="text" placeholder="Search products...">
            </div>
            
            <div class="mobile-action-buttons">
                <a href="{{ route('wishlist.index') }}" class="mobile-action-buttons mobile-wishlist-btn">
                    <i class="fas fa-heart"></i> Wishlist
                </a>
                <a href="{{ route('cart.show') }}" class="mobile-action-buttons mobile-cart-btn">
                    <i class="fas fa-shopping-cart"></i> Cart
                </a>
            </div>
        </div>
    </div>
    
    <!-- Mobile Navigation Overlay -->
    <div class="mobile-nav-overlay" id="mobileNavOverlay"></div>

    <!-- Banner Slider -->
    @if($bannerImages->count() > 0)
        <section class="banner-slider" style="position: relative; overflow: hidden; background: #f8f9fa;">
            <div class="banner-container" id="bannerSlider" style="display: flex; transition: transform 0.5s ease;">
                @foreach($bannerImages as $index => $banner)
                    <div class="banner-slide" style="min-width: 100%; position: relative;">
                        @if($banner->image_path)
                            <img src="{{ asset('storage/' . $banner->image_path) }}" alt="{{ $banner->title ?? 'Banner' }}"
                                 style="width: 100%; height: 400px; object-fit: cover;"
                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/1200x400/f8f9fa/7f8c8d?text=Banner+Image';">
                        @else
                            <div style="width: 100%; height: 400px; background: linear-gradient(135deg, #3498db, #2980b9); display: flex; align-items: center; justify-content: center;">
                                <div style="text-align: center; color: white;">
                                    <h2 style="font-size: 2.5rem; margin-bottom: 10px;">{{ $banner->title ?? 'Welcome to LaraCom' }}</h2>
                                    <p style="font-size: 1.2rem;">{{ $banner->description ?? 'Your trusted online shopping destination' }}</p>
                                </div>
                            </div>
                        @endif>
                        
                        <!-- Banner Content Overlay with Reduced Opacity for Clearer Background -->
                        <style>
                            @keyframes slideInFromLeft {
                                from { opacity: 0; transform: translateX(-50px); }
                                to { opacity: 1; transform: translateX(0); }
                            }
                            @keyframes fadeInUp {
                                from { opacity: 0; transform: translateY(30px); }
                                to { opacity: 1; transform: translateY(0); }
                            }
                        </style>
                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(135deg, rgba(0,0,0,0.4) 0%, rgba(30,30,50,0.3) 100%); display: flex; align-items: center;">
                            <div class="container">
                                <div style="max-width: 600px; color: white; padding: 30px; background: rgba(255,255,255,0.08); border-radius: 15px; border: 1px solid rgba(255,255,255,0.15); box-shadow: 0 8px 32px rgba(0,0,0,0.15); animation: slideInFromLeft 0.8s ease-out;">
                                    @if($banner->title)
                                        <h2 style="font-size: 2.8rem; font-weight: 800; margin-bottom: 15px; text-shadow: 0 2px 10px rgba(0,0,0,0.5); letter-spacing: -0.5px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; animation: fadeInUp 0.8s ease-out 0.2s both;">{{ $banner->title }}</h2>
                                    @endif
                                    @if($banner->description)
                                        <p style="font-size: 1.3rem; margin-bottom: 25px; opacity: 0.95; line-height: 1.6; text-shadow: 0 1px 3px rgba(0,0,0,0.5); animation: fadeInUp 0.8s ease-out 0.4s both;">{{ $banner->description }}</p>
                                    @endif
                                    @if($banner->link_url)
                                        <a href="{{ $banner->link_url }}" style="display: inline-block; padding: 14px 35px; background: linear-gradient(45deg, #3498db, #2980b9); color: white; text-decoration: none; border-radius: 50px; font-weight: 700; transition: all 0.4s ease; box-shadow: 0 4px 15px rgba(52, 152, 219, 0.4); border: none; font-size: 1.1rem; animation: fadeInUp 0.8s ease-out 0.6s both;" 
                                           onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(52, 152, 219, 0.6)';" 
                                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(52, 152, 219, 0.4)';">
                                            Shop Now
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Slider Navigation -->
            @if($bannerImages->count() > 1)
                <button onclick="previousBanner()" style="position: absolute; left: 20px; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.9); border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 10px rgba(0,0,0,0.2);">
                    <i class="fas fa-chevron-left" style="color: #2c3e50;"></i>
                </button>
                <button onclick="nextBanner()" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.9); border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 10px rgba(0,0,0,0.2);">
                    <i class="fas fa-chevron-right" style="color: #2c3e50;"></i>
                </button>
                
                <!-- Slider Dots -->
                <div style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); display: flex; gap: 8px;">
                    @php $dotIndex = 0; @endphp
                    @foreach($bannerImages as $banner)
                        <button onclick="goToBanner({{ $dotIndex }})" style="width: 10px; height: 10px; border-radius: 50%; border: none; background: {{ $dotIndex == 0 ? 'white' : 'rgba(255,255,255,0.5)' }}; cursor: pointer;"></button>
                        @php $dotIndex++; @endphp
                    @endforeach
                </div>
            @endif
        </section>
    @endif

    <!-- Categories Section -->
    <section class="categories-section">
        <div class="container">
            <div class="section-header">
                <div class="section-title">
                <h3> Shop by Category</h3>
       
                <p class="section-subtitle">Find exactly what you're looking for</p>
                <!-- Debug Info -->
                <small style="color: #666; display: block; margin-top: 10px;">Categories loaded: {{ $categories->count() }}</small>
            </div>
            
            <!-- Category Slider Container -->
            <div class="category-slider-container">
                <!-- Navigation Buttons -->
                <button onclick="scrollCategories('left')" class="category-nav-btn category-nav-left">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button onclick="scrollCategories('right')" class="category-nav-btn category-nav-right">
                    <i class="fas fa-chevron-right"></i>
                </button>
                
                <!-- Category Slider -->
                <div class="category-slider" id="categorySlider">
                    @if($categories->count() > 0)
                        @foreach($categories as $category)
                            <a href="{{ route('categories.show', $category) }}" class="category-card category-scroll-item">
                                <div class="category-icon">
                                    @if($category->icon)
                                        <i class="{{ $category->icon }}" style="color: {{ $category->icon_color ?? '#3498db' }};"></i>
                                    @else
                                        <i class="fas fa-{{['tshirt', 'laptop', 'home', 'book', 'gamepad', 'dumbbell', 'car', 'music', 'heart', 'star', 'camera', 'phone', 'tablet', 'watch', 'shoe', 'pizza'][rand(0,14)]}}"></i>
                                    @endif
                                </div>
                                <div class="category-name">{{ $category->name }}</div>
                                <div class="category-count">{{ $category->products_count }} Products</div>
                            </a>
                        @endforeach
                    @else
                        <div style="text-align: center; padding: 60px 20px; color: #7f8c8d;">
                            <i class="fas fa-folder-open" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                            <p style="font-size: 18px; margin-bottom: 8px;">No categories available</p>
                            <p style="font-size: 14px;">Categories will appear here when products are added</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="products-section">
        <div class="container">
            <div class="section-header">
               <div class="section-title">
                <h3>Featured Products</h3>
                <p class="section-subtitle">Check out our most popular products</p>
            </div>
            
            <div class="product-grid">
                @if($featuredProducts->count() > 0)
                    @foreach($featuredProducts as $product)
                        <a href="{{ route('products.show', $product) }}" class="product-card" style="text-decoration: none; color: inherit;">
                            <div class="product-image">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                         onerror="this.onerror=null; this.src='https://via.placeholder.com/280x200/f8f9fa/7f8c8d?text=No+Image';">
                                @else
                                    <img src="https://via.placeholder.com/280x200/f8f9fa/7f8c8d?text=No+Image" alt="{{ $product->name }}">
                                @endif
                                @if($product->compare_price && $product->compare_price > $product->price)
                                    <span class="product-badge">-{{ round((1 - $product->price / $product->compare_price) * 100) }}%</span>
                                @endif
                            </div>
                            <div class="product-info">
                                <h3 class="product-name">{{ $product->name }}</h3>
                                <div class="product-rating">
                                    <div class="stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= ($product->rating ?? 4) ? '' : '-o' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="rating-count">({{ $product->reviews_count ?? 128 }})</span>
                                </div>
                                <div class="product-price">
                                    <span class="current-price">${{ number_format($product->price, 2) }}</span>
                                    @if($product->compare_price && $product->compare_price > $product->price)
                                        <span class="original-price">${{ number_format($product->compare_price, 2) }}</span>
                                        <span class="discount-percentage">-{{ round((1 - $product->price / $product->compare_price) * 100) }}%</span>
                                    @endif
                                </div>
                                <div style="display: flex; gap: 8px; margin-top: 15px;">
                                    <a href="{{ route('products.show', $product->id) }}" class="add-to-cart" onclick="event.stopPropagation();">
                                        <i class="fas fa-shopping-cart"></i> View Details
                                    </a>
                                    <button class="wishlist-toggle-btn" 
                                            data-wishlist-product="{{ $product->id }}"
                                            onclick="event.stopPropagation(); toggleWishlist({{ $product->id }})">
                                        <i class="far fa-heart"></i>
                                    </button>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @else
                    <!-- Static products for demo -->
                    <div class="product-card">
                        <div class="product-image">
                            <img src="https://via.placeholder.com/280x200" alt="Laptop">
                            <span class="product-badge">-25%</span>
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">Gaming Laptop Pro</h3>
                            <div class="product-rating">
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <span class="rating-count">(234)</span>
                            </div>
                            <div class="product-price">
                                <span class="current-price">$899.99</span>
                                <span class="original-price">$1199.99</span>
                                <span class="discount-percentage">-25%</span>
                            </div>
                            <div style="display: flex; gap: 8px; margin-top: 15px;">
                                <a href="#" class="add-to-cart" onclick="event.stopPropagation();">
                                    <i class="fas fa-shopping-cart"></i> View Details
                                </a>
                                <button class="wishlist-toggle-btn" 
                                        data-wishlist-product="0"
                                        onclick="event.stopPropagation(); toggleWishlist(0)">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Best Sellers Section -->
    <section class="products-section">
        <div class="container">
            <div class="section-header">
                  <div class="section-title">
                <h3 class="section-title">Best Sellers</h3>
                <p class="section-subtitle">Our most popular items this month</p>
            </div>
            
            <div class="product-grid">
                @if($bestSellers->count() > 0)
                    @foreach($bestSellers->take(8) as $product)
                        <a href="{{ route('products.show', $product) }}" class="product-card" style="text-decoration: none; color: inherit;">
                            <div class="product-image">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                         onerror="this.onerror=null; this.src='https://via.placeholder.com/280x200/f8f9fa/7f8c8d?text=No+Image';">
                                @else
                                    <img src="https://via.placeholder.com/280x200/f8f9fa/7f8c8d?text=No+Image" alt="{{ $product->name }}">
                                @endif
                                @if($product->compare_price && $product->compare_price > $product->price)
                                    <span class="product-badge">-{{ round((1 - $product->price / $product->compare_price) * 100) }}%</span>
                                @endif
                            </div>
                            <div class="product-info">
                                <h3 class="product-name">{{ $product->name }}</h3>
                                <div class="product-rating">
                                    <div class="stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= ($product->rating ?? 4) ? '' : '-o' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="rating-count">({{ $product->reviews_count ?? 128 }})</span>
                                </div>
                                <div class="product-price">
                                    <span class="current-price">${{ number_format($product->price, 2) }}</span>
                                    @if($product->compare_price && $product->compare_price > $product->price)
                                        <span class="original-price">${{ number_format($product->compare_price, 2) }}</span>
                                        <span class="discount-percentage">-{{ round((1 - $product->price / $product->compare_price) * 100) }}%</span>
                                    @endif
                                </div>
                                <div style="display: flex; gap: 8px; margin-top: 15px;">
                                    <a href="{{ route('products.show', $product->id) }}" class="add-to-cart" onclick="event.stopPropagation();">
                                        <i class="fas fa-shopping-cart"></i> View Details
                                    </a>
                                    <button class="wishlist-toggle-btn" 
                                            data-wishlist-product="{{ $product->id }}"
                                            onclick="event.stopPropagation(); toggleWishlist({{ $product->id }})">
                                        <i class="far fa-heart"></i>
                                    </button>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @else
                    <!-- Static best sellers for demo -->
                    <div class="product-card">
                        <div class="product-image">
                            <img src="https://via.placeholder.com/280x200" alt="Wireless Headphones">
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">Wireless Headphones</h3>
                            <div class="product-rating">
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <span class="rating-count">(456)</span>
                            </div>
                            <div class="product-price">
                                <span class="current-price">$79.99</span>
                            </div>
                            <div style="display: flex; gap: 8px; margin-top: 15px;">
                                <a href="#" class="add-to-cart" onclick="event.stopPropagation();">
                                    <i class="fas fa-shopping-cart"></i> View Details
                                </a>
                                <button class="wishlist-toggle-btn" 
                                        data-wishlist-product="0"
                                        onclick="event.stopPropagation(); toggleWishlist(0)">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter">
        <div class="container">
            <div class="newsletter-content">
                <div class="newsletter-badge">
                    <i class="fas fa-bolt"></i>
                    Instant Access
                </div>
                <p>Join 50,000+ shoppers getting curated product picks and exclusive offers each week—no spam, just great finds.</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Enter your best email" required>
                    <button type="submit">
                        <i class="fas fa-paper-plane"></i>
                        Get Updates
                    </button>
                </form>
                <div class="newsletter-footnote">
                    <i class="fas fa-shield-alt"></i>
                    We respect your inbox. Unsubscribe anytime.
                </div>
            </div>
        </div>
    </section>

    <!-- Main Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <a href="#" class="logo"><img src="{{ asset('storage/' . $footerLogo) }}" alt="Logo" style="height: 50px; width: auto;"></a>
                      <h3>About</h3>
                    <p>Your trusted e-commerce platform for quality products and great shopping experience.</p>
                </div>
                
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('products.index') }}">Products</a></li>
                        <li><a href="{{ route('categories.index') }}">Categories</a></li>
                        <li><a href="{{ route('flash-sale.index') }}">Flash Sale</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Customer Service</h3>
                    <ul>
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Track Order</a></li>
                        <li><a href="#">Returns</a></li>
                        <li><a href="#">Shipping Info</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Connect</h3>
                    <ul>
                        <li><a href="#">Facebook</a></li>
                        <li><a href="#">Twitter</a></li>
                        <li><a href="#">Instagram</a></li>
                        <li><a href="#">LinkedIn</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} LaraCMS. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Search functionality
        const searchForm = document.querySelector('.search-bar');
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const searchTerm = this.querySelector('input').value;
                console.log('Searching for:', searchTerm);
                // Implement search functionality
            });
        }

        // Newsletter form
        const newsletterForm = document.querySelector('.newsletter-form');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const email = this.querySelector('input').value;
                console.log('Newsletter subscription:', email);
                alert('Thank you for subscribing!');
                this.reset();
            });
        }

        // Currency dropdown
        const currencyDropdown = document.querySelector('.currency-dropdown');
        if (currencyDropdown) {
            currencyDropdown.addEventListener('change', function(e) {
                console.log('Currency changed to:', e.target.value);
                // Implement currency change functionality
            });
        }
    </script>
    
    <!-- Banner Slider JavaScript -->
    <script>
        let currentBanner = 0;
        const totalBanners = {{ $bannerImages->count() }};
        let autoSlideInterval;

        function showBanner(index) {
            const slider = document.getElementById('bannerSlider');
            if (slider) {
                slider.style.transform = `translateX(-${index * 100}%)`;
                
                // Update dots
                const dots = document.querySelectorAll('.banner-slider button[onclick^="goToBanner"]');
                dots.forEach((dot, i) => {
                    dot.style.background = i === index ? 'white' : 'rgba(255,255,255,0.5)';
                });
                
                currentBanner = index;
            }
        }

        function nextBanner() {
            currentBanner = (currentBanner + 1) % totalBanners;
            showBanner(currentBanner);
        }

        function previousBanner() {
            currentBanner = (currentBanner - 1 + totalBanners) % totalBanners;
            showBanner(currentBanner);
        }

        function goToBanner(index) {
            currentBanner = index;
            showBanner(index);
        }

        function startAutoSlide() {
            if (totalBanners > 1) {
                autoSlideInterval = setInterval(nextBanner, 5000); // Change banner every 5 seconds
            }
        }

        function stopAutoSlide() {
            if (autoSlideInterval) {
                clearInterval(autoSlideInterval);
            }
        }

        // Initialize slider
        document.addEventListener('DOMContentLoaded', function() {
            if (totalBanners > 0) {
                showBanner(0);
                startAutoSlide();
                
                // Pause auto-slide on hover
                const heroSection = document.querySelector('.hero-section');
                if (heroSection) {
                    heroSection.addEventListener('mouseenter', stopAutoSlide);
                    heroSection.addEventListener('mouseleave', startAutoSlide);
                }
            }
        });
    </script>
    
    <!-- Category Slider JavaScript -->
    <script>
        let categoryAutoScrollInterval;
        let categoryScrollDirection = 'right';
        
        function scrollCategories(direction) {
            const categorySlider = document.getElementById('categorySlider');
            if (!categorySlider) return;
            
            const scrollAmount = 220; // Scroll by card width + gap
            const currentScroll = categorySlider.scrollLeft;
            const maxScroll = categorySlider.scrollWidth - categorySlider.clientWidth;
            
            if (direction === 'left') {
                const newScroll = Math.max(0, currentScroll - scrollAmount);
                categorySlider.scrollTo({
                    left: newScroll,
                    behavior: 'smooth'
                });
            } else {
                const newScroll = Math.min(maxScroll, currentScroll + scrollAmount);
                categorySlider.scrollTo({
                    left: newScroll,
                    behavior: 'smooth'
                });
            }
            
            updateNavigationButtons();
        }
        
        function updateNavigationButtons() {
            const categorySlider = document.getElementById('categorySlider');
            if (!categorySlider) return;
            
            const currentScroll = categorySlider.scrollLeft;
            const maxScroll = categorySlider.scrollWidth - categorySlider.clientWidth;
            
            const leftBtn = document.querySelector('.category-nav-left');
            const rightBtn = document.querySelector('.category-nav-right');
            
            if (leftBtn) {
                leftBtn.style.display = 'flex';
                leftBtn.style.opacity = currentScroll <= 0 ? '0.5' : '1';
                leftBtn.style.cursor = currentScroll <= 0 ? 'not-allowed' : 'pointer';
            }
            
            if (rightBtn) {
                rightBtn.style.display = 'flex';
                rightBtn.style.opacity = currentScroll >= maxScroll ? '0.5' : '1';
                rightBtn.style.cursor = currentScroll >= maxScroll ? 'not-allowed' : 'pointer';
            }
        }
        
        function startCategoryAutoScroll() {
            const categorySlider = document.getElementById('categorySlider');
            if (!categorySlider) return;
            
            if (categorySlider.scrollWidth <= categorySlider.clientWidth) {
                return; // No need to scroll if content fits
            }
            
            categoryAutoScrollInterval = setInterval(() => {
                const currentScroll = categorySlider.scrollLeft;
                const maxScroll = categorySlider.scrollWidth - categorySlider.clientWidth;
                
                if (categoryScrollDirection === 'right') {
                    if (currentScroll >= maxScroll - 10) {
                        categorySlider.scrollTo({
                            left: 0,
                            behavior: 'smooth'
                        });
                    } else {
                        scrollCategories('right');
                    }
                }
            }, 3000); // Auto-scroll every 3 seconds
        }
        
        function stopCategoryAutoScroll() {
            if (categoryAutoScrollInterval) {
                clearInterval(categoryAutoScrollInterval);
            }
        }
        
        function resetCategoryAutoScroll() {
            stopCategoryAutoScroll();
            startCategoryAutoScroll();
        }
        
        // Initialize category slider
        document.addEventListener('DOMContentLoaded', function() {
            const categorySlider = document.getElementById('categorySlider');
            if (!categorySlider) return;
            
            setTimeout(startCategoryAutoScroll, 2000);
            
            const categoryContainer = document.querySelector('.category-slider-container');
            if (categoryContainer) {
                categoryContainer.addEventListener('mouseenter', stopCategoryAutoScroll);
                categoryContainer.addEventListener('mouseleave', startCategoryAutoScroll);
            }
            
            categorySlider.addEventListener('scroll', updateNavigationButtons);
            updateNavigationButtons();
            window.addEventListener('resize', updateNavigationButtons);
        });

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
                    showAlert('Product added to cart successfully!', 'success');
                    // Update cart count if function exists
                    if (window.updateCartCount) {
                        window.updateCartCount();
                    }
                } else {
                    showAlert(data.message || 'Error adding product to cart', 'error');
                }
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                showAlert('Error adding product to cart', 'error');
            });
        }

        // Update cart count function
        function updateCartCount() {
            fetch('/cart/count')
                .then(response => response.json())
                .then(data => {
                    const cartCountElement = document.querySelector('.cart-button .cart-count');
                    if (cartCountElement) {
                        cartCountElement.textContent = data.count;
                    }
                })
                .catch(error => {
                    console.error('Error loading cart count:', error);
                });
        }

        // Load wishlist count function
        function loadWishlistCount() {
            fetch('/wishlist/count')
                .then(response => response.json())
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

        // Wishlist functionality
        function toggleWishlist(productId) {
            console.log('Toggling wishlist for product:', productId);
            
            if (!authCheck()) {
                showAlert('Please login to add items to wishlist', 'error');
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
                    showAlert(data.message, 'success');
                    updateWishlistCount(data.wishlist_count);
                    updateWishlistButton(productId, data.in_wishlist);
                } else {
                    showAlert(data.message || 'Error updating wishlist', 'error');
                }
            })
            .catch(error => {
                console.error('Error toggling wishlist:', error);
                showAlert('Error updating wishlist. Please try again.', 'error');
            });
        }

        function updateWishlistCount(count) {
            const countElement = document.getElementById('header-wishlist-count');
            if (countElement) {
                countElement.textContent = count;
            }
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

        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 20px;
                border-radius: 8px;
                z-index: 9999;
                font-weight: 500;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                transition: all 0.3s ease;
                ${type === 'success' ? 
                    'background: #d4edda; color: #155724; border: 1px solid #c3e6cb;' : 
                    'background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;'
                }
            `;
            alertDiv.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i> ${message}`;
            
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                alertDiv.style.opacity = '0';
                setTimeout(() => alertDiv.remove(), 300);
            }, 3000);
        }

        // Initialize cart and wishlist functionality
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
            loadWishlistCount();
            
            @if(auth()->check())
                // Get user's wishlist items and initialize button states
                fetch('/wishlist', {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const wishlistProductIds = data.items ? data.items.map(item => item.product_id) : [];
                    
                    // Update wishlist buttons state
                    document.querySelectorAll('.wishlist-toggle-btn').forEach(button => {
                        const productId = button.getAttribute('data-wishlist-product');
                        if (wishlistProductIds.includes(productId)) {
                            button.classList.add('in-wishlist');
                            button.innerHTML = '<i class="fas fa-heart"></i>';
                        }
                    });
                })
                .catch(error => {
                    console.error('Error initializing wishlist buttons:', error);
                });
            @endif
        });
        
        // Mobile Navigation functionality - Ensure DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Wait a bit to ensure all elements are loaded
            setTimeout(function() {
                const mobileNavToggle = document.getElementById('mobileNavToggle');
                const mobileNavMenu = document.getElementById('mobileNavMenu');
                const mobileNavClose = document.getElementById('mobileNavClose');
                const mobileNavOverlay = document.getElementById('mobileNavOverlay');
                
                console.log('Mobile nav elements found:', {
                    toggle: !!mobileNavToggle,
                    menu: !!mobileNavMenu,
                    close: !!mobileNavClose,
                    overlay: !!mobileNavOverlay,
                    toggleElement: mobileNavToggle,
                    menuElement: mobileNavMenu
                });
                
                if (!mobileNavToggle || !mobileNavMenu || !mobileNavClose || !mobileNavOverlay) {
                    console.error('Mobile navigation elements not found!');
                    return;
                }
                
                function openMobileNav() {
                    console.log('Opening mobile nav');
                    // Force remove any existing active classes first and set display to block
                    mobileNavMenu.className = mobileNavMenu.className.replace(/\bactive\b/g, '').trim() + ' active';
                    mobileNavMenu.style.display = 'block';
                    mobileNavOverlay.className = mobileNavOverlay.className.replace(/\bactive\b/g, '').trim() + ' active';
                    mobileNavToggle.className = mobileNavToggle.className.replace(/\bactive\b/g, '').trim() + ' active';
                    document.body.style.overflow = 'hidden';
                    console.log('Classes after open:', {
                        menu: mobileNavMenu.className,
                        overlay: mobileNavOverlay.className,
                        toggle: mobileNavToggle.className
                    });
                }
                
                function closeMobileNav() {
                    console.log('Closing mobile nav');
                    // Force remove active classes using regex and set display to none
                    mobileNavMenu.className = mobileNavMenu.className.replace(/\bactive\b/g, '').trim();
                    mobileNavMenu.style.display = 'none';
                    mobileNavOverlay.className = mobileNavOverlay.className.replace(/\bactive\b/g, '').trim();
                    mobileNavToggle.className = mobileNavToggle.className.replace(/\bactive\b/g, '').trim();
                    document.body.style.overflow = '';
                    console.log('Classes after close:', {
                        menu: mobileNavMenu.className,
                        overlay: mobileNavOverlay.className,
                        toggle: mobileNavToggle.className
                    });
                }
                
                // Remove existing listeners to prevent duplicates
                mobileNavToggle.replaceWith(mobileNavToggle.cloneNode(true));
                const newToggle = document.getElementById('mobileNavToggle');
                
                newToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Hamburger clicked - event fired');
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
                
                console.log('Mobile navigation event listeners attached successfully');
            }, 100);
        });
    </script>
    
</body>
</html>
