<?php

use App\Models\DoctorLocationAvailability;

class DoctorAvailabilityRepository
    implements DoctorAvailabilityRepositoryInterface
{
    public function getForDoctorAtClinic(
        int $doctorId,
        int $clinicId
    ): ?array {
        return DoctorLocationAvailability::where([
            'doctor_id' => $doctorId,
            'clinic_id' => $clinicId,
            'is_active' => true,
        ])->value('weekly_schedule');
    }
}
