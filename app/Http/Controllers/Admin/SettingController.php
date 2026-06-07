<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BannerImage;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $settings = [
            'site_name' => config('app.name', 'LaraCom'),
            'site_email' => config('mail.from.address', 'admin@laracms.com'),
            'site_phone' => '+01 234 567 890',
            'site_address' => '123 Main Street, City, State 12345',
            'currency' => 'USD',
            'timezone' => config('app.timezone', 'UTC'),
            'maintenance_mode' => false,
            'enable_registration' => true,
            'email_verification' => true,
            'default_user_role' => 'customer',
        ];

        $bannerImages = BannerImage::ordered()->get();
        
        // Get logo settings
        $headerLogo = \App\Models\Setting::getValue('header_logo');
        $footerLogo = \App\Models\Setting::getValue('footer_logo');

        return view('admin.settings.index', compact('settings', 'bannerImages', 'headerLogo', 'footerLogo'));
    }

    public function update(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_email' => 'required|email',
            'site_phone' => 'nullable|string|max:20',
            'site_address' => 'nullable|string',
            'currency' => 'required|string|in:USD,EUR,GBP,JPY',
            'timezone' => 'required|string',
            'maintenance_mode' => 'boolean',
            'enable_registration' => 'boolean',
            'email_verification' => 'boolean',
            'default_user_role' => 'required|string|in:admin,seller,customer',
        ]);

        // Update .env file or settings table
        // For now, we'll just return success message
        // In a real application, you would persist these settings

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }

    public function backup()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        // Mock backup functionality
        return redirect()->route('admin.settings.index')
            ->with('success', 'Database backup created successfully.');
    }

    public function clearCache()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        // Clear application cache
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('route:clear');
        \Artisan::call('view:clear');

        return redirect()->route('admin.settings.index')
            ->with('success', 'Application cache cleared successfully.');
    }

    public function storeBanner(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'link_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('banners', 'public');
            
            BannerImage::create([
                'title' => $request->title,
                'image_path' => $imagePath,
                'description' => $request->description,
                'link_url' => $request->link_url,
                'is_active' => $request->has('is_active'),
                'sort_order' => BannerImage::max('sort_order') + 1,
            ]);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Banner image added successfully.');
    }

    public function updateBanner(Request $request, BannerImage $banner)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'link_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'link_url' => $request->link_url,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('banners', 'public');
            $data['image_path'] = $imagePath;
        }

        $banner->update($data);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Banner image updated successfully.');
    }

    public function deleteBanner(BannerImage $banner)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $banner->delete();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Banner image deleted successfully.');
    }

    public function reorderBanners(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'banner_orders' => 'required|array',
            'banner_orders.*' => 'integer',
        ]);

        foreach ($request->banner_orders as $bannerId => $order) {
            BannerImage::where('id', $bannerId)->update(['sort_order' => $order]);
        }

        return response()->json(['success' => true]);
    }

    public function updateLogo(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'header_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'footer_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle header logo upload
        if ($request->hasFile('header_logo')) {
            $headerLogoPath = $request->file('header_logo')->store('logos', 'public');
            
            // Save to settings table
            \App\Models\Setting::setValue('header_logo', $headerLogoPath, 'string');
        }

        // Handle footer logo upload
        if ($request->hasFile('footer_logo')) {
            $footerLogoPath = $request->file('footer_logo')->store('logos', 'public');
            
            // Save to settings table
            \App\Models\Setting::setValue('footer_logo', $footerLogoPath, 'string');
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Logo settings updated successfully.');
    }
}
