<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'status',
    ];

    public function posts()
    {
        return $this->hasMany(ForumPost::class, 'category_id');
    }
}
