<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            'Nutrition Program',
            'Health Promotion Program',
            'Breast Cancer Program',
            'Cervical Cancer Program',
            'Diabetes Program',
            'Hypertension Program',
            'Maternal Health Program',
            'Mental Health Program',
            'Road Safety Program',
            'HIV/AIDS Program',
            'Tuberculosis Program',
            'Water, Sanitation, and Hygiene (WASH) Program',
            'Immunization Program',
        ];

        foreach ($programs as $program) { 
            Program::create([
                'name' => $program,
                'active' => true,
            ]);
        }
    }
}
