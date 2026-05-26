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

        // Crear doctor de prueba para demostración y evaluación visual
        $speciality = \App\Models\Speciality::where('name', 'Cardiología')->first();

        $doctorUser = User::factory()->create([
            'name' => 'Iris Godoy',
            'email' => 'iris.godoy@gmail.com',
            'password' => bcrypt('12345678'),
            'id_number' => '6549876341654654',
            'phone' => '999999999',
            'address' => 'Av. de los Doctores 123',
        ]);
        $doctorUser->assignRole('Doctor');
        
        $doctorUser->doctor()->create([
            'speciality_id' => $speciality?->id ?? 1,
            'medical_license_number' => '6549876341654654',
            'biography' => 'Hola soy un doctor',
        ]);

        // Restaurar a la paciente 'Yuri' que tenías creada manualmente
        $yuriUser = User::factory()->create([
            'name' => 'Yuri',
            'email' => 'sisco@gmail.com',
            'password' => bcrypt('12345678'),
            'id_number' => '987654321', // ID ficticio para Yuri
            'phone' => '999999999',
            'address' => 'Calle Ficticia 456',
        ]);
        $yuriUser->assignRole('Paciente');
        $yuriUser->patient()->create([]);
    }
}
