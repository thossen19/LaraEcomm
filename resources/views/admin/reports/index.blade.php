@extends('dashboard.admin')

@section('title', 'Reports Dashboard')

@section('content')
<div class="content-card">
    <h2>Reports Dashboard</h2>
    
    <!-- Overview Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Users</h3>
            <div class="number">{{ $stats['total_users'] }}</div>
            <div class="change">{{ $stats['active_users'] }} active</div>
        </div>
        <div class="stat-card">
            <h3>Total Orders</h3>
            <div class="number">{{ $stats['total_orders'] }}</div>
            <div class="change">{{ $stats['pending_orders'] }} pending</div>
        </div>
        <div class="stat-card">
            <h3>Total Products</h3>
            <div class="number">{{ $stats['total_products'] }}</div>
            <div class="change">{{ $stats['active_products'] }} active</div>
        </div>
        <div class="stat-card">
            <h3>Total Shops</h3>
            <div class="number">{{ $stats['total_shops'] }}</div>
            <div class="change">{{ $stats['active_shops'] }} active</div>
        </div>
    </div>

    <!-- Quick Links -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 30px 0;">
        <a href="{{ route('admin.reports.users') }}" class="btn btn-primary" style="display: block; text-align: center; padding: 15px;">
            <i class="fas fa-users"></i><br>
            Users Report
        </a>
        <a href="{{ route('admin.reports.orders') }}" class="btn btn-primary" style="display: block; text-align: center; padding: 15px;">
            <i class="fas fa-shopping-cart"></i><br>
            Orders Report
        </a>
        <a href="{{ route('admin.reports.products') }}" class="btn btn-primary" style="display: block; text-align: center; padding: 15px;">
            <i class="fas fa-box"></i><br>
            Products Report
        </a>
        <a href="{{ route('admin.reports.sales') }}" class="btn btn-primary" style="display: block; text-align: center; padding: 15px;">
            <i class="fas fa-chart-line"></i><br>
            Sales Report
        </a>
    </div>

    <!-- Recent Orders -->
    <div class="content-card">
        <h3>Recent Orders</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 1px solid #dee2e6;">
                        <th style="padding: 10px; text-align: left;">Order ID</th>
                        <th style="padding: 10px; text-align: left;">Customer</th>
                        <th style="padding: 10px; text-align: left;">Total</th>
                        <th style="padding: 10px; text-align: left;">Status</th>
                        <th style="padding: 10px; text-align: left;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr style="border-bottom: 1px solid #dee2e6;">
                            <td style="padding: 10px;">#{{ $order->id }}</td>
                            <td style="padding: 10px;">{{ $order->user ? $order->user->name : 'N/A' }}</td>
                            <td style="padding: 10px;">${{ number_format($order->total ?? 0, 2) }}</td>
                            <td style="padding: 10px;">
                                <span style="background: #3498db; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px;">
                                    {{ ucfirst($order->status ?? 'pending') }}
                                </span>
                            </td>
                            <td style="padding: 10px;">{{ $order->created_at ? $order->created_at->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 20px; text-align: center; color: #7f8c8d;">
                                No recent orders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Products -->
    <div class="content-card">
        <h3>Top Selling Products</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 1px solid #dee2e6;">
                        <th style="padding: 10px; text-align: left;">Product</th>
                        <th style="padding: 10px; text-align: left;">Category</th>
                        <th style="padding: 10px; text-align: left;">Price</th>
                        <th style="padding: 10px; text-align: left;">Sales</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topProducts as $product)
                        <tr style="border-bottom: 1px solid #dee2e6;">
                            <td style="padding: 10px; font-weight: 500;">{{ $product->name }}</td>
                            <td style="padding: 10px;">{{ $product->category ? $product->category->name : 'N/A' }}</td>
                            <td style="padding: 10px;">${{ number_format($product->price, 2) }}</td>
                            <td style="padding: 10px;">
                                <span style="background: #27ae60; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px;">
                                    {{ $product->order_items_count ?? 0 }} sales
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 20px; text-align: center; color: #7f8c8d;">
                                No sales data available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
