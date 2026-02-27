<div class="flex items-center gap-2">
    <x-wireui::button href="{{ route('admin.roles.edit', $role) }}" blue xs>
        <i class="fa-solid fa-pen-to-square"></i>
    </x-wireui::button>

    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <x-wire-button type="submit" color="red xs">
            <i class="fa-solid fa-trash"></i>
        </x-wire-button>
    </form>

</div>