<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Appointment;
use App\Models\Consultation;
use Illuminate\Support\Facades\DB;

class ConsultationManager extends Component
{
    public Appointment $appointment;
    
    // Form fields
    public string $diagnosis = '';
    public string $treatment = '';
    public string $notes = '';
    public array $medicines = [
        ['name' => '', 'dose' => ''],
        ['name' => '', 'dose' => ''],
    ]; // Default 2 rows

    // UI state
    public string $activeTab = 'consulta'; // consulta | receta
    public bool $showPastConsultationsModal = false;
    public bool $showMedicalHistoryModal = false;
    public $pastConsultations = [];

    public function openMedicalHistory()
    {
        $this->showMedicalHistoryModal = true;
    }

    public function closeMedicalHistory()
    {
        $this->showMedicalHistoryModal = false;
    }

    protected function rules()
    {
        return [
            'diagnosis' => 'required|string',
            'treatment' => 'required|string',
            'notes' => 'nullable|string',
            'medicines' => 'required|array|min:2',
            'medicines.*.name' => 'required|string|min:1',
            'medicines.*.dose' => 'required|string|min:1',
        ];
    }

    protected function messages()
    {
        return [
            'diagnosis.required' => 'El diagnóstico es obligatorio.',
            'treatment.required' => 'El tratamiento es obligatorio.',
            'medicines.min' => 'Debe agregar al menos dos medicamentos a la receta.',
            'medicines.*.name.required' => 'El nombre del medicamento es obligatorio.',
            'medicines.*.dose.required' => 'La dosis o indicaciones son obligatorias.',
        ];
    }

    public function mount(Appointment $appointment)
    {
        $this->appointment = $appointment;
        
        // Load past consultations for the patient
        $this->loadPastConsultations();
    }

    public function loadPastConsultations()
    {
        $this->pastConsultations = Consultation::whereHas('appointment', function ($query) {
            $query->where('patient_id', $this->appointment->patient_id)
                  ->where('id', '!=', $this->appointment->id);
        })
        ->with('appointment.doctor.user')
        ->orderBy('created_at', 'desc')
        ->get();
    }

    public function addMedicine()
    {
        $this->medicines[] = ['name' => '', 'dose' => ''];
    }

    public function removeMedicine(int $index)
    {
        unset($this->medicines[$index]);
        $this->medicines = array_values($this->medicines);
    }

    public function openPastConsultations()
    {
        $this->loadPastConsultations();
        $this->showPastConsultationsModal = true;
    }

    public function closePastConsultations()
    {
        $this->showPastConsultationsModal = false;
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            // Save consultation
            Consultation::create([
                'appointment_id' => $this->appointment->id,
                'diagnosis' => $this->diagnosis,
                'treatment' => $this->treatment,
                'notes' => $this->notes,
                'medicines' => $this->medicines,
            ]);

            // Update appointment status to "Atendida"
            $this->appointment->update([
                'status' => 2, // Atendida
            ]);
        });

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Consulta guardada',
            'text' => 'La cita ha sido atendida y la consulta médica se ha registrado exitosamente.',
        ]);

        return redirect()->route('admin.appointments.index');
    }

    public function render()
    {
        return view('livewire.admin.consultation-manager');
    }
}
