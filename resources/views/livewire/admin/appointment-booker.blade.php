<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Breadcrumbs -->
    <div class="mb-6">
        <div class="flex items-center text-xs sm:text-sm text-gray-500 space-x-2">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition-colors">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.appointments.index') }}" class="hover:text-indigo-600 transition-colors">Citas</a>
            <span>/</span>
            <span class="text-gray-800 font-medium">Nuevo</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Nuevo</h1>
    </div>

    <!-- Search Form Card -->
    <x-wire-card class="mb-8 shadow-sm border border-gray-100">
        <div>
            <h3 class="text-lg font-bold text-gray-900">Buscar disponibilidad</h3>
            <p class="text-sm text-gray-500 mt-0.5">Encuentra el horario perfecto para tu cita.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-5 items-end">
            <!-- Date Input -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Fecha</label>
                <input type="date" 
                       wire:model.defer="date" 
                       class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('date') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" />
                @error('date')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Hour Select -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Hora</label>
                <select wire:model.defer="hour_range" 
                        class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Cualquier hora</option>
                    @foreach ($hours as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Specialty Select -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Especialidad (opcional)</label>
                <select wire:model.defer="speciality_id" 
                        class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Todas las especialidades</option>
                    @foreach ($specialities as $spec)
                        <option value="{{ $spec->id }}">{{ $spec->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Search Button -->
            <div>
                <x-wire-button primary 
                               label="Buscar disponibilidad" 
                               class="w-full h-[42px] bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-md shadow-sm border-indigo-600" 
                               wire:click="search" />
            </div>
        </div>
    </x-wire-card>

    <!-- Two-Column Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- Left: Doctors Search Results (spans 2 columns) -->
        <div class="lg:col-span-2 space-y-6">
            @if (!$hasSearched)
                <x-wire-card class="text-center py-16 border border-dashed border-gray-200 shadow-none">
                    <div class="flex flex-col items-center justify-center">
                        <div class="h-16 w-16 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mb-4">
                            <i class="fa-solid fa-magnifying-glass text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-800">Comience su búsqueda</h4>
                        <p class="text-sm text-gray-500 max-w-sm mt-2">Seleccione los criterios de fecha y rango de hora arriba y pulse "Buscar disponibilidad".</p>
                    </div>
                </x-wire-card>
            @elseif (empty($searchResults))
                <x-wire-card class="text-center py-16 border border-gray-150">
                    <div class="flex flex-col items-center justify-center">
                        <div class="h-16 w-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mb-4">
                            <i class="fa-solid fa-calendar-xmark text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-800">Sin disponibilidad encontrada</h4>
                        <p class="text-sm text-gray-500 max-w-sm mt-2">No hay médicos con horarios activos o disponibles para el día y hora seleccionados. Intente con otra fecha u hora.</p>
                    </div>
                </x-wire-card>
            @else
                <div class="space-y-4">
                    @foreach ($searchResults as $doc)
                        <x-wire-card class="p-6 shadow-sm border border-gray-100 hover:border-gray-200 transition-all duration-200" wire:key="doc-card-{{ $doc['id'] }}">
                            <div class="flex items-start">
                                <!-- Initials Avatar -->
                                <div class="h-14 w-14 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 font-extrabold text-lg select-none shrink-0 shadow-sm">
                                    {{ $doc['initials'] }}
                                </div>
                                <div class="ml-4 flex-grow">
                                    <h4 class="text-lg font-bold text-gray-900">Dr(a). {{ $doc['name'] }}</h4>
                                    <p class="text-sm text-indigo-600 font-medium mt-0.5">{{ $doc['speciality'] }}</p>
                                    
                                    <div class="mt-5 pt-4 border-t border-gray-100">
                                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Horarios disponibles:</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($doc['slots'] as $slot)
                                                @php
                                                    $isSelected = ($selectedDoctorId == $doc['id'] && $selectedSlot == $slot['range']);
                                                @endphp
                                                <button type="button" 
                                                        wire:click="selectSlot({{ $doc['id'] }}, 'Dr(a). {{ addslashes($doc['name']) }}', '{{ addslashes($doc['speciality']) }}', '{{ $slot['range'] }}', '{{ $slot['start'] }}', '{{ $slot['end'] }}')"
                                                        class="px-4 py-2.5 text-xs font-semibold rounded-md border transition-all duration-200 shadow-sm
                                                               {{ $isSelected 
                                                                   ? 'bg-indigo-600 border-indigo-600 text-white hover:bg-indigo-700' 
                                                                   : 'bg-indigo-50 border-indigo-100 text-indigo-700 hover:bg-indigo-100 hover:text-indigo-800' }}">
                                                    {{ $slot['display'] }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </x-wire-card>
                    @endforeach
                </div>
            @endif
        </div>
        
        <!-- Right: Booking Summary Sidebar (spans 1 column) -->
        <div>
            <x-wire-card class="shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 pb-3.5 border-b border-gray-100">Resumen de la cita</h3>
                
                <div class="py-4 space-y-3.5 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Doctor:</span>
                        <span class="font-bold text-gray-800 text-right">{{ $selectedDoctorName ?: '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Fecha:</span>
                        <span class="font-bold text-gray-800">
                            {{ $selectedDoctorId ? \Carbon\Carbon::parse($date)->format('Y-m-d') : '-' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Horario:</span>
                        <span class="font-bold text-gray-800">
                            {{ $selectedSlot ? $selectedSlotStart . ' - ' . $selectedSlotEnd : '-' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Duración:</span>
                        <span class="font-bold text-gray-800">
                            {{ $selectedSlot ? '15 minutos' : '-' }}
                        </span>
                    </div>
                </div>
                
                <div class="border-t border-gray-100 pt-5 space-y-4">
                    <!-- Patient Select -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Paciente</label>
                        <select wire:model="patientId" 
                                class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('patientId') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            <option value="">Seleccione un paciente</option>
                            @foreach ($patients as $pat)
                                <option value="{{ $pat->id }}">{{ $pat->user->name }}</option>
                            @endforeach
                        </select>
                        @error('patientId')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Reason -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Motivo de la cita</label>
                        <textarea wire:model.defer="reason" 
                                  placeholder="Escriba aquí el motivo detallado de la cita..." 
                                  rows="4" 
                                  class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('reason') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"></textarea>
                        @error('reason')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Appointment Selection Errors -->
                    @error('selectedSlot')
                        <div class="p-3 bg-red-50 border border-red-100 rounded-md text-center text-xs font-semibold text-red-600">
                            {{ $message }}
                        </div>
                    @enderror
                    @error('selectedDoctorId')
                        <div class="p-3 bg-red-50 border border-red-100 rounded-md text-center text-xs font-semibold text-red-600">
                            {{ $message }}
                        </div>
                    @enderror
                    
                    <!-- Confirm Appointment Button -->
                    <div class="pt-2">
                        <button type="button" 
                                wire:click="save"
                                class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-md shadow-sm transition-all duration-150 border border-indigo-600 hover:border-indigo-700 text-center text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Confirmar cita
                        </button>
                    </div>
                </div>
            </x-wire-card>
        </div>
    </div>
</div>
