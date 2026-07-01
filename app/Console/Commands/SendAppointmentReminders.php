<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Services\WhatsAppService;
use Carbon\Carbon;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send WhatsApp reminders to patients with appointments scheduled for tomorrow.';

    /**
     * Execute the console command.
     */
    public function handle(WhatsAppService $whatsAppService)
    {
        $tomorrow = Carbon::tomorrow()->toDateString();
        $this->info("Buscando citas programadas para el día de mañana ({$tomorrow})...");

        // Filtrar citas que son exactamente mañana y tienen estatus de Pendiente (1)
        $appointments = Appointment::with(['patient.user', 'doctor.user'])
            ->where('date', $tomorrow)
            ->where('status', 1)
            ->get();

        if ($appointments->isEmpty()) {
            $this->info("No se encontraron citas para mañana.");
            return 0;
        }

        $this->info("Se encontraron {$appointments->count()} cita(s) pendiente(s). Enviando recordatorios...");

        $sentCount = 0;
        foreach ($appointments as $appointment) {
            $patient = $appointment->patient;
            if (!$patient || !$patient->user || empty($patient->user->phone)) {
                $this->warn("Cita ID {$appointment->id} no tiene un paciente o número de teléfono válido.");
                continue;
            }

            $patientName = $patient->user->name;
            $doctorName = $appointment->doctor->user->name ?? 'Médico';
            $timeFormatted = Carbon::parse($appointment->start_time)->format('H:i');
            $phone = $patient->user->phone;

            $message = "Recordatorio: Hola, *{$patientName}*. Recuerda que tienes una cita médica programada con el Dr(a). *{$doctorName}* para mañana *{$tomorrow}* a las *{$timeFormatted}*.\nPor favor, responde a este mensaje si deseas confirmar o reagendar. ¡Te esperamos!";

            $success = $whatsAppService->sendMessage($phone, $message);

            if ($success) {
                $this->line("Recordatorio enviado a {$patientName} ({$phone}) para su cita a las {$timeFormatted}.");
                $sentCount++;
            } else {
                $this->error("Error al enviar recordatorio a {$patientName} ({$phone}).");
            }
        }

        $this->info("Proceso completado. Recordatorios enviados con éxito: {$sentCount}");
        return 0;
    }
}
