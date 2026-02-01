<?php

namespace App\Repositories\Eloquent;

use App\Models\Appointment;
use App\Repositories\Contracts\AppointmentRepositoryInterface;

class AppointmentRepository implements AppointmentRepositoryInterface
{
    public function create(array $data): Appointment
    {
        return Appointment::create($data);
    }

    public function update(Appointment $appointment, array $data): bool
    {
        return $appointment->update($data);
    }

    public function findById(int $id): ?Appointment
    {
        return Appointment::find($id);
    }

    public function hasConflict(
        int $doctorId,
        int $clinicId,
        string $appointmentTime
    ): bool {
        return Appointment::where('doctor_id', $doctorId)
            ->where('clinic_id', $clinicId)
            ->where('appointment_time', $appointmentTime)
            ->exists();
    }
}
