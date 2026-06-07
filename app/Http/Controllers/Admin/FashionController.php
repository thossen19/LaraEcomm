<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fashion;
use Illuminate\Http\Request;

class FashionController extends Controller
{
    public function index()
    {
        $fashionItems = Fashion::orderBy('sort_order', 'asc')->paginate(10);
        return view('admin.fashion.index', compact('fashionItems'));
    }

    public function create()
    {
        return view('admin.fashion.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url',
            'original_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'category' => 'required|string|max:100',
            'brand' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('fashion', 'public');
        }

        Fashion::create($validated);

        return redirect()->route('admin.fashion.index')->with('success', 'Fashion item created successfully.');
    }

    public function edit(Fashion $fashion)
    {
        return view('admin.fashion.edit', compact('fashion'));
    }

    public function update(Request $request, Fashion $fashion)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url',
            'original_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'category' => 'required|string|max:100',
            'brand' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date'
        ]);

        if ($request->hasFile('image')) {
            if ($fashion->image) {
                \Storage::disk('public')->delete($fashion->image);
            }
            $validated['image'] = $request->file('image')->store('fashion', 'public');
        }

        $fashion->update($validated);

        return redirect()->route('admin.fashion.index')->with('success', 'Fashion item updated successfully.');
    }

    public function delete(Fashion $fashion)
    {
        if ($fashion->image) {
            \Storage::disk('public')->delete($fashion->image);
        }
        
        $fashion->delete();

        return redirect()->route('admin.fashion.index')->with('success', 'Fashion item deleted successfully.');
    }

    public function toggleStatus(Fashion $fashion)
    {
        $newStatus = $fashion->status === 'active' ? 'inactive' : 'active';

        $fashion->update([
            'status' => $newStatus
        ]);

        return back()->with('success', 'Fashion item status updated successfully.');
    }
}
