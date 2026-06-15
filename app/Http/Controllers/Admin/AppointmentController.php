<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.appointments.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with('user')->get();
        return view('admin.appointments.create', compact('patients', 'doctors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'reason' => 'required|string',
        ], [
            'patient_id.required' => 'El paciente es requerido.',
            'patient_id.exists' => 'El paciente seleccionado no es válido.',
            'doctor_id.required' => 'El médico es requerido.',
            'doctor_id.exists' => 'El médico seleccionado no es válido.',
            'date.required' => 'La fecha es requerida.',
            'date.date' => 'La fecha no es válida.',
            'date.after_or_equal' => 'La fecha no puede ser en el pasado.',
            'start_time.required' => 'La hora de inicio es requerida.',
            'end_time.required' => 'La hora de fin es requerida.',
            'end_time.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'reason.required' => 'El motivo de la consulta es requerido.',
        ]);

        Appointment::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'reason' => $request->reason,
            'status' => 1, // 1 = Pendiente
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Cita registrada',
            'text' => 'La cita médica ha sido registrada exitosamente.',
        ]);

        return redirect()->route('admin.appointments.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        return redirect()->route('admin.appointments.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with('user')->get();
        return view('admin.appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'reason' => 'required|string',
        ], [
            'patient_id.required' => 'El paciente es requerido.',
            'patient_id.exists' => 'El paciente seleccionado no es válido.',
            'doctor_id.required' => 'El médico es requerido.',
            'doctor_id.exists' => 'El médico seleccionado no es válido.',
            'date.required' => 'La fecha es requerida.',
            'date.date' => 'La fecha no es válida.',
            'date.after_or_equal' => 'La fecha no puede ser en el pasado.',
            'start_time.required' => 'La hora de inicio es requerida.',
            'end_time.required' => 'La hora de fin es requerida.',
            'end_time.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
            'reason.required' => 'El motivo de la consulta es requerido.',
        ]);

        $appointment->update([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'reason' => $request->reason,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Cita actualizada',
            'text' => 'La cita médica ha sido actualizada exitosamente.',
        ]);

        return redirect()->route('admin.appointments.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Cita eliminada',
            'text' => 'La cita médica ha sido eliminada exitosamente.',
        ]);

        return redirect()->route('admin.appointments.index');
    }

    /**
     * Show the form to attend/consult the appointment.
     */
    public function consultation(Appointment $appointment)
    {
        return view('admin.appointments.consultation', compact('appointment'));
    }
}
