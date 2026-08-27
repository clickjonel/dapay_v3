<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::disableQueryLog();

        $sqlFiles = [
            'seeders/sql/pk_reports.sql',
            'seeders/sql/pk_report_values.sql',
            'seeders/sql/pk_report_value_disaggregations.sql',
            'seeders/sql/pk_report_users.sql',
        ];

        $db = config('database.connections.' . config('database.default'));

        $tuningFlags = "SET FOREIGN_KEY_CHECKS=0; SET UNIQUE_CHECKS=0; SET AUTOCOMMIT=0; SET SQL_LOG_BIN=0;";
        $commitFlags = "COMMIT; SET FOREIGN_KEY_CHECKS=1; SET UNIQUE_CHECKS=1; SET AUTOCOMMIT=1;";

        $passwordArg = !empty($db['password']) ? '--password=' . escapeshellarg($db['password']) : '';

        foreach ($sqlFiles as $file) {
            $sqlPath = database_path($file);
            $formattedPath = str_replace('/', '\\', $sqlPath);

            // Added --max_allowed_packet=512M and error output capture (2>&1)
            $command = sprintf(
                'cmd /c "(echo %s & type "%s" & echo %s) | mysql --max_allowed_packet=512M --host=%s --port=%s --user=%s %s %s 2>&1"',
                $tuningFlags,
                $formattedPath,
                $commitFlags,
                escapeshellarg($db['host']),
                escapeshellarg($db['port']),
                escapeshellarg($db['username']),
                $passwordArg,
                escapeshellarg($db['database'])
            );

            $output = [];
            $returnVar = 0;
            exec($command, $output, $returnVar);

            // Print errors if the execution fails or drops queries
            if ($returnVar !== 0 || !empty($output)) {
                $this->command->error("Error importing {$file}:");
                $this->command->line(implode("\n", $output));
            }
        }
    }
}
