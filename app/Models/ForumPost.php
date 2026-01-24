<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumPost extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'body',
        'is_anonymous',
        'status',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(ForumCategory::class);
    }

    public function comments()
    {
        return $this->hasMany(ForumComment::class, 'post_id');
    }
}
