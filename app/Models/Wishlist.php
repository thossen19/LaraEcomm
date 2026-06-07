<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wishlist extends Model
{
    use HasFactory;

    protected $table = 'wishlist_items';

    protected $fillable = [
        'user_id',
        'product_id',
        'product_variant_id',
        'quantity',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the wishlist.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product associated with the wishlist.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the product variant associated with the wishlist.
     */
    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    /**
     * Scope to get wishlist items for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get wishlist items with products.
     */
    public function scopeWithProducts($query)
    {
        return $query->with(['product', 'productVariant']);
    }

    /**
     * Get formatted total price for the wishlist item.
     */
    public function getFormattedTotalPriceAttribute()
    {
        $price = $this->productVariant ? $this->productVariant->price : $this->product->price;
        return '$' . number_format($price, 2);
    }

    /**
     * Get formatted unit price for the wishlist item.
     */
    public function getFormattedUnitPriceAttribute()
    {
        $price = $this->productVariant ? $this->productVariant->price : $this->product->price;
        return '$' . number_format($price, 2);
    }

    /**
     * Get the display name for the wishlist item.
     */
    public function getDisplayNameAttribute()
    {
        if ($this->productVariant) {
            return $this->product->name . ' - ' . $this->productVariant->name;
        }
        return $this->product->name;
    }

    /**
     * Get the main image for the wishlist item.
     */
    public function getImageAttribute()
    {
        if ($this->product->image) {
            return asset('storage/' . $this->product->image);
        }
        
        // Default placeholder
        return asset('images/placeholder.jpg');
    }

    /**
     * Check if the wishlist item is in stock.
     */
    public function isInStock(): bool
    {
        if ($this->productVariant) {
            return !$this->productVariant->track_quantity || $this->productVariant->quantity > 0;
        }
        return !$this->product->track_quantity || $this->product->quantity > 0;
    }

    /**
     * Accessor for is_in_stock attribute (used in views).
     */
    public function getIsInStockAttribute(): bool
    {
        return $this->isInStock();
    }
}
