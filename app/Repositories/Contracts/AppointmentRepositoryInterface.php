<?php

namespace App\Repositories\Contracts;

use App\Models\Appointment;

interface AppointmentRepositoryInterface
{
    public function create(array $data): Appointment;

    public function update(Appointment $appointment, array $data): bool;

    public function findById(int $id): ?Appointment;

    public function hasConflict(
        int $doctorId,
        int $clinicId,
        string $appointmentTime
    ): bool;
}
