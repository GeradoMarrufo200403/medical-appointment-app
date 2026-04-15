<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
   
        // Crear usuario de prueba cada que se ejecuten las migraciones
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'prueba@gmail.com',
            'password' => bcrypt('12345678'),
            'id_number' => '123456789',
            'phone' => '999999999',
            'address' => 'Test Address',     
        ])->assignRole('Administrador');
    }
}
