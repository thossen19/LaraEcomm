<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Address;
use App\Models\Payment;
use App\Models\Coupon;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())->with(['cartItems.product', 'cartItems.productVariant'])->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        // Check stock availability
        foreach ($cart->cartItems as $item) {
            $availableQuantity = $item->productVariant ? $item->productVariant->quantity : $item->product->quantity;
            if ($availableQuantity < $item->quantity) {
                return redirect()->route('cart.index')->with('error', "Sorry, {$item->product->name} is out of stock or insufficient quantity!");
            }
        }

        // Load enabled payment gateways from settings
        $gatewaysRaw = Setting::get('gateways');
        $gateways = $gatewaysRaw ? json_decode($gatewaysRaw, true) : [];

        $addresses = Auth::user()->addresses;
        $defaultBilling = $addresses->where('type', 'billing')->where('is_default', true)->first();
        $defaultShipping = $addresses->where('type', 'shipping')->where('is_default', true)->first();

        return view('checkout.index', compact('cart', 'addresses', 'defaultBilling', 'defaultShipping', 'gateways'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'billing_address_id' => 'required|exists:addresses,id',
            'shipping_address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:credit_card,paypal,bank_transfer,cod',
            'coupon_code' => 'nullable|string|exists:coupons,code',
            'notes' => 'nullable|string|max:500',
        ]);

        $cart = Cart::where('user_id', Auth::id())->with(['cartItems.product', 'cartItems.productVariant'])->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return back()->with('error', 'Your cart is empty!');
        }

        // Verify addresses belong to user
        $billingAddress = Address::where('id', $request->billing_address_id)->where('user_id', Auth::id())->first();
        $shippingAddress = Address::where('id', $request->shipping_address_id)->where('user_id', Auth::id())->first();

        if (!$billingAddress || !$shippingAddress) {
            return back()->with('error', 'Invalid address selected!');
        }

        // Final stock check
        foreach ($cart->cartItems as $item) {
            $availableQuantity = $item->productVariant ? $item->productVariant->quantity : $item->product->quantity;
            if ($availableQuantity < $item->quantity) {
                return back()->with('error', "Sorry, {$item->product->name} is out of stock or insufficient quantity!");
            }
        }

        return DB::transaction(function () use ($request, $cart, $billingAddress, $shippingAddress) {
            // Calculate totals
            $subtotal = $cart->subtotal;
            $taxRate = Setting::get('tax_rate', 10) / 100;
            $tax = $subtotal * $taxRate;
            $shipping = $cart->shipping;

            // Apply coupon if provided
            $discount = 0;
            $coupon = null;
            if ($request->coupon_code) {
                $coupon = Coupon::where('code', $request->coupon_code)->first();
                if ($coupon && $coupon->isValid()) {
                    $discount = $coupon->calculateDiscount($subtotal);
                    $coupon->incrementUsage();
                }
            }

            $total = $subtotal + $tax + $shipping - $discount;

            // Create order
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'user_id' => Auth::id(),
                'status' => 'pending',
                'currency' => Setting::get('currency', 'USD'),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'discount' => $discount,
                'total' => $total,
                'coupon_code' => $request->coupon_code,
                'customer_name' => Auth::user()->full_name,
                'customer_email' => Auth::user()->email,
                'customer_phone' => Auth::user()->phone,
                'billing_address' => json_encode($billingAddress->toArray()),
                'billing_city' => $billingAddress->city,
                'billing_state' => $billingAddress->state,
                'billing_postal_code' => $billingAddress->postal_code,
                'billing_country' => $billingAddress->country,
                'shipping_address' => json_encode($shippingAddress->toArray()),
                'shipping_city' => $shippingAddress->city,
                'shipping_state' => $shippingAddress->state,
                'shipping_postal_code' => $shippingAddress->postal_code,
                'shipping_country' => $shippingAddress->country,
                'notes' => $request->notes,
            ]);

            // Create order items
            foreach ($cart->cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name' => $item->product->name,
                    'product_sku' => $item->product->sku,
                    'product_variant_title' => $item->productVariant ? $item->productVariant->title : null,
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

            // Create payment record
            Payment::create([
                'order_id' => $order->id,
                'transaction_id' => 'TXN-' . strtoupper(Str::random(10)),
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'amount' => $total,
                'currency' => Setting::get('currency', 'USD'),
            ]);

            // Clear cart
            $cart->cartItems()->delete();
            $cart->update([
                'subtotal' => 0,
                'tax' => 0,
                'shipping' => 0,
                'total' => 0
            ]);

            return redirect()->route('checkout.success', $order->id)
                        ->with('success', 'Order placed successfully!');
        });
    }

    public function success(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('checkout.success', compact('order'));
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|exists:coupons,code',
        ]);

        $coupon = Coupon::where('code', $request->coupon_code)->first();

        $cart = Cart::where('user_id', Auth::id())->first();
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty.'
            ]);
        }

        if (!$coupon->isValid($cart->subtotal)) {
            return response()->json([
                'success' => false,
                'message' => 'This coupon is not valid or has expired.'
            ]);
        }

        $discount = $coupon->calculateDiscount($cart->subtotal);
        $newTotal = $cart->total - $discount;

        return response()->json([
            'success' => true,
            'discount' => $discount,
            'new_total' => $newTotal,
            'message' => 'Coupon applied successfully!'
        ]);
    }
}
