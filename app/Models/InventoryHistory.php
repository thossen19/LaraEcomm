<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'previous_quantity',
        'new_quantity',
        'adjustment_type',
        'quantity',
        'reason',
        'notes',
        'admin_id',
    ];

    protected $casts = [
        'previous_quantity' => 'integer',
        'new_quantity' => 'integer',
        'quantity' => 'integer',
        'adjustment_type' => 'string',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByAdjustmentType($query, $type)
    {
        return $query->where('adjustment_type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function getFormattedAdjustmentTypeAttribute()
    {
        return ucfirst($this->adjustment_type);
    }

    public function getQuantityChangeAttribute()
    {
        return $this->new_quantity - $this->previous_quantity;
    }

    public function getQuantityChangeColorAttribute()
    {
        $change = $this->getQuantityChangeAttribute();
        
        if ($change > 0) {
            return 'text-green-600';
        } elseif ($change < 0) {
            return 'text-red-600';
        }
        
        return 'text-gray-600';
    }
}
