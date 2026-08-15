<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id',
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

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
