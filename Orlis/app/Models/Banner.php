<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_path',
        'image_mobile_path',
        'link_url',
        'link_target',
        'text_color',
        'position',
        'order',
        'is_active',
        'category_ids',
        'is_global',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'category_ids' => 'array',
        'is_global' => 'boolean',
        'is_active' => 'boolean',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_time')->orWhere('start_time', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_time')->orWhere('end_time', '>=', now());
            });
    }

    public function scopePosition($query, $position)
    {
        return $query->where('position', $position);
    }
}
