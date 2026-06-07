<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['categories', 'brands'])
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
            })
            ->when($request->category, function ($query, $category) {
                $query->whereHas('categories', function ($q) use ($category) {
                    $q->where('name', $category);
                });
            })
            ->when($request->stock_status, function ($query, $status) {
                if ($status === 'low') {
                    $query->where('quantity', '<=', 10);
                } elseif ($status === 'out') {
                    $query->where('quantity', '=', 0);
                } elseif ($status === 'normal') {
                    $query->where('quantity', '>', 10);
                }
            })
            ->orderBy('quantity', 'asc')
            ->paginate(20);

        // Calculate statistics
        $totalProducts = Product::count();
        $inStockProducts = Product::where('quantity', '>', 10)->count();
        $lowStockProducts = Product::where('quantity', '>', 0)->where('quantity', '<=', 10)->count();
        $outOfStockProducts = Product::where('quantity', '=', 0)->count();
        
        $categories = \App\Models\Category::orderBy('name')->get();
        $lowStockAlerts = Product::where('quantity', '<=', 10)->get();

        return view('admin.inventory.index', compact('products', 'categories', 'lowStockAlerts', 'totalProducts', 'inStockProducts', 'lowStockProducts', 'outOfStockProducts'));
    }

    public function adjust(Product $product)
    {
        $stockHistory = $product->inventoryHistory()
            ->with('admin')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.inventory.adjust', compact('product', 'stockHistory'));
    }

    public function storeAdjustment(Request $request, Product $product)
    {
        $validated = $request->validate([
            'adjustment_type' => 'required|in:add,subtract,set',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000'
        ]);

        $currentQuantity = $product->quantity;
        $adjustmentQuantity = $validated['quantity'];

        if ($validated['adjustment_type'] === 'add') {
            $newQuantity = $currentQuantity + $adjustmentQuantity;
        } elseif ($validated['adjustment_type'] === 'subtract') {
            $newQuantity = max(0, $currentQuantity - $adjustmentQuantity);
        } else {
            $newQuantity = $adjustmentQuantity;
        }

        $product->update(['quantity' => $newQuantity]);

        // Create inventory history record
        $product->inventoryHistory()->create([
            'previous_quantity' => $currentQuantity,
            'new_quantity' => $newQuantity,
            'adjustment_type' => $validated['adjustment_type'],
            'quantity' => $adjustmentQuantity,
            'reason' => $validated['reason'],
            'notes' => $validated['notes'],
            'admin_id' => auth()->id()
        ]);

        return redirect()->route('admin.inventory.index')->with('success', 'Inventory adjusted successfully.');
    }

    public function history(Product $product)
    {
        $history = $product->inventoryHistory()
            ->with('admin')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.inventory.history', compact('product', 'history'));
    }

    public function export()
    {
        $products = Product::with(['categories', 'brands'])->orderBy('name')->get();

        $filename = 'inventory-export-' . now()->format('Y-m-d-His') . '.csv';
        $handle = fopen('php://temp', 'w+');

        fputcsv($handle, ['Name', 'SKU', 'Category', 'Brand', 'Quantity', 'Low Stock Threshold', 'Price', 'Status']);

        foreach ($products as $product) {
            fputcsv($handle, [
                $product->name,
                $product->sku,
                $product->categories->first()?->name ?? '',
                $product->brands->first()?->name ?? '',
                $product->quantity,
                $product->low_stock_threshold ?? 10,
                $product->price,
                $product->quantity == 0 ? 'Out of Stock' : ($product->quantity <= ($product->low_stock_threshold ?? 10) ? 'Low Stock' : 'In Stock'),
            ]);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function bulkUpdate()
    {
        $products = Product::with(['categories'])->orderBy('name')->get();
        return view('admin.inventory.bulk-update', compact('products'));
    }

    public function processBulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:0',
        ]);

        $count = 0;
        foreach ($validated['products'] as $data) {
            Product::where('id', $data['id'])->update(['quantity' => $data['quantity']]);
            $count++;
        }

        return redirect()->route('admin.inventory.index')->with('success', "{$count} products updated successfully.");
    }

    public function quickUpdate(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0'
        ]);

        $product->update(['quantity' => $validated['quantity']]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Quantity updated successfully.']);
        }

        return back()->with('success', 'Quantity updated successfully.');
    }
}
