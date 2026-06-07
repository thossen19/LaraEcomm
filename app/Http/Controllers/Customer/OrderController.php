<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Order::where('user_id', Auth::id())->with(['orderItems.product']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('orderItems', function ($q) use ($request) {
                      $q->where('product_name', 'like', '%' . $request->search . '%');
                  });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['orderItems.product', 'orderItems.productVariant', 'payment']);

        return view('customer.orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($order->status, ['pending', 'processing'])) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        $order->update(['status' => 'cancelled']);

        // Restore stock
        foreach ($order->orderItems as $item) {
            if ($item->productVariant) {
                $item->productVariant->increment('quantity', $item->quantity);
            } else {
                $item->product->increment('quantity', $item->quantity);
            }
        }

        return back()->with('success', 'Order cancelled successfully.');
    }

    public function reorder(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $cart = Auth::user()->cart ?? Auth::user()->cart()->create([
            'subtotal' => 0,
            'tax' => 0,
            'shipping' => 0,
            'total' => 0
        ]);

        foreach ($order->orderItems as $item) {
            // Check if product is still available
            if ($item->productVariant) {
                if ($item->productVariant->quantity < $item->quantity) {
                    continue; // Skip if not enough stock
                }
            } else {
                if ($item->product->quantity < $item->quantity) {
                    continue; // Skip if not enough stock
                }
            }

            // Add to cart
            $cart->cartItems()->create([
                'product_id' => $item->product_id,
                'product_variant_id' => $item->product_variant_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'total' => $item->total,
            ]);

            // Update stock
            if ($item->productVariant) {
                $item->productVariant->decrement('quantity', $item->quantity);
            } else {
                $item->product->decrement('quantity', $item->quantity);
            }
        }

        // Recalculate cart totals
        $subtotal = $cart->cartItems->sum('total');
        $taxRate = Setting::get('tax_rate', 10) / 100;
        $tax = $subtotal * $taxRate;
        $shipping = $subtotal > 100 ? 0 : Setting::get('shipping_cost', 10);
        $total = $subtotal + $tax + $shipping;

        $cart->update([
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping' => $shipping,
            'total' => $total
        ]);

        return redirect()->route('cart.index')->with('success', 'Order items added to cart!');
    }
}
