<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdBanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'link',
        'position',
        'page',
        'status',
        'start_date',
        'end_date',
        'clicks',
        'impressions',
        'description',
        'alt_text',
        'target_blank',
        'sort_order',
        'height'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'target_blank' => 'boolean',
        'status' => 'boolean',
        'clicks' => 'integer',
        'impressions' => 'integer',
        'sort_order' => 'integer',
        'height' => 'integer'
    ];

    // Positions where banners can be displayed
    const POSITIONS = [
        'header' => 'Header Top',
        'sidebar_top' => 'Sidebar Top',
        'sidebar_middle' => 'Sidebar Middle',
        'sidebar_bottom' => 'Sidebar Bottom',
        'content_top' => 'Content Top',
        'content_middle' => 'Content Middle',
        'content_bottom' => 'Content Bottom',
        'footer' => 'Footer',
        'popup' => 'Popup Modal',
        'homepage_hero' => 'Homepage Hero',
        'homepage_featured' => 'Homepage Featured',
        'category_top' => 'Category Page Top',
        'product_detail' => 'Product Detail Sidebar'
    ];

    // Pages where banners can be displayed
    const PAGES = [
        'all' => 'All Pages',
        'home' => 'Homepage',
        'products' => 'Products Page',
        'categories' => 'Categories Page',
        'product_detail' => 'Product Detail Page',
        'cart' => 'Cart Page',
        'checkout' => 'Checkout Page',
        'about' => 'About Page',
        'contact' => 'Contact Page',
        'blog' => 'Blog Page',
        'deals' => 'Deals Page'
    ];

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeForPosition($query, $position)
    {
        return $query->where('position', $position);
    }

    public function scopeForPage($query, $page)
    {
        return $query->where(function ($q) use ($page) {
            $q->where('page', 'all')->orWhere('page', $page);
        });
    }

    public function scopeCurrentlyActive($query)
    {
        $today = now()->toDateString();
        return $query->where(function ($q) use ($today) {
            $q->whereNull('start_date')
              ->orWhere('start_date', '<=', $today);
        })->where(function ($q) use ($today) {
            $q->whereNull('end_date')
              ->orWhere('end_date', '>=', $today);
        });
    }

    public function getFormattedPositionAttribute()
    {
        return self::POSITIONS[$this->position] ?? $this->position;
    }

    public function getFormattedPageAttribute()
    {
        return self::PAGES[$this->page] ?? $this->page;
    }

    public function getHeightAttribute()
    {
        return $this->attributes['height'] ?? 100; // Default to 100px
    }

    public function getHeightStyleAttribute()
    {
        return 'height: ' . $this->height . 'px';
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }

    public function incrementClicks()
    {
        $this->increment('clicks');
    }

    public function incrementImpressions()
    {
        $this->increment('impressions');
    }

    public function getClickThroughRateAttribute()
    {
        if ($this->impressions > 0) {
            return round(($this->clicks / $this->impressions) * 100, 2);
        }
        return 0;
    }

    public function isActive()
    {
        if (!$this->status) {
            return false;
        }

        $today = now()->toDateString();
        
        if ($this->start_date && $this->start_date > $today) {
            return false;
        }
        
        if ($this->end_date && $this->end_date < $today) {
            return false;
        }
        
        return true;
    }
}
