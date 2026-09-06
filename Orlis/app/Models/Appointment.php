<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_code',
        'user_id',
        'store_id',
        'staff_id',
        'appointment_date',
        'time_slot',
        'appointment_datetime',
        'service_type',
        'status',
        'transfer_status',
        'note',
        'cancel_reason',
    ];

    protected $casts = [
        'appointment_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'staff_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(AppointmentItem::class);
    }
}
