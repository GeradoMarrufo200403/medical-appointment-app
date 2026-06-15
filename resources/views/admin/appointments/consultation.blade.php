<x-admin-layout title="Atender Cita" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Citas médicas',
        'href' => route('admin.appointments.index'),
    ],
    [
        'name' => 'Atender Cita',
    ],
]">
    @livewire('admin.consultation-manager', ['appointment' => $appointment])
</x-admin-layout>
