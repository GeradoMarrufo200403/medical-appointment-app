<div>
    <x-wire-card>
        <div class="flex justify-between items-center border-b border-gray-200 pb-4 mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Gestor de horarios</h2>
                <p class="text-sm text-gray-500 mt-1">Configure las horas y días en los que el Dr. {{ $doctor->user->name }} estará disponible.</p>
            </div>
            <x-wire-button primary label="Guardar horario" wire:click="save" icon="check" class="shadow-sm" />
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-lg">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider border-r border-gray-200 w-48">
                            DÍA/HORA
                        </th>
                        @foreach ($days as $day)
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider border-r border-gray-200 last:border-r-0">
                                {{ strtoupper($day) }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($hours as $hour)
                        <tr class="hover:bg-gray-50/50">
                            <!-- Hour column -->
                            <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 border-r border-gray-200 bg-gray-50/30">
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" 
                                           id="hour-{{ $hour }}"
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 cursor-pointer"
                                           {{ $this->isHourChecked($hour) ? 'checked' : '' }}
                                           wire:click="toggleHour('{{ $hour }}')">
                                    <label for="hour-{{ $hour }}" class="cursor-pointer select-none">
                                        {{ $hour }}
                                    </label>
                                </div>
                            </td>

                            <!-- Weekdays columns -->
                            @foreach ($days as $day)
                                <td class="px-4 py-3 border-r border-gray-200 last:border-r-0">
                                    <div class="space-y-2">
                                        <!-- Todos checkbox -->
                                        <div class="flex items-center space-x-2 border-b border-gray-100 pb-1">
                                            <input type="checkbox" 
                                                   id="all-{{ $day }}-{{ $hour }}"
                                                   class="rounded border-gray-300 text-emerald-600 shadow-sm focus:border-emerald-300 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 cursor-pointer"
                                                   {{ $this->isDayHourChecked($day, $hour) ? 'checked' : '' }}
                                                   wire:click="toggleDayHour('{{ $day }}', '{{ $hour }}')">
                                            <label for="all-{{ $day }}-{{ $hour }}" class="text-xs font-semibold text-emerald-700 cursor-pointer select-none">
                                                Todos
                                            </label>
                                        </div>

                                        <!-- Slots checkboxes -->
                                        <div class="grid grid-cols-1 gap-1">
                                            @foreach ($this->getSlotsForHour($hour) as $slot)
                                                <div class="flex items-center space-x-2">
                                                    <input type="checkbox" 
                                                           id="slot-{{ $day }}-{{ $hour }}-{{ $slot }}"
                                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 cursor-pointer text-xs"
                                                           wire:model="schedules.{{ $day }}.{{ $hour }}.{{ $slot }}">
                                                    <label for="slot-{{ $day }}-{{ $hour }}-{{ $slot }}" class="text-xs text-gray-600 cursor-pointer select-none">
                                                        {{ $slot }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-wire-card>
</div>
