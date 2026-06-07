<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'subtotal',
        'tax',
        'shipping',
        'discount',
        'total',
        'coupon_code',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function calculateTotals()
    {
        $this->subtotal = $this->cartItems()->sum('total');
        $this->total = $this->subtotal + $this->tax + $this->shipping - $this->discount;
        $this->save();
    }

    public function addItem($productId, $quantity = 1, $variantId = null)
    {
        $existingItem = $this->cartItems()
            ->where('product_id', $productId)
            ->when($variantId, function ($query, $variantId) {
                return $query->where('product_variant_id', $variantId);
            })
            ->first();

        if ($existingItem) {
            $existingItem->quantity += $quantity;
            $existingItem->total = $existingItem->quantity * $existingItem->price;
            $existingItem->save();
        } else {
            $product = Product::findOrFail($productId);
            $price = $variantId ? ProductVariant::findOrFail($variantId)->price : $product->price;

            $this->cartItems()->create([
                'product_id' => $productId,
                'product_variant_id' => $variantId,
                'quantity' => $quantity,
                'price' => $price,
                'total' => $price * $quantity,
            ]);
        }

        $this->calculateTotals();
    }

    public function removeItem($itemId)
    {
        $this->cartItems()->findOrFail($itemId)->delete();
        $this->calculateTotals();
    }

    public function clear()
    {
        $this->cartItems()->delete();
        $this->subtotal = 0;
        $this->tax = 0;
        $this->shipping = 0;
        $this->discount = 0;
        $this->total = 0;
        $this->save();
    }
}
