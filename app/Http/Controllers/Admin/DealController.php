<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function index()
    {
        $deals = Deal::orderBy('sort_order', 'asc')->paginate(10);
        return view('admin.deals.index', compact('deals'));
    }

    public function create()
    {
        return view('admin.deals.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'original_price' => 'required|numeric|min:0',
            'deal_price' => 'required|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('deals', 'public');
        }

        Deal::create($validated);

        return redirect()->route('admin.deals.index')->with('success', 'Deal created successfully.');
    }

    public function edit(Deal $deal)
    {
        return view('admin.deals.edit', compact('deal'));
    }

    public function update(Request $request, Deal $deal)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'original_price' => 'required|numeric|min:0',
            'deal_price' => 'required|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date'
        ]);

        if ($request->hasFile('image')) {
            if ($deal->image) {
                \Storage::disk('public')->delete($deal->image);
            }
            $validated['image'] = $request->file('image')->store('deals', 'public');
        }

        $deal->update($validated);

        return redirect()->route('admin.deals.index')->with('success', 'Deal updated successfully.');
    }

    public function delete(Deal $deal)
    {
        if ($deal->image) {
            \Storage::disk('public')->delete($deal->image);
        }
        
        $deal->delete();

        return redirect()->route('admin.deals.index')->with('success', 'Deal deleted successfully.');
    }

    public function toggleStatus(Deal $deal)
    {
        $newStatus = $deal->status === 'active' ? 'inactive' : 'active';

        $deal->update([
            'status' => $newStatus
        ]);

        return back()->with('success', 'Deal status updated successfully.');
    }
}
