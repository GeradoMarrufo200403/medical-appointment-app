<div class="flex items-center gap-2">
    @if ($appointment->status == 1)
        <x-wire-button href="{{ route('admin.appointments.consultation', $appointment) }}" positive xs title="Atender Cita">
            <i class="fa-solid fa-stethoscope"></i>
        </x-wire-button>
    @endif
    
    <x-wire-button href="{{ route('admin.appointments.edit', $appointment) }}" blue xs title="Editar Cita">
        <i class="fa-solid fa-pen-to-square"></i>
    </x-wire-button>
    
    <form action="{{ route('admin.appointments.destroy', $appointment) }}" method="POST" class="delete-form inline-block">
        @csrf
        @method('DELETE')
        <x-wire-button type="submit" red xs title="Eliminar Cita">
            <i class="fa-solid fa-trash-can"></i>
        </x-wire-button>
    </form>
</div>
