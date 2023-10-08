<?php

namespace Database\Seeders;

use App\Models\Grado;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Grado::insert([
            [
                'name' => 'TU_NOMBRE',
                'email' => 'tu@correo.com',
                'password' => Hash::make('password')
    
            ],
        ]);
    }
}
