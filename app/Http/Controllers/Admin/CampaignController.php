<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::orderBy('start_date', 'desc')->paginate(10);
        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('admin.campaigns.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'target_audience' => 'required|string|max:255',
            'campaign_type' => 'required|in:email,sms,banner,popup',
            'status' => 'required|in:active,inactive,expired',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('campaigns', 'public');
        }

        Campaign::create($validated);

        return redirect()->route('admin.marketing.campaigns.index')->with('success', 'Campaign created successfully.');
    }

    public function edit(Campaign $campaign)
    {
        return view('admin.campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'target_audience' => 'required|string|max:255',
            'campaign_type' => 'required|in:email,sms,banner,popup',
            'status' => 'required|in:active,inactive,expired',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date'
        ]);

        if ($request->hasFile('image')) {
            if ($campaign->image) {
                \Storage::disk('public')->delete($campaign->image);
            }
            $validated['image'] = $request->file('image')->store('campaigns', 'public');
        }

        $campaign->update($validated);

        return redirect()->route('admin.marketing.campaigns.index')->with('success', 'Campaign updated successfully.');
    }

    public function delete(Campaign $campaign)
    {
        if ($campaign->image) {
            \Storage::disk('public')->delete($campaign->image);
        }
        
        $campaign->delete();

        return redirect()->route('admin.marketing.campaigns.index')->with('success', 'Campaign deleted successfully.');
    }

    public function toggleStatus(Campaign $campaign)
    {
        $newStatus = $campaign->status;
        
        // Auto-expire if end date has passed
        if ($campaign->end_date && $campaign->end_date->isPast()) {
            $newStatus = 'expired';
        } else {
            switch ($campaign->status) {
                case 'active':
                    $newStatus = 'inactive';
                    break;
                case 'inactive':
                    $newStatus = 'active';
                    break;
                case 'expired':
                    $newStatus = 'active';
                    break;
            }
        }

        $campaign->update([
            'status' => $newStatus
        ]);

        return back()->with('success', 'Campaign status updated successfully.');
    }
}
