<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    protected $fillable = [
        'name',
        'type',     // private | clinic | hospital
        'city',
        'status',
    ];

    public function doctors()
    {
        return $this->hasMany(ClinicDoctor::class);
    }

    public function staff()
    {
        return $this->hasMany(ClinicStaff::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
