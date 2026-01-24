<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorAssistant extends Model
{
    protected $fillable = [
        'doctor_id',     // users.id
        'assistant_id',  // users.id
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function assistant()
    {
        return $this->belongsTo(User::class, 'assistant_id');
    }
}
