<x-admin-layout title="Doctores" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Doctores',
        'href' => route('admin.doctors.index'),
    ],
    [
        'name' => 'Editar',
    ],
]">
    <form action="{{ route('admin.doctors.update', $doctor) }}" method="POST">
        @csrf
        @method('PUT')
        
        {{--Encabezado con foto y acciones--}}
        <x-wire-card class="mb-8">
            <div class="lg:flex lg:justify-between lg:items-center">
                <div class="flex items-center">
                    {{--Avatar circular con iniciales--}}
                    <div class="h-20 w-20 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-2xl tracking-wider select-none shrink-0 shadow-sm">
                        @php
                            $initials = collect(explode(' ', $doctor->user->name))
                                ->map(fn($n) => mb_substr($n, 0, 1))
                                ->take(2)
                                ->join('');
                        @endphp
                        {{ strtoupper($initials) }}
                    </div>
                    
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-900">{{ $doctor->user->name }}</p>
                        <p class="text-sm font-semibold text-gray-500 mt-1">
                            Licencia: {{ $doctor->medical_license_number ?? 'N/A' }}
                        </p>
                    </div>
                </div>
                
                <div class="flex space-x-3 mt-6 lg:mt-0">
                    <x-wire-button outline gray href="{{ route('admin.doctors.index') }}">Volver</x-wire-button>
                    <x-wire-button type="submit" primary class="justify-center">
                        <i class="fa-solid fa-check me-2"></i>
                        Guardar cambios
                    </x-wire-button>
                </div>
            </div>
        </x-wire-card>

        {{--Formulario de edición de datos--}}
        <x-wire-card>
            <div class="space-y-6">
                
                {{--Especialidad--}}
                <div>
                    <x-wire-native-select label="Especialidad" name="speciality_id">
                        <option value="">Seleccione una especialidad</option>
                        @foreach ($specialities as $speciality)
                            <option value="{{ $speciality->id }}" {{ old('speciality_id', $doctor->speciality_id) == $speciality->id ? 'selected' : '' }}>
                                {{ $speciality->name }}
                            </option>
                        @endforeach
                    </x-wire-native-select>
                </div>

                {{--Licencia Médica--}}
                <div>
                    <x-wire-input label="Número de licencia médica" name="medical_license_number" placeholder="Ej. 123456789"
                        :value="old('medical_license_number', $doctor->medical_license_number)" />
                </div>

                {{--Biografía--}}
                <div>
                    <x-wire-textarea label="Biografía" name="biography" rows="4" placeholder="Escriba una breve descripción profesional..."
                        :value="old('biography', $doctor->biography)" />
                </div>

            </div>
        </x-wire-card>
    </form>
</x-admin-layout>
