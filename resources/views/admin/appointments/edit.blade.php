<x-admin-layout title="Editar Cita" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Citas médicas',
        'href' => route('admin.appointments.index'),
    ],
    [
        'name' => 'Editar Cita',
    ],
]">
    <form action="{{ route('admin.appointments.update', $appointment) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-6 max-w-4xl mx-auto">
            <!-- Header Card -->
            <x-wire-card class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Editar Cita Médica</h2>
                        <p class="text-sm text-gray-500 mt-1">Actualice los detalles de la consulta médica agendada.</p>
                    </div>
                    <div class="flex space-x-3">
                        <x-wire-button outline gray href="{{ route('admin.appointments.index') }}">Cancelar</x-wire-button>
                        <x-wire-button type="submit" primary icon="check" label="Guardar cambios" class="shadow-sm" />
                    </div>
                </div>
            </x-wire-card>

            <!-- Form Card -->
            <x-wire-card>
                <div class="space-y-6">
                    <!-- General validation error alert -->
                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fa-solid fa-circle-exclamation text-red-500 text-lg"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-bold text-red-800">Por favor, corrija los siguientes errores:</h3>
                                    <ul class="mt-2 list-disc list-inside text-sm text-red-700 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Patient Select -->
                        <div>
                            <x-wire-native-select label="Paciente *" name="patient_id">
                                <option value="">Seleccione un paciente</option>
                                @foreach ($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id', $appointment->patient_id) == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->user->name }}
                                    </option>
                                @endforeach
                            </x-wire-native-select>
                        </div>

                        <!-- Doctor Select -->
                        <div>
                            <x-wire-native-select label="Médico *" name="doctor_id">
                                <option value="">Seleccione un médico</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('doctor_id', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->user->name }} ({{ $doctor->speciality->name ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </x-wire-native-select>
                        </div>
                    </div>

                    <!-- Date input -->
                    <div>
                        <x-wire-input type="date" label="Fecha *" name="date" :value="old('date', $appointment->date)" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Start Time input -->
                        <div>
                            <x-wire-input type="time" label="Hora de inicio *" name="start_time" :value="old('start_time', \Carbon\Carbon::parse($appointment->start_time)->format('H:i'))" />
                        </div>

                        <!-- End Time input -->
                        <div>
                            <x-wire-input type="time" label="Hora de fin *" name="end_time" :value="old('end_time', \Carbon\Carbon::parse($appointment->end_time)->format('H:i'))" />
                        </div>
                    </div>

                    <!-- Reason textarea -->
                    <div>
                        <x-wire-textarea label="Motivo de la consulta *" name="reason" placeholder="Escriba aquí el motivo detallado de la consulta..." :value="old('reason', $appointment->reason)" rows="4" />
                    </div>
                </div>
            </x-wire-card>
        </div>
    </form>
</x-admin-layout>
