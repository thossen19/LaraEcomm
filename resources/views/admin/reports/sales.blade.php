@extends('dashboard.admin')

@section('title', 'Sales Report')

@section('content')
<div class="content-card">
    <h2>Sales Report</h2>
    
    <!-- Back to Reports -->
    <div style="margin: 20px 0;">
        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Reports
        </a>
    </div>

    <!-- Monthly Sales Chart -->
    <div class="content-card" style="margin-bottom: 30px;">
        <h3>Monthly Sales Trend</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 1px solid #dee2e6;">
                        <th style="padding: 10px; text-align: left;">Month</th>
                        <th style="padding: 10px; text-align: left;">Year</th>
                        <th style="padding: 10px; text-align: right;">Sales</th>
                        <th style="padding: 10px; text-align: right;">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monthlySales as $sale)
                        <tr style="border-bottom: 1px solid #dee2e6;">
                            <td style="padding: 10px;">{{ date('F', mktime(0, 0, 0, $sale->month, 1)) }}</td>
                            <td style="padding: 10px;">{{ $sale->year }}</td>
                            <td style="padding: 10px; text-align: right;">
                                <!-- This would show order count if we had it -->
                                --
                            </td>
                            <td style="padding: 10px; text-align: right; font-weight: 600; color: #27ae60;">
                                ${{ number_format($sale->total, 2) }}
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

    <!-- Top Selling Products -->
    <div class="content-card">
        <h3>Top Selling Products</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 1px solid #dee2e6;">
                        <th style="padding: 10px; text-align: left;">Product</th>
                        <th style="padding: 10px; text-align: left;">Category</th>
                        <th style="padding: 10px; text-align: right;">Price</th>
                        <th style="padding: 10px; text-align: right;">Sales Count</th>
                        <th style="padding: 10px; text-align: right;">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topSellingProducts as $product)
                        <tr style="border-bottom: 1px solid #dee2e6;">
                            <td style="padding: 10px;">
                                <div style="font-weight: 500;">{{ $product->name }}</div>
                                @if($product->short_description)
                                    <div style="font-size: 12px; color: #7f8c8d; margin-top: 2px;">
                                        {{ Str::limit($product->short_description, 50) }}
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 10px;">
                                @if($product->categories && $product->categories->count() > 0)
                                    {{ $product->categories->first()->name }}
                                @else
                                    <span style="color: #7f8c8d;">No category</span>
                                @endif
                            </td>
                            <td style="padding: 10px; text-align: right;">${{ number_format($product->price, 2) }}</td>
                            <td style="padding: 10px; text-align: right;">
                                <span style="background: #3498db; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px;">
                                    {{ $product->order_items_count ?? 0 }}
                                </span>
                            </td>
                            <td style="padding: 10px; text-align: right; font-weight: 600; color: #27ae60;">
                                ${{ number_format(($product->price * ($product->order_items_count ?? 0)), 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 20px; text-align: center; color: #7f8c8d;">
                                No sales data available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.btn {
    padding: 12px 20px;
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
    gap: 8px;
}

.btn-secondary {
    background: #6c757d !important;
    color: white !important;
    box-shadow: 0 2px 8px rgba(108, 117, 125, 0.3);
}

.btn-secondary:hover {
    background: #5a6268 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(108, 117, 125, 0.4);
}
</style>
@endsection
