<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Shop;
use App\Models\Payment;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_orders' => Order::count(),
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_shops' => Shop::count(),
            'total_revenue' => Payment::where('status', 'paid')->sum('amount'),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'processing_orders' => Order::where('status', 'processing')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'total_customers' => User::role('customer')->count(),
            'total_sellers' => User::role('seller')->count(),
            'active_products' => Product::where('is_active', true)->count(),
            'inactive_products' => Product::where('is_active', false)->count(),
        ];

        $recentOrders = Order::with(['user', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $topProducts = Product::withCount(['orderItems as sales_count'])
            ->orderBy('sales_count', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'stats' => $stats,
            'recent_orders' => $recentOrders,
            'top_products' => $topProducts,
        ]);
    }

    public function users(Request $request)
    {
        $query = User::with(['roles', 'shop'])
            ->orderBy('created_at', 'desc');

        if ($request->has('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->paginate($request->get('per_page', 15));

        return response()->json($users);
    }

    public function updateUserStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::findOrFail($id);
        $user->update(['is_active' => $request->is_active]);

        return response()->json([
            'message' => 'User status updated successfully',
            'user' => $user
        ]);
    }

    public function orders(Request $request)
    {
        $query = Order::with(['user', 'items.product', 'payments'])
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
            'shipping_status' => 'nullable|in:pending,processing,shipped,delivered',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $order = Order::findOrFail($id);
        $order->update($request->only(['status', 'shipping_status', 'notes']));

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

    public function products(Request $request)
    {
        $query = Product::with(['categories', 'shop', 'variants'])
            ->orderBy('created_at', 'desc');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->has('shop_id')) {
            $query->where('shop_id', $request->shop_id);
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

    public function updateProductStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'is_active' => 'required|boolean',
            'status' => 'nullable|in:active,inactive,draft',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = Product::findOrFail($id);
        $product->update($request->only(['is_active', 'status']));

        return response()->json([
            'message' => 'Product status updated successfully',
            'product' => $product
        ]);
    }

    public function shops(Request $request)
    {
        $query = Shop::with(['owner', 'products'])
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
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $shops = $query->paginate($request->get('per_page', 15));

        return response()->json($shops);
    }

    public function updateShopStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'is_active' => 'required|boolean',
            'status' => 'required|in:pending,approved,suspended,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $shop = Shop::findOrFail($id);
        $shop->update($request->only(['is_active', 'status']));

        if ($request->status === 'approved') {
            $shop->update(['approved_at' => now()]);
        }

        return response()->json([
            'message' => 'Shop status updated successfully',
            'shop' => $shop
        ]);
    }

    public function reports(Request $request)
    {
        $type = $request->get('type', 'sales');
        $period = $request->get('period', 'monthly');

        switch ($type) {
            case 'sales':
                return $this->salesReport($period, $request);
            case 'products':
                return $this->productsReport($period, $request);
            case 'users':
                return $this->usersReport($period, $request);
            case 'orders':
                return $this->ordersReport($period, $request);
            default:
                return response()->json(['message' => 'Invalid report type'], 422);
        }
    }

    private function salesReport($period, $request)
    {
        $query = Payment::where('status', 'paid');

        if ($period === 'daily') {
            $data = $query->selectRaw('DATE(created_at) as date, SUM(amount) as total')
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->take(30)
                ->get();
        } elseif ($period === 'monthly') {
            $data = $query->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(amount) as total')
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->take(12)
                ->get();
        } else {
            $data = $query->selectRaw('YEAR(created_at) as year, SUM(amount) as total')
                ->groupBy('year')
                ->orderBy('year', 'desc')
                ->take(5)
                ->get();
        }

        return response()->json($data);
    }

    private function productsReport($period, $request)
    {
        $data = Product::withCount(['orderItems as sales_count'])
            ->withSum(['orderItems as revenue_sum'], 'total')
            ->orderBy('sales_count', 'desc')
            ->take(20)
            ->get();

        return response()->json($data);
    }

    private function usersReport($period, $request)
    {
        $data = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(30)
            ->get();

        return response()->json($data);
    }

    private function ordersReport($period, $request)
    {
        $data = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        return response()->json($data);
    }

    public function analytics()
    {
        $analytics = [
            'revenue_chart' => $this->getRevenueChart(),
            'orders_chart' => $this->getOrdersChart(),
            'top_categories' => $this->getTopCategories(),
            'user_growth' => $this->getUserGrowth(),
        ];

        return response()->json($analytics);
    }

    private function getRevenueChart()
    {
        return Payment::where('status', 'paid')
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getOrdersChart()
    {
        return Order::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getTopCategories()
    {
        return Category::withCount(['products'])
            ->orderBy('products_count', 'desc')
            ->take(10)
            ->get();
    }

    private function getUserGrowth()
    {
        return User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
}
