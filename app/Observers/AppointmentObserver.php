<?php

namespace App\Observers;

use App\Models\Appointment;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AppointmentObserver
{
    protected WhatsAppService $whatsAppService;

    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    /**
     * Handle the Appointment "created" event.
     */
    public function created(Appointment $appointment): void
    {
        try {
            $appointment->loadMissing(['patient.user', 'doctor.user']);

            $patient = $appointment->patient;
            if (!$patient || !$patient->user || empty($patient->user->phone)) {
                Log::warning("AppointmentObserver: Cannot send WhatsApp confirmation. Patient or phone number is missing for appointment ID {$appointment->id}.");
                return;
            }

            $patientName = $patient->user->name;
            $doctorName = $appointment->doctor->user->name ?? 'Médico';
            $dateFormatted = Carbon::parse($appointment->date)->format('d/m/Y');
            $timeFormatted = Carbon::parse($appointment->start_time)->format('H:i');
            $reason = $appointment->reason;
            $phone = $patient->user->phone;

            $message = "Hola, *{$patientName}*. Tu cita médica con el Dr(a). *{$doctorName}* ha sido programada con éxito para el día *{$dateFormatted}* a las *{$timeFormatted}*.\nMotivo: {$reason}.\n¡Te esperamos en Healthify!";

            $this->whatsAppService->sendMessage($phone, $message);
        } catch (\Exception $e) {
            Log::error("AppointmentObserver Error: " . $e->getMessage());
        }
    }
}
