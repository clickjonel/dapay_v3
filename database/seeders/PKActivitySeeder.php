<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PKActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pk_activities_sql = file_get_contents(
            database_path('seeders/sql/pk_activities.sql')
        );

        $pk_activity_programs_sql = file_get_contents(
            database_path('seeders/sql/pk_activity_programs.sql')
        );

        $pk_activity_barangays_sql = file_get_contents(
            database_path('seeders/sql/pk_activity_barangays.sql')
        );

        $pk_activity_hrh_sql = file_get_contents(
            database_path('seeders/sql/pk_activity_hrh.sql')
        );

        DB::unprepared($pk_activities_sql);
        DB::unprepared($pk_activity_programs_sql);
        DB::unprepared($pk_activity_barangays_sql);
        DB::unprepared($pk_activity_hrh_sql);
    }
}
