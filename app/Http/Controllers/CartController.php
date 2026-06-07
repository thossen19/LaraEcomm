<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cart->load(['cartItems.product', 'cartItems.productVariant']);  // Fixed: items -> cartItems

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id',
        ]);

        $cart = $this->getOrCreateCart();
        $product = Product::findOrFail($request->product_id);
        $variant = $request->variant_id ? ProductVariant::findOrFail($request->variant_id) : null;

        // Check if product/variant is in stock
        if ($variant) {
            if ($variant->quantity < $request->quantity) {
                return back()->with('error', 'Not enough stock for this variant.');
            }
        } else {
            if ($product->quantity < $request->quantity) {
                return back()->with('error', 'Not enough stock for this product.');
            }
        }

        // Check if item already exists in cart
        $existingItem = $cart->cartItems()  // Fixed: items -> cartItems
            ->where('product_id', $request->product_id)
            ->where('product_variant_id', $request->variant_id)
            ->first();

        if ($existingItem) {
            $newQuantity = $existingItem->quantity + $request->quantity;
            
            if ($variant) {
                if ($variant->quantity < $newQuantity) {
                    return back()->with('error', 'Not enough stock for this variant.');
                }
            } else {
                if ($product->quantity < $newQuantity) {
                    return back()->with('error', 'Not enough stock for this product.');
                }
            }

            $existingItem->update([
                'quantity' => $newQuantity,
                'total' => $newQuantity * ($variant ? $variant->price : $product->price)
            ]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'product_variant_id' => $request->variant_id,
                'quantity' => $request->quantity,
                'price' => $variant ? $variant->price : $product->price,
                'total' => $request->quantity * ($variant ? $variant->price : $product->price)
            ]);
        }

        $this->updateCartTotals($cart);

        return back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403);
        }

        $product = $cartItem->product;
        $variant = $cartItem->productVariant;

        // Check stock
        if ($variant) {
            if ($variant->quantity < $request->quantity) {
                return back()->with('error', 'Not enough stock for this variant.');
            }
        } else {
            if ($product->quantity < $request->quantity) {
                return back()->with('error', 'Not enough stock for this product.');
            }
        }

        $cartItem->update([
            'quantity' => $request->quantity,
            'total' => $request->quantity * ($variant ? $variant->price : $product->price)
        ]);

        $this->updateCartTotals($cartItem->cart);

        return back()->with('success', 'Cart updated!');
    }

    public function remove(CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cartItem->delete();
        $this->updateCartTotals($cartItem->cart);

        return back()->with('success', 'Item removed from cart!');
    }

    public function clear()
    {
        $cart = $this->getOrCreateCart();
        $cart->cartItems()->delete();  // Fixed: items -> cartItems
        
        $cart->update([
            'subtotal' => 0,
            'tax' => 0,
            'shipping' => 0,
            'total' => 0
        ]);

        return back()->with('success', 'Cart cleared!');
    }

    public function getCartCount()
    {
        if (!Auth::check()) {
            return response()->json([
                'count' => 0,
                'total' => 0
            ]);
        }

        try {
            $cart = $this->getOrCreateCart();
            return response()->json([
                'count' => $cart->cartItems->sum('quantity'),  // Fixed: items -> cartItems
                'total' => $cart->total
            ]);
        } catch (\Exception $e) {
            \Log::error('Cart count error: ' . $e->getMessage());
            return response()->json([
                'count' => 0,
                'total' => 0
            ]);
        }
    }

    private function getOrCreateCart()
    {
        $cart = Cart::where('user_id', Auth::id())->first();

        if (!$cart) {
            $cart = Cart::create([
                'user_id' => Auth::id(),
                'subtotal' => 0,
                'tax' => 0,
                'shipping' => 0,
                'total' => 0
            ]);
        }

        return $cart;
    }

    private function updateCartTotals(Cart $cart)
    {
        $subtotal = $cart->cartItems->sum('total');  // Fixed: items -> cartItems
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
    }
}
