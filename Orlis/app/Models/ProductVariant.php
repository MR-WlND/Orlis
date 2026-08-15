<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'color',
        'size',
        'stock_qty',
        'price_override',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
