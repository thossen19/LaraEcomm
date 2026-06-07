<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'link',
        'discount_type',
        'discount_value',
        'min_purchase_amount',
        'max_discount_amount',
        'target_audience',
        'campaign_type',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_purchase_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'status' => 'string',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($campaign) {
            if (empty($campaign->slug)) {
                $campaign->slug = Str::slug($campaign->title);
            }
        });
        
        static::updating(function ($campaign) {
            if ($campaign->isDirty('title') && empty($campaign->slug)) {
                $campaign->slug = Str::slug($campaign->title);
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getFormattedDiscountValueAttribute()
    {
        $currency = \App\Models\Setting::get('currency', 'USD');
        if ($this->discount_type === 'percentage') {
            return $this->discount_value . '%';
        }
        return \App\Helpers\CurrencyHelper::format($this->discount_value, $currency);
    }

    public function getFormattedMinPurchaseAttribute()
    {
        $currency = \App\Models\Setting::get('currency', 'USD');
        return $this->min_purchase_amount ? \App\Helpers\CurrencyHelper::format($this->min_purchase_amount, $currency) : null;
    }

    public function getFormattedMaxDiscountAttribute()
    {
        $currency = \App\Models\Setting::get('currency', 'USD');
        return $this->max_discount_amount ? \App\Helpers\CurrencyHelper::format($this->max_discount_amount, $currency) : null;
    }

    public function getDaysRemainingAttribute()
    {
        if ($this->end_date) {
            return now()->diffInDays($this->end_date, false);
        }
        return null;
    }

    public function getIsExpiredAttribute()
    {
        return $this->end_date && $this->end_date->isPast();
    }

    public function getIsUpcomingAttribute()
    {
        return $this->start_date && $this->start_date->isFuture();
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active' && 
               !$this->is_expired && 
               !$this->is_upcoming;
    }

    public function getStatusColorAttribute()
    {
        switch ($this->status) {
            case 'active':
                return 'green';
            case 'inactive':
                return 'gray';
            case 'expired':
                return 'red';
            default:
                return 'gray';
        }
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCurrent($query)
    {
        return $query->where('start_date', '<=', now())
                     ->where('end_date', '>=', now());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('end_date', '<', now());
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('start_date', 'desc');
    }
}
