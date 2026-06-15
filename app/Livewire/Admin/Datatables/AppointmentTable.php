<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;

class AppointmentTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return Appointment::query()
            ->with(['patient.user', 'doctor.user']);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        
        $this->setDefaultSort('date', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Paciente", "patient.user.name")
                ->sortable()
                ->searchable(),
            Column::make("Médico", "doctor.user.name")
                ->sortable()
                ->searchable(),
            Column::make("Fecha", "date")
                ->sortable()
                ->format(fn($value) => \Carbon\Carbon::parse($value)->format('d/m/Y')),
            Column::make("Hora de inicio", "start_time")
                ->sortable()
                ->format(fn($value) => \Carbon\Carbon::parse($value)->format('H:i')),
            Column::make("Estado", "status")
                ->sortable()
                ->format(function($value) {
                    if ($value == 1) {
                        return '<span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Pendiente</span>';
                    } elseif ($value == 2) {
                        return '<span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">Atendida</span>';
                    } else {
                        return '<span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">Cancelada</span>';
                    }
                })
                ->html(),
            Column::make("Acciones")
                ->label(function ($row) {
                    return view(
                        'admin.appointments.actions',
                        ['appointment' => $row]
                    );
                })
        ];
    }
}
