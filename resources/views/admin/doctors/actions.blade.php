<div class="flex items-center gap-2">
    <x-wire-button href="{{ route('admin.doctors.edit', $doctor) }}" blue xs>
        <i class="fa-solid fa-pen-to-square"></i>
    </x-wire-button>
    <x-wire-button href="{{ route('admin.doctors.schedules', $doctor) }}" positive xs title="Horarios">
        <i class="fa-solid fa-clock"></i>
    </x-wire-button>
</div>
