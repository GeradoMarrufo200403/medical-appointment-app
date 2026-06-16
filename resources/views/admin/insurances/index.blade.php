<x-admin-layout title="Seguros" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
        
    ],
    [
        'name' => 'Seguros',
    ],

]">
    <x-slot name="actions">
        <x-wire-button blue href="{{ route('admin.insurances.create') }}">
            <i class="fa-solid fa-plus"></i> 
            Nuevo Seguro
        </x-wire-button>
    </x-slot>
    
    @livewire('admin.datatables.insurance-table')
  
</x-admin-layout>
