<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'actor_id',
        'actor_role',
        'action',
        'target_type',
        'target_id',
        'meta',
    ];

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}

