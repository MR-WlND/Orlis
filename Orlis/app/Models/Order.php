<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUSES = [
        'pending' => 'Chờ xác nhận',
        'confirmed' => 'Đã xác nhận',
        'processing' => 'Đang xử lý',
        'shipping' => 'Đang giao hàng',
        'delivered' => 'Đã giao hàng',
        'cancelled' => 'Đã hủy',
        'refunded' => 'Đã hoàn tiền',
    ];

    public const STATUS_COLORS = [
        'pending' => '#faad14',
        'confirmed' => '#1890ff',
        'processing' => '#722ed1',
        'shipping' => '#13c2c2',
        'delivered' => '#52c41a',
        'cancelled' => '#f5222d',
        'refunded' => '#eb2f96',
    ];

    protected $fillable = [
        'order_code',
        'user_id',
        'coupon_id',
        'shipping_address_snapshot',
        'recipient_name',
        'recipient_phone',
        'subtotal',
        'shipping_method_id',
        'shipping_fee',
        'discount_amount',
        'grand_total',
        'deposit_amount',
        'remaining_amount',
        'gift_note',
        'order_status',
        'payment_method',
        'ready_for_delivery_at',
    ];

    protected function casts(): array
    {
        return [
            'shipping_address_snapshot' => 'array',
            'subtotal' => 'decimal:2',
            'shipping_fee' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'deposit_amount' => 'decimal:2',
            'remaining_amount' => 'decimal:2',
            'ready_for_delivery_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function shippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(OrderStatusLog::class);
    }

    public function scopePending($query)
    {
        return $query->where('order_status', 'pending');
    }

    public function scopeDelivered($query)
    {
        return $query->where('order_status', 'delivered');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->order_status] ?? $this->order_status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->order_status] ?? '#999';
    }
}
