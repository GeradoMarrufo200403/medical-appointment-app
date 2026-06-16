<div class="flex justify-start space-x-2">
    <a href="{{ route('admin.insurances.edit', $insurance) }}" class="btn btn-blue">
        <i class="fa-solid fa-pen"></i>
    </a>
    
    <form action="{{ route('admin.insurances.destroy', $insurance) }}" method="POST" id="delete-form-{{ $insurance->id }}">
        @csrf
        @method('DELETE')
        <button type="button" class="btn btn-red" onclick="confirmDelete({{ $insurance->id }})">
            <i class="fa-solid fa-trash"></i>
        </button>
    </form>
</div>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esto",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, borrar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
