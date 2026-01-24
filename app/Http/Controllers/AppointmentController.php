<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Services\AppointmentService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function __construct(
        protected AppointmentService $service
    ) {}

    public function store(Request $request)
    {
        $data = $request->validate([
            'clinic_id'        => 'required|integer',
            'doctor_id'        => 'required|integer',
            'patient_id'       => 'nullable|integer',
            'appointment_time' => 'required|date',
        ]);

        $appointment = $this->service->create($data, $request->user());

        return response()->json($appointment, 201);
    }

    public function reschedule(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'appointment_time' => 'required|date',
        ]);

        $this->service->reschedule(
            $appointment,
            $data['appointment_time'],
            $request->user()
        );

        return response()->json(['message' => 'Appointment rescheduled']);
    }

    public function cancel(Request $request, Appointment $appointment)
    {
        $this->service->cancel($appointment, $request->user());

        return response()->json(['message' => 'Appointment cancelled']);
    }
}
