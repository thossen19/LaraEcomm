<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index()
    {
        // Get all campaigns for public display
        $activeCampaigns = Campaign::active()->current()->ordered()->get();
        $upcomingCampaigns = Campaign::upcoming()->ordered()->get();
        $expiredCampaigns = Campaign::expired()->ordered()->take(6)->get();
        
        return view('campaigns.index', compact(
            'activeCampaigns', 
            'upcomingCampaigns', 
            'expiredCampaigns'
        ));
    }
    
    public function show(Campaign $campaign)
    {
        // Only show active or upcoming campaigns
        if ($campaign->status !== 'active' && $campaign->status !== 'inactive') {
            abort(404);
        }
        
        // Get related campaigns
        $relatedCampaigns = Campaign::where('id', '!=', $campaign->id)
                                   ->active()
                                   ->current()
                                   ->take(3)
                                   ->get();
        
        return view('campaigns.show', compact('campaign', 'relatedCampaigns'));
    }
}
