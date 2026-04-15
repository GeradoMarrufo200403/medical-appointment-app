<x-admin-layout title="Usuarios" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
        
    ],
    [
        'name' => 'Usuarios',
        'href' => route('admin.users.index'),
    ],
    [
        'name' => 'Crear',
    ],

]">
    <x-wire-card>
        <x-validation-errors class="mb-4" />
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div class="grid lg:grid-cols-2 gap-4">
                    <x-wire-input label="Nombre" name="name" placeholder="Nombre completo"
                        :value="old('name', $user->name)"></x-wire-input>

                    <x-wire-input label="correo electronico" name="email" type="email" placeholder="ejemplo@dominio.com"
                        autocomplete="email" :value="old('email', $user->email)"></x-wire-input>

                    <x-wire-input label="Contraseña" name="password" type="password" placeholder="Minimo 8 caracteres"
                        autocomplete="new-password"></x-wire-input>

                    <x-wire-input label="Confirmar contraseña" name="password_confirmation" placeholder="Confirmar contraseña"
                        autocomplete="new-password"></x-wire-input>

                    <x-wire-input label="Numero de ID" name="id_number" placeholder="Ej. 123456789" autocomplete="off" required
                        inputmode="numeric" :value="old('id_number', $user->id_number)"></x-wire-input>

                    <x-wire-input label="Telefono" name="phone" placeholder="Ej. 999999999" autocomplete="tel" required
                        inputmode="tel" :value="old('phone', $user->phone)"></x-wire-input>

                </div>
                <x-wire-input name="address" label="Direccion" required :value="old('address', $user->address)" placeholder="Ej. Calle 123, Col. Centro"
                    autocomplete="street-address"></x-wire-input>

                <div class="space-y-1">
                    <x-wire-native-select name="role_id" label="Rol" required>
                        <option value="">
                            Seleccione un rol
                        </option>

                        @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id', $user->roles->first()->id) == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                        @endforeach
                    </x-wire-native-select>
                    <p class="text-sm text-gray-500">
                        Define lo permisos y accesos del Usuarios.
                    </p>
                </div>

                <div class="flex justify-end">
                    <x-wire-button type="submit" blue>Actualizar</x-wire-button>
                </div>
            </div>
        </form>

    </x-wire-card>


</x-admin-layout>