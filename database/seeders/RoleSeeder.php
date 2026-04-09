<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Definir los roles
        $roles = [
           ['name' => 'Paciente', 'is_system' => true],
           ['name' => 'Doctor', 'is_system' => true],
           ['name' => 'Recepcionista', 'is_system' => true],
           ['name' => 'Administrador', 'is_system' => true],
           ['name' => 'Super administrador', 'is_system' => true],
           
        ];

        //Crear los roles
        foreach ($roles as $role) {
            Role::create($role);
        }
     
    }
}
