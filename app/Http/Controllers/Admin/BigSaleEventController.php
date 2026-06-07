<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BigSaleEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BigSaleEventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = BigSaleEvent::query();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status === 'active');
        }

        // Filter by featured
        if ($request->filled('featured')) {
            $query->where('featured', $request->featured === 'yes');
        }

        $bigSaleEvents = $query->orderBy('sort_order', 'asc')
                              ->orderBy('created_at', 'desc')
                              ->paginate(20);

        return view('admin.cms.big-sale-events.index', compact('bigSaleEvents'));
    }

    public function create()
    {
        return view('admin.cms.big-sale-events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'required|string',
            'banner_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'background_color' => 'nullable|string|max:7',
            'text_color' => 'nullable|string|max:7',
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|url',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'original_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'boolean',
            'featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500'
        ]);

        // Handle banner image upload
        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $request->file('banner_image')->store('big-sale-events', 'public');
        }

        // Set default values
        $validated['status'] = $request->has('status');
        $validated['featured'] = $request->has('featured');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['background_color'] = $validated['background_color'] ?? '#FF6B6B';
        $validated['text_color'] = $validated['text_color'] ?? '#FFFFFF';
        $validated['button_text'] = $validated['button_text'] ?? 'Shop Now';

        BigSaleEvent::create($validated);

        return redirect()->route('admin.cms.big-sale-events.index')
                         ->with('success', 'Big Sale Event created successfully.');
    }

    public function edit(BigSaleEvent $bigSaleEvent)
    {
        return view('admin.cms.big-sale-events.edit', compact('bigSaleEvent'));
    }

    public function update(Request $request, BigSaleEvent $bigSaleEvent)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'required|string',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'background_color' => 'nullable|string|max:7',
            'text_color' => 'nullable|string|max:7',
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|url',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'original_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'boolean',
            'featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500'
        ]);

        // Handle banner image upload
        if ($request->hasFile('banner_image')) {
            // Delete old image
            if ($bigSaleEvent->banner_image) {
                Storage::disk('public')->delete($bigSaleEvent->banner_image);
            }
            $validated['banner_image'] = $request->file('banner_image')->store('big-sale-events', 'public');
        }

        // Set boolean values
        $validated['status'] = $request->has('status');
        $validated['featured'] = $request->has('featured');
        $validated['sort_order'] = $validated['sort_order'] ?? $bigSaleEvent->sort_order;

        $bigSaleEvent->update($validated);

        return redirect()->route('admin.cms.big-sale-events.index')
                         ->with('success', 'Big Sale Event updated successfully.');
    }

    public function destroy(BigSaleEvent $bigSaleEvent)
    {
        // Delete banner image
        if ($bigSaleEvent->banner_image) {
            Storage::disk('public')->delete($bigSaleEvent->banner_image);
        }

        $bigSaleEvent->delete();

        return redirect()->route('admin.cms.big-sale-events.index')
                         ->with('success', 'Big Sale Event deleted successfully.');
    }

    public function toggleStatus(BigSaleEvent $bigSaleEvent)
    {
        $bigSaleEvent->update([
            'status' => !$bigSaleEvent->status
        ]);

        return back()->with('success', 'Big Sale Event status updated successfully.');
    }

    public function toggleFeatured(BigSaleEvent $bigSaleEvent)
    {
        $bigSaleEvent->update([
            'featured' => !$bigSaleEvent->featured
        ]);

        return back()->with('success', 'Big Sale Event featured status updated successfully.');
    }

    public function duplicate(BigSaleEvent $bigSaleEvent)
    {
        $newEvent = $bigSaleEvent->replicate();
        $newEvent->title = $bigSaleEvent->title . ' (Copy)';
        $newEvent->status = false; // Set as inactive by default
        $newEvent->featured = false;
        $newEvent->save();

        // Duplicate banner image if exists
        if ($bigSaleEvent->banner_image) {
            $oldPath = $bigSaleEvent->banner_image;
            $newPath = 'big-sale-events/' . uniqid() . '.' . pathinfo($oldPath, PATHINFO_EXTENSION);
            
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->copy($oldPath, $newPath);
                $newEvent->update(['banner_image' => $newPath]);
            }
        }

        return redirect()->route('admin.cms.big-sale-events.index')
                         ->with('success', 'Big Sale Event duplicated successfully.');
    }
}
