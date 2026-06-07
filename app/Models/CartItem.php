<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'product_variant_id',
        'quantity',
        'price',
        'total',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function getFormattedPriceAttribute()
    {
        $currency = \App\Models\Setting::get('currency', 'USD');
        return \App\Helpers\CurrencyHelper::format($this->price, $currency);
    }

    public function getFormattedTotalAttribute()
    {
        $currency = \App\Models\Setting::get('currency', 'USD');
        return \App\Helpers\CurrencyHelper::format($this->total, $currency);
    }

    public function updateTotal()
    {
        $this->total = $this->quantity * $this->price;
        $this->save();
    }
}
