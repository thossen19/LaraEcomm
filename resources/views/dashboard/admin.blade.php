<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - {{ config('app.name', 'LaraCom') }}</title>
    
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
            background: #2c3e50 !important;
            color: white !important;
            padding: 20px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid #34495e;
            margin-bottom: 20px;
        }
        
        .sidebar-header h2 {
            font-size: 24px !important;
            font-weight: 800 !important;
            color: #3498db !important;
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
            background: #3498db !important;
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
            background: #3498db !important;
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
            border-left: 4px solid #3498db;
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
            background: #3498db !important;
            color: white !important;
        }
        
        .btn-primary:hover {
            background: #2980b9 !important;
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
        
        /* Submenu Styles */
        .submenu {
            display: none;
            list-style: none;
            padding-left: 20px;
            margin: 0;
        }
        
        .submenu.show {
            display: block;
        }
        
        .submenu li a {
            padding: 8px 16px;
            display: block;
            color: #7f8c8d;
            text-decoration: none;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        
        .submenu li a:hover {
            background: rgba(52, 152, 219, 0.1);
            color: #3498db;
        }
        
        .submenu li a.active {
            background: rgba(52, 152, 219, 0.2);
            color: #3498db;
        }
        
        .submenu-toggle {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .submenu-toggle .fa-chevron-down {
            transition: transform 0.3s ease;
        }
        
        .submenu-toggle.open .fa-chevron-down {
            transform: rotate(180deg);
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
    <script>
        // Prevent automatic focus issues
        document.addEventListener('DOMContentLoaded', function() {
            // Remove focus from any input elements on page load
            if (document.activeElement && document.activeElement.tagName === 'INPUT') {
                document.activeElement.blur();
            }
            
            // Ensure body gets focus instead of URL bar
            document.body.focus();
            
            // Prevent focus from jumping to URL bar
            window.addEventListener('load', function() {
                setTimeout(function() {
                    document.activeElement.blur();
                    document.body.setAttribute('tabindex', '-1');
                    document.body.focus();
                }, 100);
            });
            
            // Submenu toggle functionality
            const submenuToggles = document.querySelectorAll('.submenu-toggle');
            
            submenuToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Toggle the submenu
                    const submenu = this.nextElementSibling;
                    if (submenu && submenu.classList.contains('submenu')) {
                        submenu.classList.toggle('show');
                        this.classList.toggle('open');
                    }
                });
            });
        });
    </script>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Admin Panel</h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="#" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="{{ route('admin.users.index') }}"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="{{ route('admin.orders.index') }}"><i class="fas fa-shopping-cart"></i> Orders</a></li>
                <li><a href="{{ route('admin.products.index') }}"><i class="fas fa-box"></i> Products</a></li>
                <li><a href="{{ route('admin.categories.index') }}"><i class="fas fa-tags"></i> Categories</a></li>
                <li><a href="{{ route('admin.shops.index') }}"><i class="fas fa-store"></i> Shops</a></li>
                <li><a href="#" class="submenu-toggle"><i class="fas fa-bullhorn"></i> Marketing <i class="fas fa-chevron-down"></i></a>
                    <ul class="submenu">
                        <li><a href="{{ route('admin.flash-sale.index') }}"><i class="fas fa-bolt"></i> Flash Sale</a></li>
                        <li><a href="#"><i class="fas fa-percentage"></i> Coupons</a></li>
                        <li><a href="#"><i class="fas fa-envelope"></i> Email Campaigns</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('admin.reports.index') }}"><i class="fas fa-chart-line"></i> Reports</a></li>
                <li><a href="{{ route('admin.settings.index') }}"><i class="fas fa-cog"></i> Settings</a></li>
                <li><a href="#"><i class="fas fa-bell"></i> Notifications</a></li>
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
                <h1>Admin Dashboard</h1>
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
            
            @yield('content')
            
            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Users</h3>
                    <div class="number">1,234</div>
                    <div class="change">+12% from last month</div>
                </div>
                <div class="stat-card">
                    <h3>Total Orders</h3>
                    <div class="number">567</div>
                    <div class="change">+8% from last month</div>
                </div>
                <div class="stat-card">
                    <h3>Total Revenue</h3>
                    <div class="number">$12,345</div>
                    <div class="change">+15% from last month</div>
                </div>
                <div class="stat-card">
                    <h3>Active Shops</h3>
                    <div class="number">89</div>
                    <div class="change">+3% from last month</div>
                </div>
            </div>
            
            <!-- Recent Orders -->
            <div class="content-card">
                <h2>Recent Orders</h2>
                <p>No recent orders to display.</p>
            </div>
            
            <!-- System Status -->
            <div class="content-card">
                <h2>System Status</h2>
                <p>All systems are running normally.</p>
            </div>
        </main>
    </div>
</body>
</html>
