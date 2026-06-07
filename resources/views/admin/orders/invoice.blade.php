<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            line-height: 1.6;
            color: #1f2937;
            background: #f0f2f5;
        }

        .invoice-wrapper {
            max-width: 820px;
            margin: 40px auto;
        }

        .invoice-container {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.06);
            overflow: hidden;
        }

        .invoice-header {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            padding: 40px;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .header-left .store-name {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 4px;
        }

        .header-left .store-tagline {
            font-size: 13px;
            color: #94a3b8;
            font-weight: 400;
        }

        .header-right {
            text-align: right;
        }

        .header-right .invoice-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #94a3b8;
            margin-bottom: 4px;
        }

        .header-right .invoice-number {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.3px;
        }

        .header-right .invoice-date {
            font-size: 13px;
            color: #cbd5e1;
            margin-top: 4px;
        }

        .status-row {
            padding: 14px 40px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 12px;
        }

        .status-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            font-weight: 600;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 18px;
            border-radius: 100px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending { background: #fef3c7; color: #92400e; }
        .status-processing { background: #dbeafe; color: #1e40af; }
        .status-shipped { background: #e0e7ff; color: #4338ca; }
        .status-delivered { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        .status-refunded { background: #f3e8ff; color: #6b21a8; }

        .invoice-body {
            padding: 40px;
        }

        .addresses {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 40px;
        }

        .address-block {
            background: #f8fafc;
            padding: 24px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .address-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #64748b;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
        }

        .address-name {
            font-weight: 600;
            font-size: 15px;
            color: #1e293b;
            margin-bottom: 2px;
        }

        .address-content p {
            font-size: 13px;
            color: #475569;
            line-height: 1.7;
        }

        .address-content .label {
            color: #94a3b8;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            display: block;
            margin-top: 8px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title .badge {
            background: #e2e8f0;
            color: #64748b;
            font-size: 12px;
            padding: 2px 10px;
            border-radius: 100px;
            font-weight: 600;
        }

        .items-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 32px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .items-table thead th {
            background: #f1f5f9;
            padding: 12px 16px;
            text-align: left;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #64748b;
            border-bottom: 1px solid #e2e8f0;
        }

        .items-table thead th:last-child {
            text-align: right;
        }

        .items-table thead th:nth-child(3),
        .items-table thead th:nth-child(4) {
            text-align: center;
        }

        .items-table tbody tr {
            transition: background 0.15s;
        }

        .items-table tbody tr:not(:last-child) td {
            border-bottom: 1px solid #f1f5f9;
        }

        .items-table tbody tr:hover {
            background: #f8fafc;
        }

        .items-table tbody td {
            padding: 14px 16px;
            font-size: 13px;
            color: #334155;
        }

        .items-table tbody td:last-child {
            text-align: right;
            font-weight: 600;
        }

        .items-table tbody td:nth-child(3),
        .items-table tbody td:nth-child(4) {
            text-align: center;
        }

        .item-product {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .item-thumb {
            width: 40px;
            height: 40px;
            background: #f1f5f9;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: #94a3b8;
            flex-shrink: 0;
        }

        .item-name {
            font-weight: 600;
            color: #1e293b;
        }

        .item-sku {
            font-size: 11px;
            color: #94a3b8;
            display: block;
            margin-top: 1px;
        }

        .order-summary-wrap {
            display: flex;
            justify-content: flex-end;
        }

        .order-summary {
            width: 320px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 24px;
        }

        .summary-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #64748b;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
            color: #475569;
        }

        .summary-row .amount {
            font-weight: 600;
        }

        .summary-row.discount .amount {
            color: #059669;
        }

        .summary-row.total {
            border-top: 2px solid #e2e8f0;
            margin-top: 8px;
            padding-top: 16px;
            font-size: 18px;
            font-weight: 800;
            color: #1e293b;
        }

        .summary-row.total .amount {
            color: #1e293b;
        }

        .invoice-footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 24px 40px;
            text-align: center;
        }

        .invoice-footer p {
            font-size: 13px;
            color: #94a3b8;
            line-height: 1.8;
        }

        .invoice-footer .footer-brand {
            font-weight: 700;
            color: #64748b;
        }

        .invoice-footer .footer-divider {
            width: 40px;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            border-radius: 2px;
            margin: 12px auto;
        }

        .print-btn {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .print-btn button {
            background: #1e293b;
            color: #fff;
            border: none;
            padding: 12px 32px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            transition: background 0.2s;
        }

        .print-btn button:hover {
            background: #334155;
        }

        .payment-method {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f1f5f9;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            color: #475569;
        }

        @media print {
            body {
                background: #fff;
            }
            .invoice-wrapper {
                margin: 0;
                max-width: 100%;
            }
            .invoice-container {
                box-shadow: none;
                border-radius: 0;
            }
            .print-btn {
                display: none;
            }
            .items-table tbody tr:hover {
                background: transparent;
            }
            .addresses {
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-wrapper">
        <div class="invoice-container">
            <!-- Header -->
            <div class="invoice-header">
                <div class="header-left">
                    <div class="store-name">E-Commerce</div>
                    <div class="store-tagline">Premium Online Store</div>
                </div>
                <div class="header-right">
                    <div class="invoice-label">Invoice</div>
                    <div class="invoice-number">#{{ $order->order_number }}</div>
                    <div class="invoice-date">{{ $order->created_at->format('F j, Y') }}</div>
                </div>
            </div>

            <!-- Status -->
            <div class="status-row">
                <span class="status-label">Status</span>
                <span class="status-badge status-{{ $order->status }}">{{ $order->status_label }}</span>
            </div>

            <!-- Body -->
            <div class="invoice-body">
                <!-- Addresses -->
                <div class="addresses">
                    <div class="address-block">
                        <div class="address-title">Bill To</div>
                        @php $ba = json_decode($order->billing_address, true); @endphp
                        <div class="address-name">{{ $order->customer_name }}</div>
                        <div class="address-content">
                            @if($ba && !empty($ba['company']))
                                <p>{{ $ba['company'] }}</p>
                            @endif
                            <p>{{ $ba['address_line_1'] ?? $order->billing_address }}</p>
                            @if($ba && !empty($ba['address_line_2']))
                                <p>{{ $ba['address_line_2'] }}</p>
                            @endif
                            <p>{{ $order->billing_city }}, {{ $order->billing_state }} {{ $order->billing_postal_code }}</p>
                            <p>{{ $order->billing_country }}</p>
                            <span class="label">Phone</span>
                            <p>{{ $order->customer_phone }}</p>
                            <span class="label">Email</span>
                            <p>{{ $order->customer_email }}</p>
                        </div>
                    </div>

                    <div class="address-block">
                        <div class="address-title">Ship To</div>
                        @php $sa = json_decode($order->shipping_address, true); @endphp
                        <div class="address-name">{{ $order->customer_name }}</div>
                        <div class="address-content">
                            @if($sa && !empty($sa['company']))
                                <p>{{ $sa['company'] }}</p>
                            @endif
                            <p>{{ $sa['address_line_1'] ?? $order->shipping_address }}</p>
                            @if($sa && !empty($sa['address_line_2']))
                                <p>{{ $sa['address_line_2'] }}</p>
                            @endif
                            <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}</p>
                            <p>{{ $order->shipping_country }}</p>
                            <span class="label">Payment</span>
                            <p><span class="payment-method">{{ ucwords(str_replace('_', ' ', $order->payment->payment_method ?? 'N/A')) }}</span></p>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="section-title">
                    Order Items
                    <span class="badge">{{ $order->orderItems->count() }} item{{ $order->orderItems->count() !== 1 ? 's' : '' }}</span>
                </div>

                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                            <tr>
                                <td>
                                    <div class="item-product">
                                        <div class="item-thumb">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                                        </div>
                                        <div>
                                            <div class="item-name">{{ $item->product->name }}</div>
                                            @if($item->variant)
                                                <span class="item-sku">{{ $item->variant->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $item->product->sku }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Summary -->
                <div class="order-summary-wrap">
                    <div class="order-summary">
                        <div class="summary-title">Summary</div>
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span class="amount">${{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Tax</span>
                            <span class="amount">${{ number_format($order->tax_amount, 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping</span>
                            <span class="amount">${{ number_format($order->shipping_amount, 2) }}</span>
                        </div>
                        @if($order->discount_amount > 0)
                            <div class="summary-row discount">
                                <span>Discount</span>
                                <span class="amount">-${{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                        @endif
                        <div class="summary-row total">
                            <span>Total</span>
                            <span class="amount">${{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="invoice-footer">
                <div class="footer-divider"></div>
                <p>Thank you for your business!</p>
                <p>For any questions, contact <span class="footer-brand">support@ecommerce.com</span></p>
                <p>&copy; {{ date('Y') }} E-Commerce Store. All rights reserved.</p>
            </div>
        </div>

        <div class="print-btn">
            <button onclick="window.print()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 8px;"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                Print Invoice
            </button>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
