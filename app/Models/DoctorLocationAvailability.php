<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorLocationAvailability extends Model
{
    protected $fillable = [
        'doctor_id',
        'clinic_id',
        'weekly_schedule',
        'is_active',
    ];

    protected $casts = [
        'weekly_schedule' => 'array',
        'is_active'       => 'boolean',
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
