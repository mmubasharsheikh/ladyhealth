<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicDoctorContract extends Model
{
    protected $fillable = [
        'clinic_id',
        'doctor_id',

        'appointment_billing_type',
        'appointment_billing_value',

        'subscription_type',
        'subscription_amount',

        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at'   => 'date',
        'is_active' => 'boolean',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
