<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'department',
        'title',
        'slug',
        'excerpt',
        'content',
        'thumbnail',
        'status',
        'tags',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }
}
