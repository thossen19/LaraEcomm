<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $coupons = Coupon::query()
            ->when($request->search, function ($query, $search) {
                $query->where('code', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%");
            })
            ->when($request->status, function ($query, $status) {
                if ($status === 'expired') {
                    $query->where(function ($q) {
                        $q->where('expires_at', '<', now())
                          ->orWhere(function ($q2) {
                              $q2->where('usage_limit', '>', 0)
                                 ->whereColumn('used_count', '>=', 'usage_limit');
                          });
                    });
                } else {
                    $query->where('is_active', $status === 'active');
                }
            })
            ->when($request->type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.coupons.index', compact('coupons'));
    }

    public function show(Coupon $coupon)
    {
        return view('admin.coupons.show', compact('coupon'));
    }

    public function create()
    {
        $products = \App\Models\Product::orderBy('name')->get();
        $categories = \App\Models\Category::orderBy('name')->get();
        
        return view('admin.coupons.create', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:coupons',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'description' => 'nullable|string|max:1000'
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_public'] = $request->boolean('is_public');
        $validated['starts_at'] = $request->filled('starts_at') ? $request->starts_at : now();
        $validated['expires_at'] = $request->filled('expires_at') ? $request->expires_at : now()->addYear();

        Coupon::create($validated);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully.');
    }

    public function edit(Coupon $coupon)
    {
        $products = \App\Models\Product::orderBy('name')->get();
        $categories = \App\Models\Category::orderBy('name')->get();

        return view('admin.coupons.edit', compact('coupon', 'products', 'categories'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'description' => 'nullable|string|max:1000'
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_public'] = $request->boolean('is_public');
        $validated['starts_at'] = $request->filled('starts_at') ? $request->starts_at : $coupon->starts_at;
        $validated['expires_at'] = $request->filled('expires_at') ? $request->expires_at : $coupon->expires_at;

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted successfully.');
    }

    public function toggleStatus(Coupon $coupon)
    {
        $coupon->update([
            'is_active' => !$coupon->is_active
        ]);

        return back()->with('success', 'Coupon status updated successfully.');
    }

    public function duplicate(Coupon $coupon)
    {
        $newCoupon = $coupon->replicate();
        $newCoupon->code = $coupon->code . '_COPY';
        $newCoupon->name = $coupon->name . ' (Copy)';
        $newCoupon->is_active = false;
        $newCoupon->save();

        return redirect()->route('admin.coupons.edit', $newCoupon)->with('success', 'Coupon duplicated successfully.');
    }

    public function sendTestEmail(Coupon $coupon)
    {
        // This would send a test email with coupon details
        // Implementation depends on your email service
        
        return back()->with('success', 'Test email sent successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $couponIds = $request->input('coupons', []);
        
        if (empty($couponIds)) {
            return back()->with('error', 'No coupons selected.');
        }

        Coupon::whereIn('id', $couponIds)->delete();

        return back()->with('success', 'Selected coupons deleted successfully.');
    }
}
