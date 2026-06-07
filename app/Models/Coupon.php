<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'minimum_amount',
        'usage_limit',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active',
        'is_public',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'is_public' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('expires_at', '>=', now());
    }

    public function scopeValid($query, $subtotal = 0)
    {
        return $query->where(function ($q) use ($subtotal) {
            $q->whereNull('minimum_amount')
              ->orWhere('minimum_amount', '<=', $subtotal);
        });
    }

    public function isValid($subtotal = 0)
    {
        if (!$this->is_active) return false;
        if (now()->lt($this->starts_at)) return false;
        if (now()->gt($this->expires_at)) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        if ($this->minimum_amount && $subtotal < $this->minimum_amount) return false;
        
        return true;
    }

    public function calculateDiscount($subtotal)
    {
        if (!$this->isValid($subtotal)) return 0;

        if ($this->type === 'fixed') {
            return min($this->value, $subtotal);
        } else {
            return min($subtotal * ($this->value / 100), $subtotal);
        }
    }

    public function incrementUsage()
    {
        $this->increment('used_count');
    }

    public function getStatusLabelAttribute()
    {
        if (!$this->is_active) return 'Inactive';
        if (now()->lt($this->starts_at)) return 'Scheduled';
        if (now()->gt($this->expires_at)) return 'Expired';
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return 'Used Up';
        return 'Active';
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status_label) {
            'Active' => 'green',
            'Scheduled' => 'blue',
            'Expired' => 'red',
            'Used Up' => 'orange',
            default => 'gray'
        };
    }

    public function getFormattedValueAttribute()
    {
        if ($this->type === 'fixed') {
            return '$' . number_format($this->value, 2);
        } else {
            return $this->value . '%';
        }
    }
}
