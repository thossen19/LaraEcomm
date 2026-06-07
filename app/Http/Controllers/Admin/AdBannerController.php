<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdBannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = AdBanner::query();

        // Filter by position
        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        // Filter by page
        if ($request->filled('page')) {
            $query->where('page', $request->page);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status === 'active');
        }

        $adBanners = $query->orderBy('sort_order', 'asc')
                          ->orderBy('created_at', 'desc')
                          ->paginate(20);

        $positions = AdBanner::POSITIONS;
        $pages = AdBanner::PAGES;

        return view('admin.cms.ad-banners.index', compact('adBanners', 'positions', 'pages'));
    }

    public function create()
    {
        $positions = AdBanner::POSITIONS;
        $pages = AdBanner::PAGES;

        return view('admin.cms.ad-banners.create', compact('positions', 'pages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'link' => 'nullable|url',
            'position' => ['required', Rule::in(array_keys(AdBanner::POSITIONS))],
            'page' => ['required', Rule::in(array_keys(AdBanner::PAGES))],
            'status' => 'boolean',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'description' => 'nullable|string|max:500',
            'alt_text' => 'nullable|string|max:255',
            'target_blank' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'height' => 'nullable|integer|min:50|max:500'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('ad-banners', 'public');
        }

        // Set default values
        $validated['status'] = $request->has('status');
        $validated['target_blank'] = $request->has('target_blank');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['height'] = $validated['height'] ?? 100; // Default to 100px
        $validated['clicks'] = 0;
        $validated['impressions'] = 0;

        AdBanner::create($validated);

        return redirect()->route('admin.cms.ad-banners.index')
                         ->with('success', 'Ad banner created successfully.');
    }

    public function edit(AdBanner $adBanner)
    {
        $positions = AdBanner::POSITIONS;
        $pages = AdBanner::PAGES;

        return view('admin.cms.ad-banners.edit', compact('adBanner', 'positions', 'pages'));
    }

    public function update(Request $request, AdBanner $adBanner)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'link' => 'nullable|url',
            'position' => ['required', Rule::in(array_keys(AdBanner::POSITIONS))],
            'page' => ['required', Rule::in(array_keys(AdBanner::PAGES))],
            'status' => 'boolean',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'description' => 'nullable|string|max:500',
            'alt_text' => 'nullable|string|max:255',
            'target_blank' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'height' => 'nullable|integer|min:50|max:500'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($adBanner->image) {
                Storage::disk('public')->delete($adBanner->image);
            }
            $validated['image'] = $request->file('image')->store('ad-banners', 'public');
        }

        // Set boolean values
        $validated['status'] = $request->has('status');
        $validated['target_blank'] = $request->has('target_blank');
        $validated['sort_order'] = $validated['sort_order'] ?? $adBanner->sort_order;

        $adBanner->update($validated);

        return redirect()->route('admin.cms.ad-banners.index')
                         ->with('success', 'Ad banner updated successfully.');
    }

    public function destroy(AdBanner $adBanner)
    {
        // Delete image
        if ($adBanner->image) {
            Storage::disk('public')->delete($adBanner->image);
        }

        $adBanner->delete();

        return redirect()->route('admin.cms.ad-banners.index')
                         ->with('success', 'Ad banner deleted successfully.');
    }

    public function toggleStatus(AdBanner $adBanner)
    {
        $adBanner->update([
            'status' => !$adBanner->status
        ]);

        return back()->with('success', 'Ad banner status updated successfully.');
    }

    public function duplicate(AdBanner $adBanner)
    {
        $newBanner = $adBanner->replicate();
        $newBanner->title = $adBanner->title . ' (Copy)';
        $newBanner->clicks = 0;
        $newBanner->impressions = 0;
        $newBanner->status = false; // Set as inactive by default
        $newBanner->save();

        // Duplicate image if exists
        if ($adBanner->image) {
            $oldPath = $adBanner->image;
            $newPath = 'ad-banners/' . uniqid() . '.' . pathinfo($oldPath, PATHINFO_EXTENSION);
            
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->copy($oldPath, $newPath);
                $newBanner->update(['image' => $newPath]);
            }
        }

        return redirect()->route('admin.cms.ad-banners.index')
                         ->with('success', 'Ad banner duplicated successfully.');
    }

    public function stats()
    {
        $totalBanners = AdBanner::count();
        $activeBanners = AdBanner::where('status', true)->count();
        $totalClicks = AdBanner::sum('clicks');
        $totalImpressions = AdBanner::sum('impressions');

        // Banners by position
        $bannersByPosition = AdBanner::selectRaw('position, COUNT(*) as count')
                                  ->groupBy('position')
                                  ->get()
                                  ->mapWithKeys(function ($item) {
                                      return [$item->position => $item->count];
                                  });

        // Banners by page
        $bannersByPage = AdBanner::selectRaw('page, COUNT(*) as count')
                               ->groupBy('page')
                               ->get()
                               ->mapWithKeys(function ($item) {
                                   return [$item->page => $item->count];
                               });

        // Top performing banners
        $topBanners = AdBanner::orderBy('clicks', 'desc')
                             ->take(10)
                             ->get();

        return view('admin.cms.ad-banners.stats', compact(
            'totalBanners',
            'activeBanners',
            'totalClicks',
            'totalImpressions',
            'bannersByPosition',
            'bannersByPage',
            'topBanners'
        ));
    }
}
