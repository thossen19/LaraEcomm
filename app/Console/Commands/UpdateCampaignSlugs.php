<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Campaign;
use Illuminate\Support\Str;

class UpdateCampaignSlugs extends Command
{
    protected $signature = 'campaigns:update-slugs';
    
    protected $description = 'Update slugs for existing campaigns';

    public function handle()
    {
        $campaigns = Campaign::where('slug', '')->orWhereNull('slug')->get();
        
        foreach ($campaigns as $campaign) {
            $campaign->update([
                'slug' => Str::slug($campaign->title . '-' . $campaign->id)
            ]);
        }
        
        $this->info("Updated {$campaigns->count()} campaigns with slugs.");
        
        return Command::SUCCESS;
    }
}
