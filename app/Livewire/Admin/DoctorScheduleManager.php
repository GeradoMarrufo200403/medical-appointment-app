<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Support\Facades\DB;

class DoctorScheduleManager extends Component
{
    public Doctor $doctor;
    public array $schedules = [];
    
    public array $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
    public array $hours = [
        '08:00:00', '09:00:00', '10:00:00', '11:00:00', '12:00:00',
        '13:00:00', '14:00:00', '15:00:00', '16:00:00', '17:00:00', '18:00:00'
    ];

    public function mount(Doctor $doctor)
    {
        $this->doctor = $doctor;
        
        // Initialize state matrix
        foreach ($this->days as $day) {
            foreach ($this->hours as $hour) {
                $slots = $this->getSlotsForHour($hour);
                foreach ($slots as $slot) {
                    $this->schedules[$day][$hour][$slot] = false;
                }
            }
        }

        // Load existing schedules from database
        $existingSchedules = DoctorSchedule::where('doctor_id', $this->doctor->id)->get();
        foreach ($existingSchedules as $sched) {
            $day = $sched->day_of_week;
            $hour = $sched->hour;
            $dbSlots = $sched->slots; // automatically cast to array
            
            if (is_array($dbSlots)) {
                foreach ($dbSlots as $slot) {
                    if (isset($this->schedules[$day][$hour][$slot])) {
                        $this->schedules[$day][$hour][$slot] = true;
                    }
                }
            }
        }
    }

    public function getSlotsForHour(string $hour): array
    {
        $h = substr($hour, 0, 2);
        $nextH = str_pad((int)$h + 1, 2, '0', STR_PAD_LEFT);
        return [
            "$h:00 - $h:15",
            "$h:15 - $h:30",
            "$h:30 - $h:45",
            "$h:45 - $nextH:00"
        ];
    }

    public function isHourChecked(string $hour): bool
    {
        foreach ($this->days as $day) {
            $slots = $this->getSlotsForHour($hour);
            foreach ($slots as $slot) {
                if (!$this->schedules[$day][$hour][$slot]) {
                    return false;
                }
            }
        }
        return true;
    }

    public function toggleHour(string $hour)
    {
        $currentlyAllChecked = $this->isHourChecked($hour);
        $newValue = !$currentlyAllChecked;

        foreach ($this->days as $day) {
            $slots = $this->getSlotsForHour($hour);
            foreach ($slots as $slot) {
                $this->schedules[$day][$hour][$slot] = $newValue;
            }
        }
    }

    public function isDayHourChecked(string $day, string $hour): bool
    {
        $slots = $this->getSlotsForHour($hour);
        foreach ($slots as $slot) {
            if (!$this->schedules[$day][$hour][$slot]) {
                return false;
            }
        }
        return true;
    }

    public function toggleDayHour(string $day, string $hour)
    {
        $currentlyAllChecked = $this->isDayHourChecked($day, $hour);
        $newValue = !$currentlyAllChecked;

        $slots = $this->getSlotsForHour($hour);
        foreach ($slots as $slot) {
            $this->schedules[$day][$hour][$slot] = $newValue;
        }
    }

    public function save()
    {
        DB::transaction(function () {
            // Delete current schedules
            DoctorSchedule::where('doctor_id', $this->doctor->id)->delete();

            // Save new schedules
            foreach ($this->days as $day) {
                foreach ($this->hours as $hour) {
                    $slots = $this->getSlotsForHour($hour);
                    $selectedSlots = [];
                    foreach ($slots as $slot) {
                        if ($this->schedules[$day][$hour][$slot]) {
                            $selectedSlots[] = $slot;
                        }
                    }

                    if (!empty($selectedSlots)) {
                        DoctorSchedule::create([
                            'doctor_id' => $this->doctor->id,
                            'day_of_week' => $day,
                            'hour' => $hour,
                            'slots' => $selectedSlots,
                        ]);
                    }
                }
            }
        });

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Horario actualizado',
            'text' => 'El horario del doctor ha sido guardado exitosamente.',
        ]);

        return redirect()->route('admin.doctors.index');
    }

    public function render()
    {
        return view('livewire.admin.doctor-schedule-manager');
    }
}
