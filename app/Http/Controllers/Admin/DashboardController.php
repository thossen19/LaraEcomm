<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Cart;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get date range for filtering
        $startDate = $request->get('start_date', Carbon::now()->subDays(30));
        $endDate = $request->get('end_date', Carbon::now());
        
        // Sales Statistics - with fallback values
        try {
            $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', '!=', 'cancelled')
                ->count();
            
            $totalRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', '!=', 'cancelled')
                ->sum('total');
        } catch (\Exception $e) {
            $totalOrders = 0;
            $totalRevenue = 0;
        }
        
        try {
            $totalProducts = Product::count();
        } catch (\Exception $e) {
            $totalProducts = 0;
        }
        
        try {
            $totalUsers = User::count();
        } catch (\Exception $e) {
            $totalUsers = 0;
        }
        
        // Recent Orders - with fallback
        try {
            $recentOrders = Order::with(['user', 'items.product'])
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
        } catch (\Exception $e) {
            $recentOrders = collect();
        }
        
        // Top Products - with fallback
        try {
            $topProducts = \App\Models\OrderItem::selectRaw('product_id, SUM(quantity) as total_sold, SUM(total) as total_revenue')
                ->with('product')
                ->groupBy('product_id')
                ->orderBy('total_sold', 'desc')
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            $topProducts = collect();
        }
        
        // Order Status Breakdown - with fallback
        try {
            $orderStats = Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', '!=', 'cancelled')
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();
        } catch (\Exception $e) {
            $orderStats = [];
        }
        
        // Daily Sales Chart Data - with fallback
        try {
            $dailySales = Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', '!=', 'cancelled')
                ->selectRaw('DATE(created_at) as date, SUM(total) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->get();
        } catch (\Exception $e) {
            $dailySales = collect();
        }
        
        // Monthly Revenue Chart Data - with fallback
        try {
            $monthlyRevenue = Order::whereBetween('created_at', [
                    Carbon::now()->subMonths(11)->startOfMonth(),
                    Carbon::now()
                ])
                ->where('status', '!=', 'cancelled')
                ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total) as total')
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        } catch (\Exception $e) {
            $monthlyRevenue = collect();
        }
        
        return view('admin.dashboard.index', compact(
            'totalOrders',
            'totalRevenue',
            'totalProducts',
            'totalUsers',
            'recentOrders',
            'topProducts',
            'orderStats',
            'dailySales',
            'monthlyRevenue',
            'startDate',
            'endDate'
        ));
    }

    public function salesReport(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30));
        $endDate = $request->get('end_date', Carbon::now());
        
        $orders = Order::with(['user', 'items.product'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $totalRevenue = $orders->sum('total');
        $totalOrders = $orders->count();
        
        return view('admin.dashboard.sales-report', compact(
            'orders',
            'totalRevenue',
            'totalOrders',
            'startDate',
            'endDate'
        ));
    }

    public function inventoryReport()
    {
        $products = Product::with(['categories', 'brands'])
            ->orderBy('quantity', 'asc')
            ->get();
        
        $lowStockProducts = Product::where('quantity', '<=', 10)->get();
        $outOfStockProducts = Product::where('quantity', '=', 0)->get();
        
        return view('admin.dashboard.inventory-report', compact(
            'products',
            'lowStockProducts',
            'outOfStockProducts'
        ));
    }

    public function userReport()
    {
        $users = User::withCount('orders')
            ->withSum('orders', 'total')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $totalUsers = User::count();
        $newUsersThisMonth = User::where('created_at', '>=', Carbon::now()->subMonth())->count();
        
        return view('admin.dashboard.user-report', compact(
            'users',
            'totalUsers',
            'newUsersThisMonth'
        ));
    }
}
