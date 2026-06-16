<x-admin-layout title="Seguros" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
        
    ],
    [
        'name' => 'Seguros',
        'href' => route('admin.insurances.index'),
    ],
    [
        'name' => 'Crear',
    ],

]">
    <x-wire-card>
        <form action="{{ route('admin.insurances.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <x-wire-input label="Nombre de la Aseguradora" name="name" placeholder="Ej. GNP Seguros" 
                    value="{{ old('name') }}" required></x-wire-input>

                <x-wire-input label="Porcentaje de Descuento (%)" name="discount_percentage" placeholder="Ej. 15.5" type="number" step="0.01" min="0" max="100"
                    value="{{ old('discount_percentage') }}"></x-wire-input>
            </div>

            <div class="mb-4">
                <x-wire-textarea label="Detalles de Póliza/Convenio" name="policy_details" placeholder="Información sobre la cobertura, condiciones, etc.">
                    {{ old('policy_details') }}
                </x-wire-textarea>
            </div>

            <div class="flex justify-end mt-4">
                <x-wire-button href="{{ route('admin.insurances.index') }}" secondary class="mr-2">Cancelar</x-wire-button>
                <x-wire-button type="submit" blue>Guardar Seguro</x-wire-button>
            </div>
        </form>
    </x-wire-card>

</x-admin-layout>
