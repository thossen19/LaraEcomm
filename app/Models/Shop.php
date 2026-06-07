<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Shop extends Model
{
    protected $fillable = [
        'owner_id',
        'name',
        'slug',
        'description',
        'logo',
        'banner',
        'email',
        'phone',
        'address',
        'status',
        'commission_rate',
        'settings',
        'social_links',
        'is_active',
        'approved_at'
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'address' => 'array',
        'settings' => 'array',
        'social_links' => 'array',
        'is_active' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function activeProducts(): HasMany
    {
        return $this->products()->where('is_active', true);
    }

    public function orderItems()
    {
        return $this->hasManyThrough(OrderItem::class, Product::class, 'shop_id', 'product_id');
    }

    public function getOrdersCountAttribute()
    {
        $productIds = $this->products()->pluck('id');
        return \App\Models\Order::whereHas('items', function($query) use ($productIds) {
            $query->whereIn('product_id', $productIds);
        })->distinct('id')->count();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
