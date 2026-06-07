<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fashion extends Model
{
    use HasFactory;

    protected $table = 'fashion';

    protected $fillable = [
        'title',
        'description',
        'image',
        'link',
        'original_price',
        'sale_price',
        'category',
        'brand',
        'sort_order',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'original_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'sort_order' => 'integer',
        'status' => 'string',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function getFormattedOriginalPriceAttribute()
    {
        return '₹' . number_format($this->original_price, 2);
    }

    public function getFormattedSalePriceAttribute()
    {
        return $this->sale_price ? '₹' . number_format($this->sale_price, 2) : null;
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->sale_price && $this->original_price) {
            return round((($this->original_price - $this->sale_price) / $this->original_price) * 100, 2);
        }
        return 0;
    }

    public function getFormattedDiscountAttribute()
    {
        $discount = $this->discount_percentage;
        return $discount > 0 ? '-' . $discount . '%' : '';
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
