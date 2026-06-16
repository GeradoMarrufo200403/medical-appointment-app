<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Insurance;
use Illuminate\Database\Eloquent\Builder;

class InsuranceTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return Insurance::query();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Aseguradora", "name")
                ->searchable()
                ->sortable(),
            Column::make("Detalles de póliza", "policy_details")
                ->searchable()
                ->sortable(),
            Column::make("Descuento (%)", "discount_percentage")
                ->sortable(),
            Column::make("Acciones")
                ->label(function ($row) {
                    return view(
                        'admin.insurances.actions',
                        ['insurance' => $row]
                    );
                })
        ];
    }
}
