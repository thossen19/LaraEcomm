<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Dashboard - {{ config('app.name', 'LaraCom') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
            background: #f8f9fa !important;
            color: #2c3e50 !important;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            background: #667eea !important;
            color: white !important;
            padding: 20px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid #764ba2;
            margin-bottom: 20px;
        }
        
        .sidebar-header h2 {
            font-size: 24px !important;
            font-weight: 800 !important;
            color: #f39c12 !important;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
        }
        
        .sidebar-menu li {
            margin-bottom: 5px;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #ecf0f1 !important;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: #764ba2 !important;
            color: white !important;
        }
        
        .sidebar-menu i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }
        
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
        }
        
        .header {
            background: white !important;
            padding: 20px 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            font-size: 28px !important;
            font-weight: 800 !important;
            color: #2c3e50 !important;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #667eea !important;
            color: white !important;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white !important;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-left: 4px solid #667eea;
        }
        
        .stat-card h3 {
            font-size: 14px;
            color: #7f8c8d !important;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-weight: 600;
        }
        
        .stat-card .number {
            font-size: 32px !important;
            font-weight: 800 !important;
            color: #2c3e50 !important;
            margin-bottom: 5px;
        }
        
        .stat-card .change {
            font-size: 12px;
            color: #667eea !important;
        }
        
        .content-card {
            background: white !important;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }
        
        .content-card h2 {
            font-size: 20px !important;
            font-weight: 700 !important;
            color: #2c3e50 !important;
            margin-bottom: 20px;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: none;
        }
        
        .btn-primary {
            background: #667eea !important;
            color: white !important;
        }
        
        .btn-primary:hover {
            background: #5a67d8 !important;
        }
        
        .btn-danger {
            background: #e74c3c !important;
            color: white !important;
        }
        
        .btn-danger:hover {
            background: #c0392b !important;
        }
        
        .alert {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>My Account</h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="{{ route('customer.dashboard') }}" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="{{ route('profile.show') }}"><i class="fas fa-user"></i> Profile</a></li>
                <li><a href="{{ route('orders.index') }}"><i class="fas fa-shopping-cart"></i> My Orders</a></li>
                <li><a href="{{ route('wishlist.index') }}"><i class="fas fa-heart"></i> Wishlist</a></li>
                <li><a href="{{ route('addresses.index') }}"><i class="fas fa-map-marker-alt"></i> Addresses</a></li>
                <li><a href="{{ route('payments.index') }}"><i class="fas fa-credit-card"></i> Payment Methods</a></li>
                <li><a href="{{ route('reviews.index') }}"><i class="fas fa-star"></i> Reviews</a></li>
                <li><a href="{{ route('notifications.index') }}"><i class="fas fa-bell"></i> Notifications</a></li>
                <li><a href="{{ route('settings.index') }}"><i class="fas fa-cog"></i> Settings</a></li>
            </ul>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <!-- Header -->
            <header class="header">
                <h1>Customer Dashboard</h1>
                <div class="user-info">
                    <span>Welcome, {{ Auth::user()->name }}</span>
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </header>
            
            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Orders</h3>
                    <div class="number">12</div>
                    <div class="change">2 orders this month</div>
                </div>
                <div class="stat-card">
                    <h3>Wishlist Items</h3>
                    <div class="number">8</div>
                    <div class="change">3 new items</div>
                </div>
                <div class="stat-card">
                    <h3>Total Spent</h3>
                    <div class="number">$456</div>
                    <div class="change">$89 this month</div>
                </div>
                <div class="stat-card">
                    <h3>Loyalty Points</h3>
                    <div class="number">234</div>
                    <div class="change">+15 points earned</div>
                </div>
            </div>
            
            <!-- Recent Orders -->
            <div class="content-card">
                <h2>Recent Orders</h2>
                <p>No recent orders to display.</p>
            </div>
            
            <!-- Recommended Products -->
            <div class="content-card">
                <h2>Recommended for You</h2>
                <p>Based on your browsing history and preferences.</p>
            </div>
        </main>
    </div>
</body>
</html>
