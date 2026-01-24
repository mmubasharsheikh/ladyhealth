<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumComment extends Model
{
    protected $fillable = [
        'post_id',
        'user_id',
        'body',
        'is_doctor_reply',
        'status',
    ];

    public function post()
    {
        return $this->belongsTo(ForumPost::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

