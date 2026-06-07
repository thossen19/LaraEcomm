<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Payment;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class SellerController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:seller');
    }

    public function dashboard(Request $request)
    {
        $user = $request->user();
        $shop = $user->shop;

        if (!$shop) {
            return response()->json(['message' => 'Shop not found'], 404);
        }

        $stats = [
            'total_products' => Product::where('shop_id', $shop->id)->count(),
            'active_products' => Product::where('shop_id', $shop->id)->where('is_active', true)->count(),
            'total_orders' => Order::whereHas('items.product', function ($query) use ($shop) {
                $query->where('shop_id', $shop->id);
            })->count(),
            'pending_orders' => Order::whereHas('items.product', function ($query) use ($shop) {
                $query->where('shop_id', $shop->id);
            })->where('status', 'pending')->count(),
            'processing_orders' => Order::whereHas('items.product', function ($query) use ($shop) {
                $query->where('shop_id', $shop->id);
            })->where('status', 'processing')->count(),
            'total_revenue' => Payment::whereHas('order.items.product', function ($query) use ($shop) {
                $query->where('shop_id', $shop->id);
            })->where('status', 'paid')->sum('amount'),
            'this_month_revenue' => Payment::whereHas('order.items.product', function ($query) use ($shop) {
                $query->where('shop_id', $shop->id);
            })->where('status', 'paid')->whereMonth('created_at', now()->month)->sum('amount'),
        ];

        $recentOrders = Order::with(['user', 'items.product' => function ($query) use ($shop) {
                $query->where('shop_id', $shop->id);
            }])
            ->whereHas('items.product', function ($query) use ($shop) {
                $query->where('shop_id', $shop->id);
            })
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $topProducts = Product::where('shop_id', $shop->id)
            ->withCount(['orderItems as sales_count'])
            ->orderBy('sales_count', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'shop' => $shop,
            'stats' => $stats,
            'recent_orders' => $recentOrders,
            'top_products' => $topProducts,
        ]);
    }

    public function shopProfile(Request $request)
    {
        $shop = $request->user()->shop;

        if (!$shop) {
            return response()->json(['message' => 'Shop not found'], 404);
        }

        return response()->json($shop->load(['owner', 'products']));
    }

    public function updateShopProfile(Request $request)
    {
        $user = $request->user();
        $shop = $user->shop;

        if (!$shop) {
            return response()->json(['message' => 'Shop not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|array',
            'social_links' => 'nullable|array',
            'settings' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $shop->update($request->only([
            'name', 'description', 'email', 'phone', 'address', 'social_links', 'settings'
        ]));

        return response()->json([
            'message' => 'Shop profile updated successfully',
            'shop' => $shop
        ]);
    }

    public function products(Request $request)
    {
        $user = $request->user();
        $shop = $user->shop;

        if (!$shop) {
            return response()->json(['message' => 'Shop not found'], 404);
        }

        $query = Product::with(['categories', 'variants'])
            ->where('shop_id', $shop->id)
            ->orderBy('created_at', 'desc');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->paginate($request->get('per_page', 15));

        return response()->json($products);
    }

    public function createProduct(Request $request)
    {
        $user = $request->user();
        $shop = $user->shop;

        if (!$shop) {
            return response()->json(['message' => 'Shop not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'sku' => 'required|string|max:100|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'track_quantity' => 'boolean',
            'is_active' => 'boolean',
            'requires_shipping' => 'boolean',
            'weight' => 'nullable|numeric|min:0',
            'category_ids' => 'array',
            'category_ids.*' => 'exists:categories,id',
            'variants' => 'array',
            'variants.*.sku' => 'required|string|max:100',
            'variants.*.title' => 'required|string',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock_quantity' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = Product::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name) . '-' . uniqid(),
            'description' => $request->description,
            'short_description' => $request->short_description,
            'sku' => $request->sku,
            'price' => $request->price,
            'compare_price' => $request->compare_price,
            'stock_quantity' => $request->stock_quantity,
            'track_quantity' => $request->boolean('track_quantity', true),
            'is_active' => $request->boolean('is_active', true),
            'is_digital' => false,
            'requires_shipping' => $request->boolean('requires_shipping', true),
            'weight' => $request->weight,
            'product_type' => 'physical',
            'status' => 'active',
            'shop_id' => $shop->id,
        ]);

        // Attach categories
        if ($request->has('category_ids')) {
            $product->categories()->attach($request->category_ids);
        }

        // Create variants
        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                $product->variants()->create([
                    'sku' => $variantData['sku'],
                    'title' => $variantData['title'],
                    'price' => $variantData['price'],
                    'stock_quantity' => $variantData['stock_quantity'],
                    'is_active' => true,
                    'position' => array_search($variantData, $request->variants),
                ]);
            }
        }

        return response()->json($product->load(['categories', 'variants']), 201);
    }

    public function orders(Request $request)
    {
        $user = $request->user();
        $shop = $user->shop;

        if (!$shop) {
            return response()->json(['message' => 'Shop not found'], 404);
        }

        $query = Order::with(['user', 'items.product', 'payments'])
            ->whereHas('items.product', function ($query) use ($shop) {
                $query->where('shop_id', $shop->id);
            })
            ->orderBy('created_at', 'desc');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate($request->get('per_page', 15));

        return response()->json($orders);
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        $shop = $user->shop;

        if (!$shop) {
            return response()->json(['message' => 'Shop not found'], 404);
        }

        $order = Order::whereHas('items.product', function ($query) use ($shop) {
                $query->where('shop_id', $shop->id);
            })
            ->findOrFail($id);

        $order->update($request->only(['status', 'notes']));

        if ($request->status === 'shipped') {
            $order->update(['shipped_at' => now()]);
        } elseif ($request->status === 'delivered') {
            $order->update(['delivered_at' => now()]);
        }

        return response()->json([
            'message' => 'Order status updated successfully',
            'order' => $order->load(['user', 'items.product', 'payments'])
        ]);
    }

    public function earnings(Request $request)
    {
        $user = $request->user();
        $shop = $user->shop;

        if (!$shop) {
            return response()->json(['message' => 'Shop not found'], 404);
        }

        $period = $request->get('period', 'monthly');

        $earnings = Payment::whereHas('order.items.product', function ($query) use ($shop) {
                $query->where('shop_id', $shop->id);
            })
            ->where('status', 'paid');

        if ($period === 'daily') {
            $data = $earnings->selectRaw('DATE(created_at) as date, SUM(amount) as total, SUM(amount * commission_rate / 100) as commission')
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->take(30)
                ->get();
        } elseif ($period === 'monthly') {
            $data = $earnings->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(amount) as total, SUM(amount * commission_rate / 100) as commission')
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->take(12)
                ->get();
        } else {
            $data = $earnings->selectRaw('YEAR(created_at) as year, SUM(amount) as total, SUM(amount * commission_rate / 100) as commission')
                ->groupBy('year')
                ->orderBy('year', 'desc')
                ->take(5)
                ->get();
        }

        return response()->json($data);
    }

    public function analytics(Request $request)
    {
        $user = $request->user();
        $shop = $user->shop;

        if (!$shop) {
            return response()->json(['message' => 'Shop not found'], 404);
        }

        $analytics = [
            'sales_chart' => $this->getSalesChart($shop),
            'orders_chart' => $this->getOrdersChart($shop),
            'top_products' => $this->getTopProducts($shop),
            'revenue_summary' => $this->getRevenueSummary($shop),
        ];

        return response()->json($analytics);
    }

    private function getSalesChart($shop)
    {
        return Payment::whereHas('order.items.product', function ($query) use ($shop) {
                $query->where('shop_id', $shop->id);
            })
            ->where('status', 'paid')
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getOrdersChart($shop)
    {
        return Order::whereHas('items.product', function ($query) use ($shop) {
                $query->where('shop_id', $shop->id);
            })
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getTopProducts($shop)
    {
        return Product::where('shop_id', $shop->id)
            ->withCount(['orderItems as sales_count'])
            ->orderBy('sales_count', 'desc')
            ->take(10)
            ->get();
    }

    private function getRevenueSummary($shop)
    {
        $payments = Payment::whereHas('order.items.product', function ($query) use ($shop) {
                $query->where('shop_id', $shop->id);
            })
            ->where('status', 'paid');

        return [
            'total_revenue' => $payments->sum('amount'),
            'total_commission' => $payments->sum(DB::raw('amount * commission_rate / 100')),
            'net_earnings' => $payments->sum(DB::raw('amount - (amount * commission_rate / 100)')),
            'this_month' => $payments->whereMonth('created_at', now()->month)->sum('amount'),
            'last_month' => $payments->whereMonth('created_at', now()->subMonth()->month)->sum('amount'),
        ];
    }
}
