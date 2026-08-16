<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'discount_percent',
        'discount_amount',
        'max_uses',
        'used_count',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function isValid()
    {
        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) {
            return false;
        }

        if ($this->expires_at !== null && now()->gt($this->expires_at)) {
            return false;
        }

        return true;
    }
}
