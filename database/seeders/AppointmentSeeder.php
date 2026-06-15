<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Patient;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patient = Patient::first();
        $doctor = Doctor::first();

        if (!$patient || !$doctor) {
            return;
        }

        // 1. Seed Doctor Schedules
        DoctorSchedule::create([
            'doctor_id' => $doctor->id,
            'day_of_week' => 'Lunes',
            'hour' => '08:00:00',
            'slots' => ['08:00 - 08:15', '08:15 - 08:30'],
        ]);

        DoctorSchedule::create([
            'doctor_id' => $doctor->id,
            'day_of_week' => 'Martes',
            'hour' => '08:00:00',
            'slots' => ['08:00 - 08:15', '08:15 - 08:30'],
        ]);

        DoctorSchedule::create([
            'doctor_id' => $doctor->id,
            'day_of_week' => 'Miércoles',
            'hour' => '08:00:00',
            'slots' => ['08:00 - 08:15', '08:15 - 08:30'],
        ]);

        // 2. Seed Past Appointments and Consultations
        $app1 = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'date' => '2026-05-10',
            'start_time' => '09:00:00',
            'end_time' => '09:30:00',
            'reason' => 'Control de presión arterial',
            'status' => 2, // Atendida
        ]);

        Consultation::create([
            'appointment_id' => $app1->id,
            'diagnosis' => "Hipertensión arterial controlada.",
            'treatment' => "Continuar con Enalapril 10mg cada 12 horas. Realizar ejercicio aeróbico ligero 30 min al día.",
            'notes' => "Paciente refiere sentirse bien, sin cefaleas ni mareos. Presión arterial registrada: 120/80 mmHg.",
            'medicines' => [
                ['name' => 'Enalapril 10mg', 'dose' => '1 tableta cada 12 horas por 30 días'],
                ['name' => 'Aspirina 100mg', 'dose' => '1 tableta diaria con el almuerzo'],
            ],
        ]);

        $app2 = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'date' => '2026-06-01',
            'start_time' => '10:00:00',
            'end_time' => '10:30:00',
            'reason' => 'Chequeo cardiológico general',
            'status' => 2, // Atendida
        ]);

        Consultation::create([
            'appointment_id' => $app2->id,
            'diagnosis' => "Ritmo sinusal normal. Cansancio leve asociado a estrés laboral.",
            'treatment' => "Tomar suplemento de magnesio. Descanso adecuado de al menos 7 horas.",
            'notes' => "Electrocardiograma de control sin alteraciones significativas. Ruidos cardíacos rítmicos.",
            'medicines' => [
                ['name' => 'Cloruro de Magnesio 500mg', 'dose' => '1 cápsula por la noche antes de dormir'],
                ['name' => 'Complejo B', 'dose' => '1 tableta por la mañana con el desayuno'],
            ],
        ]);

        // 3. Seed a Pending Appointment for demonstration
        Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'date' => date('Y-m-d', strtotime('+2 days')),
            'start_time' => '11:00:00',
            'end_time' => '11:30:00',
            'reason' => 'Valoración preoperatoria',
            'status' => 1, // Pendiente
        ]);
    }
}
