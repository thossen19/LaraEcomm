<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Seller Dashboard - {{ config('app.name', 'LaraCom') }}</title>
    
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
            background: #27ae60 !important;
            color: white !important;
            padding: 20px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid #2ecc71;
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
            background: #2ecc71 !important;
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
            background: #27ae60 !important;
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
            border-left: 4px solid #27ae60;
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
            color: #27ae60 !important;
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
            background: #27ae60 !important;
            color: white !important;
        }
        
        .btn-primary:hover {
            background: #229954 !important;
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
                <h2>Seller Panel</h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="#" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="#"><i class="fas fa-box"></i> My Products</a></li>
                <li><a href="#"><i class="fas fa-plus-circle"></i> Add Product</a></li>
                <li><a href="#"><i class="fas fa-shopping-cart"></i> Orders</a></li>
                <li><a href="#"><i class="fas fa-chart-line"></i> Sales Report</a></li>
                <li><a href="#"><i class="fas fa-store"></i> Shop Settings</a></li>
                <li><a href="#"><i class="fas fa-star"></i> Reviews</a></li>
                <li><a href="#"><i class="fas fa-bullhorn"></i> Promotions</a></li>
                <li><a href="#"><i class="fas fa-wallet"></i> Earnings</a></li>
                <li><a href="#"><i class="fas fa-cog"></i> Settings</a></li>
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
                <h1>Seller Dashboard</h1>
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
                    <h3>Total Products</h3>
                    <div class="number">45</div>
                    <div class="change">+5% from last month</div>
                </div>
                <div class="stat-card">
                    <h3>Total Orders</h3>
                    <div class="number">123</div>
                    <div class="change">+18% from last month</div>
                </div>
                <div class="stat-card">
                    <h3>Total Revenue</h3>
                    <div class="number">$8,765</div>
                    <div class="change">+22% from last month</div>
                </div>
                <div class="stat-card">
                    <h3>Avg Rating</h3>
                    <div class="number">4.8</div>
                    <div class="change">+0.2 from last month</div>
                </div>
            </div>
            
            <!-- Recent Orders -->
            <div class="content-card">
                <h2>Recent Orders</h2>
                <p>No recent orders to display.</p>
            </div>
            
            <!-- Quick Actions -->
            <div class="content-card">
                <h2>Quick Actions</h2>
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <a href="#" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Add New Product
                    </a>
                    <a href="#" class="btn btn-primary">
                        <i class="fas fa-chart-line"></i> View Sales Report
                    </a>
                    <a href="#" class="btn btn-primary">
                        <i class="fas fa-bullhorn"></i> Create Promotion
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
