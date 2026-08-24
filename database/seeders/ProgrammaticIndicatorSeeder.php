<?php

namespace Database\Seeders;

use App\Models\ProgrammaticIndicator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgrammaticIndicatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $indicators = [
            [
                'name' => 'Number of children aged 12-59 months who completed 2 doses of Vitamin A',
                'active'      => 1,
                'program_id'     => 1,
                'scope'          => 'Central',
            ],
            [
                'name' => 'Number of children vaccinated with MMR2 antigen through routine immunization',
                'active'      => 1,
                'program_id'     => 13,
                'scope'          => 'Central',
            ],
            [
                'name' => 'Number of pregnant women aged 10-49 years old with high-risk conditions',
                'active'      => 1,
                'program_id'     => 7,
                'scope'          => 'Central',
            ],
            [
                'name' => 'Number of households with access to basic safe water supply',
                'active'      => 1,
                'program_id'     => 12,
                'scope'          => 'Central',
            ],
            [
                'name' => 'Number of registered DSTB all forms that are cured and completed treatment',
                'active'      => 1,
                'program_id'     => 11,
                'scope'          => 'Central',
            ],
            [
                'name' => 'Number of pregnant women screened for HIV',
                'active'      => 1,
                'program_id'     => 10,
                'scope'          => 'Central',
            ],
            [
                'name' => 'Number of individuals involved in road crash accidents provided with care',
                'active'      => 1,
                'program_id'     => 9,
                'scope'          => 'Central',
            ],
            [
                'name' => 'Number of adults aged 20-59 y/o and elderlies aged 60 y/o and above screened for hypertension',
                'active'      => 1,
                'program_id'     => 6,
                'scope'          => 'Central',
            ],
            [
                'name' => 'Number of adults aged 20-59 y/o and elderlies aged 60 y/o and above screened for diabetes',
                'active'      => 1,
                'program_id'     => 5,
                'scope'          => 'Central',
            ],
            [
                'name' => 'Number of women aged 30-65 years old screened or assessed for cervical cancer',
                'active'      => 1,
                'program_id'     => 10,
                'scope'          => 'Central',
            ],
            [
                'name' => 'Number of individuals with mental health concern assessed and provided services',
                'active'      => 1,
                'program_id'     => 8,
                'scope'          => 'Central',
            ],
            [
                'name' => 'Number of clients served with other services related to Nutrition Program',
                'active'      => 1,
                'program_id'     => 1,
                'scope'          => 'Central',
            ],
            [
                'name' => 'Number of clients served with other services related to Health Promotion Program',
                'active'      => 1,
                'program_id'     => 2,
                'scope'          => 'Central',
            ],
            [
                'name' => 'Number of clients served with other services related to Breast Cancer Program',
                'active'      => 1,
                'program_id'     => 3,
                'scope'          => 'Central',
            ],
            [
                'name' => 'Number of clients served with other services related to Cervical Cancer Program',
                'active'      => 1,
                'program_id'     => 4,
                'scope'          => 'Central',
            ],
            [
                'name' => 'Number of clients served with other services related to Diabetes Program',
                'active'      => 1,
                'program_id'     => 5,
                'scope'          => 'Central',
            ],
            [
                'name' => 'Number of clients served with other services related to Hypertension Program',
                'active'      => 1,
                'program_id'     => 6,
                'scope'          => 'Central',
            ],
            [
                'name' => 'Number of clients served with other services related to Maternal Health Program',
                'active'      => 1,
                'program_id'     => 7,
                'scope'          => 'Central',
            ],
            [
                'name' => 'Number of clients served with other services related to Mental Health Program',
                'active'      => 1,
                'program_id'     => 8,
                'scope'          => 'Central',
            ],
            [
                'name' => 'Number of clients served with other services related to Road Safety Program',
                'active'      => 1,
                'program_id'     => 9,
                'scope'          => 'Central',
            ],
            [
                'name' => 'Number of clients served with other services related to HIV/AIDS Program',
                'active'      => 1,
                'program_id'     => 10,
                'scope'          => 'Central',
            ],
            [
                'name' => 'Number of clients served with other services related to Tuberculosis Program',
                'active'      => 1,
                'program_id'     => 11,
                'scope'          => 'Central',
            ],
            [
                'name' => 'Number of clients served with other services related to Water, Sanitation, and Hygiene (WASH) Program',
                'active'      => 1,
                'program_id'     => 12,
                'scope'          => 'Central',
            ],
            [
                'name' => 'Number of clients served with other services related to Immunization Program',
                'active'      => 1,
                'program_id'     => 13,
                'scope'          => 'Central',
            ],
        ];

        foreach($indicators as $indicator){
            ProgrammaticIndicator::create($indicator);
        }
    }
}
