<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreInventory extends Model
{
    protected $table = 'store_inventory';

    protected $fillable = [
        'store_id',
        'variant_id',
        'stock_qty',
        'reserved_qty',
    ];

    protected function casts(): array
    {
        return [
            'stock_qty' => 'integer',
            'reserved_qty' => 'integer',
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function getAvailableQtyAttribute(): int
    {
        return max(0, $this->stock_qty - $this->reserved_qty);
    }
}
