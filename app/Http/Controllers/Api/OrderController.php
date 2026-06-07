<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $orders = Order::with(['items.product', 'items.productVariant', 'payments'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 10));

        return response()->json($orders);
    }

    public function show($id)
    {
        $user = request()->user();
        
        $order = Order::with(['items.product', 'items.productVariant', 'payments'])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        return response()->json($order);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'billing_address' => 'required|array',
            'shipping_address' => 'nullable|array',
            'notes' => 'nullable|string',
            'payment_method' => 'required|string',
            'shipping_method' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        
        // Get cart items
        $cartItems = Cart::with(['product', 'productVariant'])
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'message' => 'Cart is empty'
            ], 422);
        }

        // Check stock availability
        foreach ($cartItems as $cartItem) {
            $stockQuantity = $cartItem->productVariant 
                ? $cartItem->productVariant->stock_quantity 
                : $cartItem->product->stock_quantity;

            if ($stockQuantity < $cartItem->quantity) {
                return response()->json([
                    'message' => "Insufficient stock for {$cartItem->product->name}"
                ], 422);
            }
        }

        DB::beginTransaction();

        try {
            // Calculate totals
            $subtotal = $cartItems->sum(function ($item) {
                return $item->quantity * $item->price;
            });

            $taxAmount = $subtotal * 0.1; // 10% tax
            $shippingAmount = $this->calculateShipping($request->shipping_method, $cartItems);
            $totalAmount = $subtotal + $taxAmount + $shippingAmount;

            // Create order
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'user_id' => $user->id,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingAmount,
                'discount_amount' => 0,
                'total_amount' => $totalAmount,
                'currency' => 'USD',
                'status' => 'pending',
                'payment_status' => 'pending',
                'shipping_status' => 'pending',
                'billing_address' => $request->billing_address,
                'shipping_address' => $request->shipping_address ?? $request->billing_address,
                'notes' => $request->notes,
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_variant_id' => $cartItem->product_variant_id,
                    'product_name' => $cartItem->product->name,
                    'product_sku' => $cartItem->productVariant 
                        ? $cartItem->productVariant->sku 
                        : $cartItem->product->sku,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'total' => $cartItem->quantity * $cartItem->price,
                    'options' => $cartItem->options,
                ]);

                // Update stock
                if ($cartItem->productVariant) {
                    $cartItem->productVariant->decrement('stock_quantity', $cartItem->quantity);
                } else {
                    $cartItem->product->decrement('stock_quantity', $cartItem->quantity);
                }
            }

            // Clear cart
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            return response()->json($order->load(['items.product', 'items.productVariant']), 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'message' => 'Failed to create order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        
        $order = Order::where('user_id', $user->id)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'shipping_address' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $order->update($request->only(['shipping_address', 'notes']));

        return response()->json($order->load(['items.product', 'items.productVariant']));
    }

    public function cancel($id)
    {
        $user = request()->user();
        
        $order = Order::where('user_id', $user->id)->findOrFail($id);

        if ($order->payment_status === 'paid') {
            return response()->json([
                'message' => 'Cannot cancel paid order'
            ], 422);
        }

        if (in_array($order->status, ['shipped', 'delivered'])) {
            return response()->json([
                'message' => 'Cannot cancel shipped or delivered order'
            ], 422);
        }

        // Restore stock
        foreach ($order->items as $item) {
            if ($item->productVariant) {
                $item->productVariant->increment('stock_quantity', $item->quantity);
            } else {
                $item->product->increment('stock_quantity', $item->quantity);
            }
        }

        $order->update([
            'status' => 'cancelled'
        ]);

        return response()->json(['message' => 'Order cancelled successfully']);
    }

    private function calculateShipping($method, $cartItems)
    {
        $totalWeight = 0;
        
        foreach ($cartItems as $item) {
            $weight = $item->productVariant 
                ? ($item->productVariant->weight ?? 0) 
                : ($item->product->weight ?? 0);
            $totalWeight += $weight * $item->quantity;
        }

        switch ($method) {
            case 'standard':
                return max(5.99, $totalWeight * 0.5);
            case 'express':
                return max(12.99, $totalWeight * 1.5);
            case 'overnight':
                return max(24.99, $totalWeight * 3);
            case 'pickup':
                return 0;
            default:
                return 9.99;
        }
    }

    public function track($id)
    {
        $user = request()->user();
        
        $order = Order::where('user_id', $user->id)->findOrFail($id);

        $tracking = [
            'order_number' => $order->order_number,
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'shipping_status' => $order->shipping_status,
            'created_at' => $order->created_at,
            'shipped_at' => $order->shipped_at,
            'delivered_at' => $order->delivered_at,
        ];

        return response()->json($tracking);
    }
}
