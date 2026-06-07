@extends('dashboard.admin')

@section('title', 'Orders Report')

@section('content')
<div class="content-card">
    <h2>Orders Report</h2>
    
    <!-- Order Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Orders</h3>
            <div class="number">{{ $orderStats['total'] }}</div>
            <div class="change">{{ $orderStats['pending'] }} pending</div>
        </div>
        <div class="stat-card">
            <h3>Processing</h3>
            <div class="number">{{ $orderStats['processing'] }}</div>
            <div class="change">{{ $orderStats['shipped'] }} shipped</div>
        </div>
        <div class="stat-card">
            <h3>Delivered</h3>
            <div class="number">{{ $orderStats['delivered'] }}</div>
            <div class="change">{{ $orderStats['cancelled'] }} cancelled</div>
        </div>
        <div class="stat-card">
            <h3>Total Revenue</h3>
            <div class="number">${{ number_format($orderStats['total_revenue'] ?? 0, 2) }}</div>
            <div class="change">All time</div>
        </div>
    </div>

    <!-- Back to Reports -->
    <div style="margin: 20px 0;">
        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Reports
        </a>
    </div>

    <!-- Orders Table -->
    <div class="content-card">
        <h3>All Orders</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 1px solid #dee2e6;">
                        <th style="padding: 10px; text-align: left;">Order #</th>
                        <th style="padding: 10px; text-align: left;">Customer</th>
                        <th style="padding: 10px; text-align: left;">Date</th>
                        <th style="padding: 10px; text-align: left;">Total</th>
                        <th style="padding: 10px; text-align: left;">Status</th>
                        <th style="padding: 10px; text-align: left;">Payment</th>
                        <th style="padding: 10px; text-align: left;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr style="border-bottom: 1px solid #dee2e6;">
                            <td style="padding: 10px;">
                                <div style="font-weight: 500;">{{ $order->order_number }}</div>
                            </td>
                            <td style="padding: 10px;">
                                @if($order->user)
                                    <div>{{ $order->user->name }}</div>
                                    <div style="font-size: 12px; color: #7f8c8d;">{{ $order->user->email }}</div>
                                @else
                                    <span style="color: #7f8c8d;">Guest</span>
                                @endif
                            </td>
                            <td style="padding: 10px;">{{ $order->created_at ? $order->created_at->format('M d, Y') : 'N/A' }}</td>
                            <td style="padding: 10px; font-weight: 600;">${{ number_format($order->total_amount, 2) }}</td>
                            <td style="padding: 10px;">
                                @switch($order->status)
                                    @case('pending')
                                        <span style="background: #f39c12; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px;">
                                            Pending
                                        </span>
                                        @break
                                    @case('processing')
                                        <span style="background: #3498db; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px;">
                                            Processing
                                        </span>
                                        @break
                                    @case('shipped')
                                        <span style="background: #9b59b6; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px;">
                                            Shipped
                                        </span>
                                        @break
                                    @case('delivered')
                                        <span style="background: #27ae60; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px;">
                                            Delivered
                                        </span>
                                        @break
                                    @case('cancelled')
                                        <span style="background: #e74c3c; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px;">
                                            Cancelled
                                        </span>
                                        @break
                                    @default
                                        <span style="background: #7f8c8d; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px;">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                @endswitch
                            </td>
                            <td style="padding: 10px;">
                                @if($order->payment_status === 'paid')
                                    <span style="background: #27ae60; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px;">
                                        Paid
                                    </span>
                                @else
                                    <span style="background: #f39c12; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px;">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 10px;">
                                <div style="display: flex; gap: 5px;">
                                    <a href="#" onclick="viewOrderDetails({{ $order->id }})" style="color: #3498db; text-decoration: none; font-size: 12px;">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    @if($order->status === 'pending')
                                        <a href="#" onclick="updateOrderStatus({{ $order->id }}, 'processing')" style="color: #27ae60; text-decoration: none; font-size: 12px;">
                                            <i class="fas fa-play"></i> Process
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 20px; text-align: center; color: #7f8c8d;">
                                No orders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($orders->hasPages())
            <div style="margin-top: 20px; text-align: center;">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>

<script>
function viewOrderDetails(orderId) {
    // This would open a modal or navigate to order details page
    alert('Order details view would be implemented here for order #' + orderId);
}

function updateOrderStatus(orderId, newStatus) {
    if (confirm('Are you sure you want to update this order status to ' + newStatus + '?')) {
        // This would make an AJAX call to update the order status
        fetch('/admin/orders/' + orderId + '/status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error updating order status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating order status');
        });
    }
}
</script>

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
