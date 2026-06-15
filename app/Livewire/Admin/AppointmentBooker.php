<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Speciality;
use App\Models\DoctorSchedule;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AppointmentBooker extends Component
{
    // Search Filters
    public $date;
    public $hour_range = '';
    public $speciality_id = '';

    // Data lists
    public $specialities = [];
    public $patients = [];
    public $searchResults = [];
    public $hasSearched = false;

    // Selected Slot Details
    public $selectedDoctorId = null;
    public $selectedDoctorName = '';
    public $selectedSpecialityName = '';
    public $selectedSlot = '';
    public $selectedSlotStart = '';
    public $selectedSlotEnd = '';

    // Appointment Form
    public $patientId = '';
    public $reason = '';

    public array $hours = [
        '08:00:00' => '08:00:00 - 09:00:00',
        '09:00:00' => '09:00:00 - 10:00:00',
        '10:00:00' => '10:00:00 - 11:00:00',
        '11:00:00' => '11:00:00 - 12:00:00',
        '12:00:00' => '12:00:00 - 13:00:00',
        '13:00:00' => '13:00:00 - 14:00:00',
        '14:00:00' => '14:00:00 - 15:00:00',
        '15:00:00' => '15:00:00 - 16:00:00',
        '16:00:00' => '16:00:00 - 17:00:00',
        '17:00:00' => '17:00:00 - 18:00:00',
        '18:00:00' => '18:00:00 - 19:00:00',
    ];

    public function mount()
    {
        // Set default date to today in the format YYYY-MM-DD
        $this->date = Carbon::today()->format('Y-m-d');
        
        // Load data
        $this->specialities = Speciality::orderBy('name')->get();
        $this->patients = Patient::with('user')->get()->sortBy('user.name');
    }

    public function search()
    {
        $this->validate([
            'date' => 'required|date|after_or_equal:today',
        ], [
            'date.required' => 'La fecha es requerida.',
            'date.date' => 'La fecha no es válida.',
            'date.after_or_equal' => 'La fecha no puede ser en el pasado.',
        ]);

        $this->hasSearched = true;
        
        // Reset selected slot if we perform a new search
        $this->resetSelection();

        // 1. Get Spanish Day of Week
        $dayOfWeekMap = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo'
        ];
        $dayNumber = Carbon::parse($this->date)->dayOfWeekIso;
        $spanishDay = $dayOfWeekMap[$dayNumber] ?? '';

        // 2. Query Doctor Schedules
        $query = DoctorSchedule::with(['doctor.user', 'doctor.speciality'])
            ->where('day_of_week', $spanishDay);

        if ($this->speciality_id) {
            $query->whereHas('doctor', function ($q) {
                $q->where('speciality_id', $this->speciality_id);
            });
        }

        if ($this->hour_range) {
            $query->where('hour', $this->hour_range);
        }

        $schedules = $query->get();

        // 3. Process and Filter Booked Slots
        $results = [];
        foreach ($schedules as $sched) {
            $doctor = $sched->doctor;
            if (!$doctor || !$doctor->user) {
                continue;
            }

            // Fetch already active appointments for this doctor on selected date
            $bookedAppointments = Appointment::where('doctor_id', $doctor->id)
                ->where('date', $this->date)
                ->whereIn('status', [1, 2]) // 1 = Pendiente, 2 = Atendida
                ->get();

            $availableSlots = [];
            if (is_array($sched->slots)) {
                foreach ($sched->slots as $slot) {
                    $parts = explode(' - ', $slot);
                    if (count($parts) < 2) continue;
                    $start = $parts[0] . ':00';
                    $end = $parts[1] . ':00';

                    // Check if there is an overlapping booked appointment starting at the same time
                    $isBooked = $bookedAppointments->contains(function ($app) use ($start) {
                        return Carbon::parse($app->start_time)->format('H:i:s') === $start;
                    });

                    if (!$isBooked) {
                        $availableSlots[] = [
                            'range' => $slot,
                            'start' => $start,
                            'end' => $end,
                            'display' => $start,
                        ];
                    }
                }
            }

            if (empty($availableSlots)) {
                continue;
            }

            $docId = $doctor->id;
            if (isset($results[$docId])) {
                $results[$docId]['slots'] = array_merge($results[$docId]['slots'], $availableSlots);
            } else {
                $nameParts = explode(' ', $doctor->user->name);
                $initials = collect($nameParts)
                    ->map(fn($n) => mb_substr($n, 0, 1))
                    ->take(2)
                    ->join('');

                $results[$docId] = [
                    'id' => $doctor->id,
                    'name' => $doctor->user->name,
                    'speciality' => $doctor->speciality->name ?? 'General',
                    'initials' => strtoupper($initials),
                    'slots' => $availableSlots,
                ];
            }
        }

        // Sort slots chronologically for each doctor
        foreach ($results as &$res) {
            usort($res['slots'], function ($a, $b) {
                return strcmp($a['start'], $b['start']);
            });
        }

        $this->searchResults = array_values($results);
    }

    public function selectSlot($doctorId, $doctorName, $specialityName, $slotRange, $start, $end)
    {
        $this->selectedDoctorId = $doctorId;
        $this->selectedDoctorName = $doctorName;
        $this->selectedSpecialityName = $specialityName;
        $this->selectedSlot = $slotRange;
        $this->selectedSlotStart = $start;
        $this->selectedSlotEnd = $end;
    }

    public function resetSelection()
    {
        $this->selectedDoctorId = null;
        $this->selectedDoctorName = '';
        $this->selectedSpecialityName = '';
        $this->selectedSlot = '';
        $this->selectedSlotStart = '';
        $this->selectedSlotEnd = '';
    }

    public function save()
    {
        $this->validate([
            'date' => 'required|date|after_or_equal:today',
            'selectedDoctorId' => 'required',
            'selectedSlot' => 'required',
            'patientId' => 'required|exists:patients,id',
            'reason' => 'required|string|min:3',
        ], [
            'date.required' => 'La fecha es requerida.',
            'date.after_or_equal' => 'La fecha no puede ser en el pasado.',
            'selectedDoctorId.required' => 'Debe seleccionar un médico y un horario disponible.',
            'selectedSlot.required' => 'Debe seleccionar un horario disponible.',
            'patientId.required' => 'Debe seleccionar un paciente.',
            'patientId.exists' => 'El paciente seleccionado no es válido.',
            'reason.required' => 'El motivo de la cita es requerido.',
            'reason.min' => 'El motivo debe tener al menos 3 caracteres.',
        ]);

        // Extra check to prevent double booking in concurrent requests
        $alreadyBooked = Appointment::where('doctor_id', $this->selectedDoctorId)
            ->where('date', $this->date)
            ->where('start_time', $this->selectedSlotStart)
            ->whereIn('status', [1, 2])
            ->exists();

        if ($alreadyBooked) {
            $this->addError('selectedSlot', 'Lo sentimos, este horario acaba de ser reservado. Por favor busque otra disponibilidad.');
            return;
        }

        // Save appointment
        Appointment::create([
            'patient_id' => $this->patientId,
            'doctor_id' => $this->selectedDoctorId,
            'date' => $this->date,
            'start_time' => $this->selectedSlotStart,
            'end_time' => $this->selectedSlotEnd,
            'duration' => 15, // Default is 15 minutes
            'reason' => $this->reason,
            'status' => 1, // 1 = Pendiente
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Cita registrada',
            'text' => 'La cita médica ha sido agendada exitosamente.',
        ]);

        return redirect()->route('admin.appointments.index');
    }

    public function render()
    {
        return view('livewire.admin.appointment-booker');
    }
}
