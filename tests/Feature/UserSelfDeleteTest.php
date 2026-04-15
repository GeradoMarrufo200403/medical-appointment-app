<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

//Usamos el trait para refrescar la base de datos entre  test

test('Un usuario no puede eliminar su propia cuenta', function () {
    //1. Crear un usuario
    $user = User::factory()->create([
        //JetStream exige este campo para funcionar
        'email_verified_at' => now(),
    ]);

    //2. Actuar como ese usuario
    $this->actingAs($user, 'web');

    //3. Intentar eliminar su propia cuenta
    $response = $this->delete(route('admin.users.destroy', $user));

    //4. Verificar que la respuesta sea una redirección (error)
    $response->assertStatus(403);

    //5. Verificar que el usuario realmente no fue eliminado
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
    ]);

    
});
