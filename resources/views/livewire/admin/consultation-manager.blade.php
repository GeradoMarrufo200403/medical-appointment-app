<div>
    <!-- Header Patient Details & Top Actions -->
    <x-wire-card class="mb-8">
        <div class="lg:flex lg:justify-between lg:items-center">
            <div class="flex items-center">
                <!-- Circular Avatar with Initials -->
                <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xl select-none shrink-0 shadow-sm">
                    @php
                        $initials = collect(explode(' ', $appointment->patient->user->name))
                            ->map(fn($n) => mb_substr($n, 0, 1))
                            ->take(2)
                            ->join('');
                    @endphp
                    {{ strtoupper($initials) }}
                </div>
                <div class="ml-4">
                    <h2 class="text-xl font-bold text-gray-900">Atendiendo cita de: {{ $appointment->patient->user->name }}</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Médico: <span class="font-medium text-gray-700">Dr(a). {{ $appointment->doctor->user->name }}</span> | 
                        Fecha: <span class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }} ({{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }})</span>
                    </p>
                </div>
            </div>
            
            <div class="flex flex-wrap gap-3 mt-6 lg:mt-0">
                <!-- Action Buttons -->
                <x-wire-button outline gray label="Volver" href="{{ route('admin.appointments.index') }}" />
                
                <x-wire-button info outline label="Consultas Anteriores" icon="clock" wire:click="openPastConsultations" />
                
                <x-wire-button primary outline label="Ver Historia" icon="user" wire:click="openMedicalHistory" />
                
                <x-wire-button positive label="Guardar Consulta" icon="check" wire:click="save" />
            </div>
        </div>
    </x-wire-card>

    <!-- Main Consultation Tab Card -->
    <x-wire-card>
        <!-- Custom Tabs Menu -->
        <div class="border-b border-gray-200 mb-6">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500">
                <li class="me-2">
                    <button type="button" 
                            wire:click="$set('activeTab', 'consulta')"
                            class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group transition-colors duration-200 {{ $activeTab === 'consulta' ? 'text-blue-600 border-blue-600' : 'border-transparent hover:text-blue-600 hover:border-blue-300' }}">
                        <i class="fa-solid fa-file-waveform me-2"></i>
                        Consulta
                        @if ($errors->has('diagnosis') || $errors->has('treatment'))
                            <span class="ms-2 inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-2xs font-semibold bg-red-100 text-red-600">!</span>
                        @endif
                    </button>
                </li>
                <li class="me-2">
                    <button type="button" 
                            wire:click="$set('activeTab', 'receta')"
                            class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group transition-colors duration-200 {{ $activeTab === 'receta' ? 'text-blue-600 border-blue-600' : 'border-transparent hover:text-blue-600 hover:border-blue-300' }}">
                        <i class="fa-solid fa-pills me-2"></i>
                        Receta
                        @if ($errors->has('medicines') || $errors->has('medicines.*'))
                            <span class="ms-2 inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-2xs font-semibold bg-red-100 text-red-600">!</span>
                        @endif
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Content: Consulta -->
        <div wire:key="tab-consulta" class="{{ $activeTab === 'consulta' ? '' : 'hidden' }}">
            <div class="space-y-6">
                <!-- Diagnosis -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Diagnóstico <span class="text-red-500">*</span></label>
                    <textarea wire:model.defer="diagnosis" 
                              rows="4" 
                              placeholder="Describa el diagnóstico clínico detallado del paciente..."
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('diagnosis') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"></textarea>
                    @error('diagnosis')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Treatment -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tratamiento <span class="text-red-500">*</span></label>
                    <textarea wire:model.defer="treatment" 
                              rows="4" 
                              placeholder="Describa el plan de tratamiento prescrito..."
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('treatment') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"></textarea>
                    @error('treatment')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Notas (Opcional)</label>
                    <textarea wire:model.defer="notes" 
                              rows="3" 
                              placeholder="Notas adicionales o recomendaciones clínicas..."
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
            </div>
        </div>

        <!-- Tab Content: Receta -->
        <div wire:key="tab-receta" class="{{ $activeTab === 'receta' ? '' : 'hidden' }}">
            <div class="space-y-4">
                <div class="flex justify-between items-center pb-2 border-b border-gray-100">
                    <h3 class="text-md font-bold text-gray-800">Medicamentos recetados</h3>
                    <x-wire-button sm primary label="Añadir medicamento" icon="plus" wire:click="addMedicine" />
                </div>

                @error('medicines')
                    <div class="bg-red-50 text-red-600 text-sm p-3 rounded-lg">{{ $message }}</div>
                @enderror

                <div class="space-y-3">
                    @foreach ($medicines as $index => $medicine)
                        <div class="flex items-start gap-4 p-3 bg-gray-50 border border-gray-100 rounded-lg" wire:key="med-{{ $index }}">
                            <div class="flex-grow grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Medicine Name -->
                                <div>
                                    <input type="text" 
                                           wire:model.defer="medicines.{{ $index }}.name"
                                           placeholder="Nombre del medicamento (ej: Paracetamol 500mg)"
                                           class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('medicines.'.$index.'.name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                    @error('medicines.'.$index.'.name')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Dose / Indications -->
                                <div>
                                    <input type="text" 
                                           wire:model.defer="medicines.{{ $index }}.dose"
                                           placeholder="Dosis e indicaciones (ej: 1 tableta cada 8 horas por 5 días)"
                                           class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('medicines.'.$index.'.dose') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                                    @error('medicines.'.$index.'.dose')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Remove Button -->
                            @if (count($medicines) > 2)
                                <button type="button" 
                                        wire:click="removeMedicine({{ $index }})"
                                        class="p-2 text-red-600 hover:text-red-800 transition-colors mt-0.5 rounded-full hover:bg-red-50">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </x-wire-card>

    <!-- Modal: Consultas Anteriores -->
    @if ($showPastConsultationsModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closePastConsultations"></div>

                <!-- Center element -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal Content -->
                <div class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900" id="modal-title">
                            Historial Clínico: Consultas Anteriores
                        </h3>
                        <button type="button" class="text-gray-400 hover:text-gray-500" wire:click="closePastConsultations">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>

                    <div class="px-6 py-6 max-h-[70vh] overflow-y-auto space-y-6">
                        @if ($pastConsultations->isEmpty())
                            <div class="text-center py-8">
                                <i class="fa-solid fa-folder-open text-gray-300 text-5xl mb-3"></i>
                                <p class="text-gray-500 font-medium">No se encontraron consultas anteriores para este paciente.</p>
                            </div>
                        @else
                            @foreach ($pastConsultations as $consultation)
                                <div class="border border-gray-200 rounded-lg p-5 bg-white shadow-sm space-y-4">
                                    <div class="flex justify-between items-start border-b border-gray-100 pb-3">
                                        <div>
                                            <p class="text-sm font-semibold text-blue-700">Atendido por: Dr(a). {{ $consultation->appointment->doctor->user->name }}</p>
                                            <p class="text-xs text-gray-400 mt-1">Cita del: {{ \Carbon\Carbon::parse($consultation->appointment->date)->format('d/m/Y') }} ({{ \Carbon\Carbon::parse($consultation->appointment->start_time)->format('H:i') }})</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <h4 class="text-xs font-bold text-gray-500 uppercase">Diagnóstico</h4>
                                            <p class="text-sm text-gray-800 mt-1 whitespace-pre-line">{{ $consultation->diagnosis }}</p>
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-bold text-gray-500 uppercase">Tratamiento</h4>
                                            <p class="text-sm text-gray-800 mt-1 whitespace-pre-line">{{ $consultation->treatment }}</p>
                                        </div>
                                    </div>

                                    @if ($consultation->notes)
                                        <div>
                                            <h4 class="text-xs font-bold text-gray-500 uppercase">Notas</h4>
                                            <p class="text-sm text-gray-600 mt-1 whitespace-pre-line">{{ $consultation->notes }}</p>
                                        </div>
                                    @endif

                                    @if ($consultation->medicines && count($consultation->medicines) > 0)
                                        <div class="pt-2 border-t border-gray-100">
                                            <h4 class="text-xs font-bold text-gray-500 uppercase mb-2">Receta Médica</h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                @foreach ($consultation->medicines as $med)
                                                    <div class="text-xs bg-gray-50 border border-gray-100 rounded px-3 py-2">
                                                        <span class="font-bold text-gray-700">{{ $med['name'] }}</span>: 
                                                        <span class="text-gray-600">{{ $med['dose'] }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end">
                        <x-wire-button gray label="Cerrar" wire:click="closePastConsultations" />
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal: Historia Médica del Paciente -->
    @if ($showMedicalHistoryModal)
        <div class="fixed inset-0 z-50 overflow-y-auto animate-fade-in" aria-labelledby="modal-title-history" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeMedicalHistory"></div>

                <!-- Center element -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal Content -->
                <div class="inline-block align-middle bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-100">
                    <!-- Header -->
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900" id="modal-title-history">
                            Historia médica del paciente
                        </h3>
                        <button type="button" class="text-gray-400 hover:text-gray-500" wire:click="closeMedicalHistory">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-8 space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                            <div>
                                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Tipo de sangre:</span>
                                <span class="block text-sm font-bold text-gray-800 mt-1.5">
                                    {{ $appointment->patient->bloodType->name ?? 'No registrado' }}
                                </span>
                            </div>
                            <div>
                                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Alergias:</span>
                                <span class="block text-sm font-bold text-gray-800 mt-1.5">
                                    {{ $appointment->patient->allergies ?: 'No registradas' }}
                                </span>
                            </div>
                            <div>
                                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Enfermedades crónicas:</span>
                                <span class="block text-sm font-bold text-gray-800 mt-1.5">
                                    {{ $appointment->patient->chronic_conditions ?: 'No registradas' }}
                                </span>
                            </div>
                            <div>
                                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Antecedentes quirúrgicos:</span>
                                <span class="block text-sm font-bold text-gray-800 mt-1.5">
                                    {{ $appointment->patient->surgical_history ?: 'No registrados' }}
                                </span>
                            </div>
                        </div>

                        <!-- Action Link inside body matching design -->
                        <div class="pt-6 border-t border-gray-100 flex justify-end">
                            <a href="{{ route('admin.patients.edit', $appointment->patient_id) }}" 
                               target="_blank" 
                               class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors flex items-center gap-1.5">
                                <span>Ver / Editar Historia Médica</span>
                                <i class="fa-solid fa-up-right-from-square text-2xs"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end">
                        <x-wire-button gray label="Cerrar" wire:click="closeMedicalHistory" />
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
