<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\ClinicDoctor;
use App\Models\DoctorAssistant;
use App\Repositories\Contracts\AppointmentRepositoryInterface;
use Illuminate\Support\Facades\DB;
use DomainException;
use Carbon\Carbon;
use App\Repositories\Contracts\DoctorAvailabilityRepositoryInterface;

class AppointmentService
{
    public function __construct(
        protected AppointmentRepositoryInterface $appointments,
        protected DoctorAvailabilityRepositoryInterface $availability
    ) {
    }

    /**
     * Create appointment
     */
    public function create(array $data, $actor): Appointment
    {
        return DB::transaction(function () use ($data, $actor) {

            $this->authorize($actor, $data['doctor_id'], $data['clinic_id']);

            $this->validateDoctorAtClinic(
                $data['doctor_id'],
                $data['clinic_id']
            );

            $this->validateWithinAvailability(
                $data['doctor_id'],
                $data['clinic_id'],
                $data['appointment_time']
            );

            if (
                $this->appointments->hasConflict(
                    $data['doctor_id'],
                    $data['clinic_id'],
                    $data['appointment_time']
                )
            ) {
                throw new DomainException('This slot is already booked.');
            }

            return $this->appointments->create([
                'clinic_id' => $data['clinic_id'],
                'doctor_id' => $data['doctor_id'],
                'patient_id' => $data['patient_id'] ?? null,
                'appointment_time' => $data['appointment_time'],
                'managed_by' => $actor->id,
                'managed_by_role' => $this->resolveRole($actor),
                'status' => 'scheduled',
            ]);
        });
    }

    /**
     * Reschedule appointment
     */
    public function reschedule(
        Appointment $appointment,
        string $newTime,
        $actor
    ): bool {
        $this->authorize(
            $actor,
            $appointment->doctor_id,
            $appointment->clinic_id
        );

        if (
            $this->appointments->hasConflict(
                $appointment->doctor_id,
                $appointment->clinic_id,
                $newTime
            )
        ) {
            throw new DomainException('This slot is already booked.');
        }

        return $this->appointments->update($appointment, [
            'appointment_time' => $newTime,
        ]);
    }

    /**
     * Cancel appointment
     */
    public function cancel(Appointment $appointment, $actor): bool
    {
        $this->authorize(
            $actor,
            $appointment->doctor_id,
            $appointment->clinic_id
        );

        return $this->appointments->update($appointment, [
            'status' => 'cancelled',
        ]);
    }

    /**
     * -----------------------
     * INTERNAL HELPERS
     * -----------------------
     */

    private function authorize($actor, int $doctorId, int $clinicId): void
    {
        // Doctor managing own appointments
        if ($actor->hasRole('doctor') && $actor->id === $doctorId) {
            return;
        }

        // PA managing doctor's appointments
        if (
            $actor->hasRole('pa') &&
            DoctorAssistant::where('doctor_id', $doctorId)
                ->where('assistant_id', $actor->id)
                ->exists()
        ) {
            return;
        }

        // Clinic receptionist managing clinic appointments
        if (
            $actor->hasRole('receptionist') &&
            $actor->clinicStaff()
                ->where('clinic_id', $clinicId)
                ->exists()
        ) {
            return;
        }

        throw new DomainException('You are not authorized to manage this appointment.');
    }

    private function validateDoctorAtClinic(
        int $doctorId,
        int $clinicId
    ): void {
        if (
            !ClinicDoctor::where([
                'doctor_id' => $doctorId,
                'clinic_id' => $clinicId,
            ])->exists()
        ) {
            throw new DomainException('Doctor is not available at this clinic.');
        }
    }

    private function resolveRole($actor): string
    {
        if ($actor->hasRole('doctor')) {
            return 'doctor';
        }

        if ($actor->hasRole('pa')) {
            return 'pa';
        }

        if ($actor->hasRole('receptionist')) {
            return 'receptionist';
        }

        return 'unknown';
    }

    private function validateWithinAvailability(
        int $doctorId,
        int $clinicId,
        string $appointmentTime
    ): void {
        $schedule = $this->availability
            ->getForDoctorAtClinic($doctorId, $clinicId);

        if (!$schedule) {
            throw new DomainException(
                'Doctor availability not defined for this location.'
            );
        }

        $time = Carbon::parse($appointmentTime);
        $day = strtolower($time->format('D')); // mon, tue...
        $hour = $time->format('H:i');

        if (!isset($schedule[$day])) {
            throw new DomainException('Doctor not available on this day.');
        }

        foreach ($schedule[$day] as $slot) {
            if ($hour >= $slot['from'] && $hour < $slot['to']) {
                return;
            }
        }

        throw new DomainException(
            'Appointment time is outside doctor availability.'
        );
    }

}
