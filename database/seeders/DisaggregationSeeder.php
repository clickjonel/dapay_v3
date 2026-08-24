<?php

namespace Database\Seeders;

use App\Models\Disaggregation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DisaggregationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $disaggregations = [
            [
                'name' => 'Male',
                'active'           => 1,
                'group'               => 'Sex',
            ],
            [
                'name' => 'Female',
                'active'           => 1,
                'group'               => 'Sex',
            ],
            [
                'name' => '4Ps Beneficiary',
                'active'           => 1,
                'group'               => 'DSWD',
            ],
            [
                'name' => 'Households',
                'active'           => 1,
                'group'               => 'WASH',
            ],
            [
                'name' => 'Individuals',
                'active'           => 1,
                'group'               => 'WASH',
            ],
            [
                'name' => 'Age group 60 years old and above',
                'active'           => 0,
                'group'               => 'Age Group',
            ],
        ];

        foreach ($disaggregations as $disaggregation) { 
            Disaggregation::create($disaggregation);
        }
    }
}
