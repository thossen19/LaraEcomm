<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Deal extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'link',
        'discount_percentage',
        'original_price',
        'deal_price',
        'sort_order',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
        'original_price' => 'decimal:2',
        'deal_price' => 'decimal:2',
        'sort_order' => 'integer',
        'status' => 'string',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'id';
    }

    public function getDiscountPercentageAttribute($value)
    {
        return (float) $value;
    }

    public function getOriginalPriceAttribute($value)
    {
        return (float) $value;
    }

    public function getDealPriceAttribute($value)
    {
        return (float) $value;
    }

    public function getFormattedOriginalPriceAttribute()
    {
        $currency = \App\Models\Setting::get('currency', 'USD');
        return \App\Helpers\CurrencyHelper::format($this->original_price, $currency);
    }

    public function getFormattedDealPriceAttribute()
    {
        $currency = \App\Models\Setting::get('currency', 'USD');
        return \App\Helpers\CurrencyHelper::format($this->deal_price, $currency);
    }

    public function getFormattedDiscountAttribute()
    {
        return $this->discount_percentage . '%';
    }

    public function getFormattedDiscountValueAttribute()
    {
        return $this->discount_percentage . '%';
    }

    public function getDaysRemainingAttribute()
    {
        if ($this->end_date) {
            return Carbon::parse($this->end_date)->diffInDays(now(), false);
        }
        return null;
    }

    public function getIsExpiredAttribute()
    {
        return $this->end_date && Carbon::parse($this->end_date)->isPast();
    }

    public function getIsUpcomingAttribute()
    {
        return $this->start_date && Carbon::parse($this->start_date)->isFuture();
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active' && 
               !$this->is_expired && 
               !$this->is_upcoming;
    }

    public function getSalePriceAttribute()
    {
        return $this->deal_price;
    }

    public function getFormattedMinPurchaseAttribute()
    {
        // This is a placeholder since deals table doesn't have min_purchase_amount
        return null;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCurrent($query)
    {
        return $query->where(function($q) {
            $q->whereNull('start_date')
              ->orWhere('start_date', '<=', now());
        })->where(function($q) {
            $q->whereNull('end_date')
              ->orWhere('end_date', '>=', now());
        });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
    }
}
