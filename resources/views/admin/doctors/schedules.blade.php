<x-admin-layout title="Horarios del Doctor" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Doctores',
        'href' => route('admin.doctors.index'),
    ],
    [
        'name' => 'Horarios',
    ],
]">
    @livewire('admin.doctor-schedule-manager', ['doctor' => $doctor])
</x-admin-layout>
