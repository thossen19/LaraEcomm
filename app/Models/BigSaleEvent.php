<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BigSaleEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'banner_image',
        'background_color',
        'text_color',
        'button_text',
        'button_link',
        'discount_percentage',
        'original_price',
        'sale_price',
        'start_date',
        'end_date',
        'status',
        'sort_order',
        'featured',
        'meta_title',
        'meta_description'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => 'boolean',
        'featured' => 'boolean',
        'discount_percentage' => 'decimal:2',
        'original_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'sort_order' => 'integer'
    ];

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
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

    public function getFormattedDiscountAttribute()
    {
        return number_format($this->discount_percentage, 0) . '%';
    }

    public function getFormattedOriginalPriceAttribute()
    {
        $currency = \App\Models\Setting::get('currency', 'USD');
        return \App\Helpers\CurrencyHelper::format($this->original_price, $currency);
    }

    public function getFormattedSalePriceAttribute()
    {
        $currency = \App\Models\Setting::get('currency', 'USD');
        return \App\Helpers\CurrencyHelper::format($this->sale_price, $currency);
    }

    public function getSavingsAmountAttribute()
    {
        return $this->original_price - $this->sale_price;
    }

    public function getFormattedSavingsAmountAttribute()
    {
        $currency = \App\Models\Setting::get('currency', 'USD');
        return \App\Helpers\CurrencyHelper::format($this->savings_amount, $currency);
    }

    public function getBannerImageUrlAttribute()
    {
        if ($this->banner_image) {
            return asset('storage/' . $this->banner_image);
        }
        return null;
    }

    public function getDaysLeftAttribute()
    {
        if (!$this->end_date) {
            return null;
        }
        
        $days = now()->diffInDays($this->end_date, false);
        
        if ($days < 0) {
            return 'Ended';
        } elseif ($days === 0) {
            return 'Last Day';
        } elseif ($days === 1) {
            return '1 Day Left';
        } else {
            return $days . ' Days Left';
        }
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

    public function getUrgencyLevelAttribute()
    {
        if (!$this->end_date) {
            return 'normal';
        }

        $daysLeft = now()->diffInDays($this->end_date, false);
        
        if ($daysLeft < 0) {
            return 'ended';
        } elseif ($daysLeft <= 1) {
            return 'critical';
        } elseif ($daysLeft <= 3) {
            return 'high';
        } elseif ($daysLeft <= 7) {
            return 'medium';
        } else {
            return 'normal';
        }
    }
}
