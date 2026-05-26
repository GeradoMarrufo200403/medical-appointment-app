<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Speciality;

class SpecialitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialities = [
            'Cardiología',
            'Pediatría',
            'Dermatología',
            'Ginecología y Obstetricia',
            'Traumatología y Ortopedia',
            'Oftalmología',
            'Otorrinolaringología',
            'Neurología',
            'Psiquiatría',
            'Urología'
        ];

        foreach ($specialities as $speciality) {
            Speciality::firstOrCreate(['name' => $speciality]);
        }
    }
}
