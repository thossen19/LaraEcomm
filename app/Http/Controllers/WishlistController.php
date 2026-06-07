<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display user's wishlist.
     */
    public function index(Request $request)
    {
        // Debug: Check authentication and user ID
        \Log::info('Wishlist index request', [
            'user_authenticated' => Auth::check(),
            'user_id' => Auth::id(),
            'session_id' => session()->getId()
        ]);

        if (!Auth::check()) {
            \Log::error('User not authenticated for wishlist index');
            return redirect()->route('login');
        }

        $wishlistItems = Wishlist::forUser(Auth::id())
            ->withProducts()
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Debug: Log the query and results
        \Log::info('Wishlist items for user ' . Auth::id(), [
            'count' => $wishlistItems->count(),
            'items' => collect($wishlistItems->items())->map(function($item) {
                return [
                    'id' => $item->id,
                    'user_id' => $item->user_id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product ? $item->product->name : 'N/A'
                ];
            })->toArray()
        ]);

        // If AJAX request, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'wishlist' => collect($wishlistItems->items())->map(function ($item) {
                    return [
                        'db_id' => $item->id, // Database ID for removal
                        'id' => $item->product_id, // Product ID for frontend use
                        'name' => $item->product->name,
                        'image' => $item->image,
                        'price' => $item->product->price,
                        'added_at' => $item->created_at->toISOString()
                    ];
                })->toArray()
            ]);
        }

        return view('wishlist.index', compact('wishlistItems'));
    }

    /**
     * Get wishlist count for authenticated user.
     */
    public function getCount(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                    'count' => 0
                ], 401);
            }

            $count = Wishlist::where('user_id', Auth::id())->count();
            
            \Log::info('Wishlist count request for user ' . Auth::id() . ': ' . $count);

            return response()->json([
                'success' => true,
                'count' => $count,
                'user_id' => Auth::id()
            ]);

        } catch (\Exception $e) {
            \Log::error('Error getting wishlist count', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error getting wishlist count',
                'count' => 0
            ], 500);
        }
    }

    /**
     * Add product to wishlist.
     */
    public function add(Request $request)
    {
        try {
            // Debug: Log request data and auth status
            \Log::info('Wishlist add request received', [
                'user_authenticated' => Auth::check(),
                'user_id' => Auth::id(),
                'session_id' => session()->getId(),
                'request_data' => $request->all()
            ]);

            if (!Auth::check()) {
                \Log::error('User not authenticated for wishlist add');
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $request->validate([
                'product_id' => 'required|exists:products,id',
                'product_variant_id' => 'nullable|exists:product_variants,id',
                'quantity' => 'required|integer|min:1|max:10',
                'notes' => 'nullable|string|max:255'
            ]);

            // Check if item already exists in wishlist for THIS USER
            $exists = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)
                ->when($request->product_variant_id, function($query, $variantId) {
                    $query->where('product_variant_id', $variantId);
                })
                ->exists();

            if ($exists) {
                \Log::info('Item already exists in wishlist for user ' . Auth::id(), [
                    'user_id' => Auth::id(),
                    'product_id' => $request->product_id
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'This item is already in your wishlist!'
                ], 409);
            }

            // Add to wishlist with explicit user_id
            $wishlistItem = Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'product_variant_id' => $request->product_variant_id,
                'quantity' => $request->quantity,
                'notes' => $request->notes
            ]);

            \Log::info('Wishlist item created successfully', [
                'wishlist_id' => $wishlistItem->id,
                'user_id' => $wishlistItem->user_id,
                'product_id' => $wishlistItem->product_id
            ]);

            // Verify the item was saved correctly
            $verifyItem = Wishlist::find($wishlistItem->id);
            \Log::info('Verification of created item', [
                'item_exists' => $verifyItem ? true : false,
                'correct_user' => $verifyItem && $verifyItem->user_id == Auth::id(),
                'item_data' => $verifyItem ? $verifyItem->toArray() : null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product added to wishlist successfully!',
                'wishlist_count' => Wishlist::forUser(Auth::id())->count(),
                'user_id' => Auth::id(),
                'db_id' => $wishlistItem->id
            ]);

        } catch (\Exception $e) {
            \Log::error('Error adding item to wishlist', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error adding item to wishlist: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update wishlist item.
     */
    public function update(Request $request, Wishlist $item)
    {
        if ($item->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'This action is unauthorized.'], 403);
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500'
        ]);

        $item->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Wishlist item updated successfully!',
            'item' => $item->load(['product', 'productVariant'])
        ]);
    }

    /**
     * Remove item from wishlist.
     */
    public function remove(Wishlist $item)
    {
        if ($item->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'This action is unauthorized.'], 403);
        }

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from wishlist!',
            'wishlist_count' => Wishlist::forUser(Auth::id())->count()
        ]);
    }

    /**
     * Clear entire wishlist.
     */
    public function clear()
    {
        Wishlist::where('user_id', Auth::id())->delete();

        return response()->json([
            'success' => true,
            'message' => 'Wishlist cleared successfully!',
            'wishlist_count' => 0
        ]);
    }

    /**
     * Move wishlist item to cart.
     */
    public function moveToCart(Wishlist $item)
    {
        if ($item->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'This action is unauthorized.'], 403);
        }

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

        $cart->addItem($item->product_id, $item->quantity, $item->product_variant_id);

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item moved to cart successfully!',
            'wishlist_count' => Wishlist::forUser(Auth::id())->count()
        ]);
    }

    /**
     * Add multiple items to wishlist.
     */
    public function addMultiple(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:10'
        ]);

        $addedCount = 0;
        $skippedCount = 0;
        $skippedItems = [];

        foreach ($request->items as $item) {
            $exists = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $item['product_id'])
                ->when($item['product_variant_id'] ?? null, function($query, $variantId) {
                    $query->where('product_variant_id', $variantId);
                })
                ->exists();

            if (!$exists) {
                Wishlist::create([
                    'user_id' => Auth::id(),
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['product_variant_id'] ?? null,
                    'quantity' => $item['quantity'],
                    'notes' => $item['notes'] ?? null
                ]);
                $addedCount++;
            } else {
                $skippedCount++;
                $skippedItems[] = $item['product_id'];
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Added {$addedCount} items to wishlist. {$skippedCount} items were already in your wishlist.",
            'added_count' => $addedCount,
            'skipped_count' => $skippedCount,
            'skipped_items' => $skippedItems,
            'wishlist_count' => Wishlist::forUser(Auth::id())->count()
        ]);
    }
}
