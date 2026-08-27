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
        // 1. Disable query logging to keep PHP memory at 0MB
        DB::disableQueryLog();

        // 2. Define target SQL files
        $sqlFiles = [
            'seeders/sql/pk_activities.sql',
            'seeders/sql/pk_activity_programs.sql',
            'seeders/sql/pk_activity_barangays.sql',
            'seeders/sql/pk_activity_hrh.sql',
        ];

        // 3. Database configuration
        $db = config('database.connections.' . config('database.default'));
        
        // Speed flags: Turn off FK checks, unique checks, binary logs, and wrap in 1 transaction
        $tuningFlags = "SET FOREIGN_KEY_CHECKS=0; SET UNIQUE_CHECKS=0; SET AUTOCOMMIT=0; SET SQL_LOG_BIN=0;";
        $commitFlags = "COMMIT; SET FOREIGN_KEY_CHECKS=1; SET UNIQUE_CHECKS=1; SET AUTOCOMMIT=1;";

        // Build password argument if needed
        $passwordArg = !empty($db['password']) ? '--password=' . escapeshellarg($db['password']) : '';

        // 4. Stream each file via Laragon CMD line pipe
        foreach ($sqlFiles as $file) {
            $formattedPath = str_replace('/', '\\', database_path($file));

            $command = sprintf(
                'cmd /c "(echo %s & type "%s" & echo %s) | mysql --host=%s --port=%s --user=%s %s %s"',
                $tuningFlags,
                $formattedPath,
                $commitFlags,
                escapeshellarg($db['host']),
                escapeshellarg($db['port']),
                escapeshellarg($db['username']),
                $passwordArg,
                escapeshellarg($db['database'])
            );

            exec($command);
        }
    }
}
