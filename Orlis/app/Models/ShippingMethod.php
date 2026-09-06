<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingMethod extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'cost',
        'is_active',
        'min_order_amount_for_free_shipping',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'cost' => 'decimal:2',
        'min_order_amount_for_free_shipping' => 'decimal:2',
    ];
}
