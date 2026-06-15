<x-admin-layout title="Citas médicas" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Citas médicas'
    ],
]">
    <x-slot name="actions">
        <x-wire-button primary label="Nueva Cita" icon="plus" href="{{ route('admin.appointments.create') }}" class="shadow-sm" />
    </x-slot>

    @livewire('admin.datatables.appointment-table')

</x-admin-layout>
