<?php

namespace App\Repositories\Contracts;

interface DoctorAvailabilityRepositoryInterface
{
    public function getForDoctorAtClinic(
        int $doctorId,
        int $clinicId
    ): ?array;
}
