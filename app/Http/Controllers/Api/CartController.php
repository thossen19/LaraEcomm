<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $cartItems = Cart::with(['product', 'productVariant'])
            ->where(function ($query) use ($user) {
                if ($user) {
                    $query->where('user_id', $user->id);
                } else {
                    $query->where('session_id', $request->session_id);
                }
            })
            ->get();

        $cartTotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        return response()->json([
            'items' => $cartItems,
            'total' => $cartTotal,
            'count' => $cartItems->sum('quantity')
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
            'options' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        $sessionId = $request->session_id;

        // Get product and variant
        $product = Product::findOrFail($request->product_id);
        $variant = null;

        if ($request->product_variant_id) {
            $variant = ProductVariant::findOrFail($request->product_variant_id);
            
            // Check if variant belongs to product
            if ($variant->product_id !== $product->id) {
                return response()->json([
                    'message' => 'Variant does not belong to this product'
                ], 422);
            }
        }

        // Check stock
        $stockQuantity = $variant ? $variant->stock_quantity : $product->stock_quantity;
        if ($stockQuantity < $request->quantity) {
            return response()->json([
                'message' => 'Insufficient stock'
            ], 422);
        }

        // Get price
        $price = $variant ? $variant->price : $product->price;

        // Check if item already exists in cart
        $existingCart = Cart::where(function ($query) use ($user, $sessionId) {
                if ($user) {
                    $query->where('user_id', $user->id);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->where('product_id', $request->product_id)
            ->where('product_variant_id', $request->product_variant_id)
            ->first();

        if ($existingCart) {
            // Update quantity
            $newQuantity = $existingCart->quantity + $request->quantity;
            
            if ($stockQuantity < $newQuantity) {
                return response()->json([
                    'message' => 'Insufficient stock'
                ], 422);
            }

            $existingCart->update([
                'quantity' => $newQuantity,
                'price' => $price,
                'options' => $request->options
            ]);

            $cartItem = $existingCart;
        } else {
            // Create new cart item
            $cartItem = Cart::create([
                'user_id' => $user ? $user->id : null,
                'session_id' => $user ? null : $sessionId,
                'product_id' => $request->product_id,
                'product_variant_id' => $request->product_variant_id,
                'quantity' => $request->quantity,
                'price' => $price,
                'options' => $request->options,
            ]);
        }

        return response()->json($cartItem->load(['product', 'productVariant']), 201);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        
        $cartItem = Cart::where(function ($query) use ($user, $request) {
                if ($user) {
                    $query->where('user_id', $user->id);
                } else {
                    $query->where('session_id', $request->session_id);
                }
            })
            ->findOrFail($id);

        // Check stock
        $stockQuantity = $cartItem->productVariant 
            ? $cartItem->productVariant->stock_quantity 
            : $cartItem->product->stock_quantity;

        if ($stockQuantity < $request->quantity) {
            return response()->json([
                'message' => 'Insufficient stock'
            ], 422);
        }

        $cartItem->update([
            'quantity' => $request->quantity
        ]);

        return response()->json($cartItem->load(['product', 'productVariant']));
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        
        $cartItem = Cart::where(function ($query) use ($user, $request) {
                if ($user) {
                    $query->where('user_id', $user->id);
                } else {
                    $query->where('session_id', $request->session_id);
                }
            })
            ->findOrFail($id);

        $cartItem->delete();

        return response()->json(['message' => 'Item removed from cart']);
    }

    public function clear(Request $request)
    {
        $user = $request->user();
        
        Cart::where(function ($query) use ($user, $request) {
                if ($user) {
                    $query->where('user_id', $user->id);
                } else {
                    $query->where('session_id', $request->session_id);
                }
            })
            ->delete();

        return response()->json(['message' => 'Cart cleared']);
    }

    public function count(Request $request)
    {
        $user = $request->user();
        
        $count = Cart::where(function ($query) use ($user, $request) {
                if ($user) {
                    $query->where('user_id', $user->id);
                } else {
                    $query->where('session_id', $request->session_id);
                }
            })
            ->sum('quantity');

        return response()->json(['count' => $count]);
    }

    public function merge(Request $request)
    {
        $user = $request->user();
        $sessionId = $request->session_id;

        if (!$user || !$sessionId) {
            return response()->json([
                'message' => 'User and session_id required'
            ], 422);
        }

        // Get session cart items
        $sessionCartItems = Cart::where('session_id', $sessionId)->get();

        foreach ($sessionCartItems as $sessionItem) {
            // Check if user already has this item in cart
            $existingUserItem = Cart::where('user_id', $user->id)
                ->where('product_id', $sessionItem->product_id)
                ->where('product_variant_id', $sessionItem->product_variant_id)
                ->first();

            if ($existingUserItem) {
                // Merge quantities
                $existingUserItem->update([
                    'quantity' => $existingUserItem->quantity + $sessionItem->quantity
                ]);
                $sessionItem->delete();
            } else {
                // Move to user cart
                $sessionItem->update([
                    'user_id' => $user->id,
                    'session_id' => null
                ]);
            }
        }

        return response()->json(['message' => 'Cart merged successfully']);
    }
}
