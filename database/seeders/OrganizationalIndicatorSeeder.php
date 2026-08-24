<?php

namespace Database\Seeders;

use App\Models\OrganizationalIndicator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationalIndicatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $indicators = [
            'Total No. of PK Sites in Barangay',
            'Total No. of PK Sites with Active PK Teams',
            'Total No. of PK Teams with Complete Kits',
            'Total No. of PK Team Members Oriented/Trained on PK',
            'Total No. of Clients provided with Health Promotion',
            'Total No. of Clients provided with Health Services',
            'Total No. of First Patient Encounters (FPEs)',
            'Total No. of Referrals Made to Higher Facilities',
            'Total No. of PhilHealth Registrations',
            'Total No. of Large-scale activities conducted',
            'Total No. of PK activities conducted',
            'Total number puroks engaged in pk',
        ];

        foreach ($indicators as $indicator) {
            OrganizationalIndicator::create([
                'name'=> $indicator,
                'active'=> true,
            ]);
        }
    }
}
