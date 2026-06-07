@extends('dashboard.admin')

@section('title', 'Products Report')

@section('content')
<div class="content-card">
    <h2>Products Report</h2>
    
    <!-- Product Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Products</h3>
            <div class="number">{{ $productStats['total'] }}</div>
            <div class="change">{{ $productStats['active'] }} active</div>
        </div>
        <div class="stat-card">
            <h3>Active Products</h3>
            <div class="number">{{ $productStats['active'] }}</div>
            <div class="change">{{ $productStats['inactive'] }} inactive</div>
        </div>
        <div class="stat-card">
            <h3>Out of Stock</h3>
            <div class="number">{{ $productStats['out_of_stock'] }}</div>
            <div class="change">{{ $productStats['low_stock'] }} low stock</div>
        </div>
        <div class="stat-card">
            <h3>Average Price</h3>
            <div class="number">${{ number_format($productStats['avg_price'] ?? 0, 2) }}</div>
            <div class="change">Per product</div>
        </div>
    </div>

    <!-- Back to Reports -->
    <div style="margin: 20px 0;">
        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Reports
        </a>
    </div>

    <!-- Products Table -->
    <div class="content-card">
        <h3>All Products</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 1px solid #dee2e6;">
                        <th style="padding: 10px; text-align: left;">ID</th>
                        <th style="padding: 10px; text-align: left;">Product</th>
                        <th style="padding: 10px; text-align: left;">SKU</th>
                        <th style="padding: 10px; text-align: left;">Categories</th>
                        <th style="padding: 10px; text-align: left;">Price</th>
                        <th style="padding: 10px; text-align: left;">Stock</th>
                        <th style="padding: 10px; text-align: left;">Status</th>
                        <th style="padding: 10px; text-align: left;">Created</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr style="border-bottom: 1px solid #dee2e6;">
                            <td style="padding: 10px;">#{{ $product->id }}</td>
                            <td style="padding: 10px;">
                                <div style="font-weight: 500;">{{ $product->name }}</div>
                                @if($product->short_description)
                                    <div style="font-size: 12px; color: #7f8c8d; margin-top: 2px;">
                                        {{ Str::limit($product->short_description, 50) }}
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 10px;">{{ $product->sku ?? 'N/A' }}</td>
                            <td style="padding: 10px;">
                                @if($product->categories->count() > 0)
                                    @foreach($product->categories->take(2) as $category)
                                        <span style="background: #3498db; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px; margin-right: 2px;">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                    @if($product->categories->count() > 2)
                                        <span style="color: #7f8c8d; font-size: 11px;">
                                            +{{ $product->categories->count() - 2 }} more
                                        </span>
                                    @endif
                                @else
                                    <span style="color: #7f8c8d; font-size: 11px;">No categories</span>
                                @endif
                            </td>
                            <td style="padding: 10px;">${{ number_format($product->price, 2) }}</td>
                            <td style="padding: 10px;">
                                @if($product->stock_quantity <= 0)
                                    <span style="background: #e74c3c; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px;">
                                        Out of Stock
                                    </span>
                                @elseif($product->stock_quantity <= 10)
                                    <span style="background: #f39c12; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px;">
                                        {{ $product->stock_quantity }} (Low)
                                    </span>
                                @else
                                    <span style="background: #27ae60; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px;">
                                        {{ $product->stock_quantity }}
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 10px;">
                                @if($product->is_active)
                                    <span style="background: #27ae60; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px;">
                                        Active
                                    </span>
                                @else
                                    <span style="background: #7f8c8d; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px;">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 10px;">{{ $product->created_at ? $product->created_at->format('M d, Y') : 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="padding: 20px; text-align: center; color: #7f8c8d;">
                                No products found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($products->hasPages())
            <div style="margin-top: 20px; text-align: center;">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
