<?php

namespace Database\Seeders;

use App\Models\Disaggregation;
use App\Models\Program;
use App\Models\Province;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       $this->call([
            ProvinceSeeder::class,
            MunicipalitySeeder::class,
            BarangaySeeder::class,
            UserSeeder::class,
            OrganizationalIndicatorSeeder::class,
            ProgramSeeder::class,
            ProgrammaticIndicatorSeeder::class,
            DisaggregationSeeder::class,
            ProgrammaticIndicatorDisaggregationSeeder::class,
            BarangayOrganizationalIndicatorSeeder::class,
            BarangayPriorityProgramSeeder::class,
            TeamSeeder::class,
            TeamMemberSeeder::class,
            PKActivitySeeder::class,
            ReportSeeder::class,
       ]);
    }
}
