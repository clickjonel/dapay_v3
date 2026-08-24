<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting Province Seeder.');
        
        $provinces = [
            ['id' => 1, 'psgc_code' => '1400100000', 'name' => 'Abra'],
            ['id' => 2, 'psgc_code' => '1401100000', 'name' => 'Benguet'],
            ['id' => 3, 'psgc_code' => '1402700000', 'name' => 'Ifugao'],
            ['id' => 4, 'psgc_code' => '1403200000', 'name' => 'Kalinga'],
            ['id' => 5, 'psgc_code' => '1404400000', 'name' => 'Mountain Province'],
            ['id' => 6, 'psgc_code' => '1408100000', 'name' => 'Apayao'],
            ['id' => 7, 'psgc_code' => '1430300000', 'name' => 'Baguio City'],
        ];

        Province::insert($provinces);

        $this->command->info('Provinces Have Been Seeded Successfuly.');
    }
}
