<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'color',
        'size',
        'stock_qty',
        'price_override',
        'attributes',
    ];

    protected function casts(): array
    {
        return [
            'attributes' => 'array',
            'price_override' => 'decimal:2',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'variant_id');
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class, 'variant_id');
    }

    public function inventory(): HasMany
    {
        return $this->hasMany(StoreInventory::class, 'variant_id');
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class, 'variant_id');
    }

    /**
     * Giá hiệu dụng: dùng price_override nếu có, fallback về giá gốc của product.
     */
    public function getEffectivePriceAttribute(): float
    {
        return (float) ($this->price_override ?? $this->product?->sale_price ?? $this->product?->price ?? 0);
    }

    /**
     * Tên hiển thị biến thể (ví dụ: "50ml – Hồng").
     */
    public function getDisplayNameAttribute(): string
    {
        $parts = [];
        if ($this->attributes) {
            foreach ($this->attributes as $key => $value) {
                $parts[] = $value;
            }
        }

        return implode(' – ', $parts) ?: $this->sku;
    }
}
