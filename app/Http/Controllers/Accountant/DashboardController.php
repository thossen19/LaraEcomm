<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:super_admin,accountant']);
    }

    public function index(Request $request)
    {
        // Get date range for filtering
        $startDate = $request->get('start_date', Carbon::now()->subDays(30));
        $endDate = $request->get('end_date', Carbon::now());
        
        // Financial Summary
        $totalRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->sum('total');
        
        $totalExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->sum('amount');
        
        $netProfit = $totalRevenue - $totalExpenses;
        
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->count();
        
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        
        // Payment Statistics
        $paymentStats = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get();
        
        // Expense Categories
        $expenseByCategory = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();
        
        // Daily Revenue/Expense Chart Data
        $dailyFinancials = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->selectRaw('DATE(created_at) as date, SUM(total) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        $dailyExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->selectRaw('expense_date as date, SUM(amount) as expense')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Monthly Financial Data
        $monthlyFinancials = Order::whereBetween('created_at', [
                Carbon::now()->subMonths(11)->startOfMonth(),
                Carbon::now()
            ])
            ->where('status', '!=', 'cancelled')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total) as revenue')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        return view('accountant.dashboard.index', compact(
            'totalRevenue',
            'totalExpenses',
            'netProfit',
            'totalOrders',
            'averageOrderValue',
            'paymentStats',
            'expenseByCategory',
            'dailyFinancials',
            'dailyExpenses',
            'monthlyFinancials',
            'startDate',
            'endDate'
        ));
    }

    public function financialReport(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30));
        $endDate = $request->get('end_date', Carbon::now());
        
        // Detailed Orders
        $orders = Order::with(['user', 'items.product'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Detailed Expenses
        $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->with('user')
            ->orderBy('expense_date', 'desc')
            ->get();
        
        // Payments
        $payments = Payment::with(['order', 'order.user'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Summary Statistics
        $totalRevenue = $orders->sum('total');
        $totalExpenses = $expenses->sum('amount');
        $netProfit = $totalRevenue - $totalExpenses;
        
        return view('accountant.dashboard.financial-report', compact(
            'orders',
            'expenses',
            'payments',
            'totalRevenue',
            'totalExpenses',
            'netProfit',
            'startDate',
            'endDate'
        ));
    }

    public function expenseReport()
    {
        $expenses = Expense::with('user')
            ->orderBy('expense_date', 'desc')
            ->paginate(20);
        
        return view('accountant.dashboard.expense-report', compact('expenses'));
    }

    public function createExpense()
    {
        return view('accountant.dashboard.create-expense');
    }

    public function storeExpense(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'expense_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'receipt' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('receipt')) {
            $validated['receipt'] = $request->file('receipt')->store('expenses', 'public');
        }

        Expense::create($validated);

        return redirect()->route('accountant.expense-report')
                    ->with('success', 'Expense added successfully!');
    }

    public function taxReport(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30));
        $endDate = $request->get('end_date', Carbon::now());
        
        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->get();
        
        $taxRate = setting('tax_rate', 10) / 100;
        $totalTax = $orders->sum('total') * $taxRate;
        
        return view('accountant.dashboard.tax-report', compact(
            'orders',
            'totalTax',
            'taxRate',
            'startDate',
            'endDate'
        ));
    }
}
