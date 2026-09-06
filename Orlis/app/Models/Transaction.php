<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'order_id',
        'type',
        'amount',
        'currency',
        'exchange_rate',
        'payment_method',
        'transaction_code',
        'gateway_response',
        'status',
        'notes',
    ];

    /**
     * Đơn hàng liên quan đến giao dịch.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
